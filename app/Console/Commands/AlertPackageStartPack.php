<?php

namespace App\Console\Commands;

use App\Enums\StatePresetProduct;
use App\Enums\TypeAlarm;
use App\Enums\TypeDelivery;
use App\Enums\TypePackage;
use App\Enums\TypePackageMaterial;
use App\Enums\TypePointHistory;
use App\Models\Alarm;
use App\Models\Package;
use App\Models\Point;
use App\Models\PointHistory;
use App\Models\Preset;
use App\Models\PresetProduct;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class AlertPackageStartPack extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alert:packageStartPack';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '품목구성알림';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $package = Package::getOngoing();

        if($package && $package->start_pack_at <= Carbon::now()) {
            $presetProducts = $package->presetProducts()->where('alert_pack', 0)->cursor();

            foreach ($presetProducts as $presetProduct) {
                $user = User::find($presetProduct->preset->user_id);

                if ($user) {
                    $packageSetting = $user->packageSetting;

                    if ($packageSetting) {
                        Alarm::create([
                            'type' => TypeAlarm::ALERT_PACKAGE_START_PACK,
                            'user_id' => $presetProduct->preset->user_id,
                            'alarmable_type' => PresetProduct::class,
                            'alarmable_id' => $presetProduct->id,
                        ]);

                        $unlikeMaterials = $packageSetting->materials()->wherePivot('unlike', 1)->get();

                        $packageMaterials = $package->packageMaterials()
                            ->where('type', $presetProduct->package_type == TypePackage::SINGLE ? TypePackageMaterial::SINGLE : TypePackageMaterial::BUNGLE)
                            ->whereNotIn('material_id', $unlikeMaterials->pluck('id')->toArray())
                            ->get();

                        $priceTotal = 0;

                        foreach ($packageMaterials as $packageMaterial) {
                            $presetProduct->materials()->attach($packageMaterial->material_id, [
                                'price' => $packageMaterial->price,
                                'price_origin' => $packageMaterial->price_origin,
                                'value' => $packageMaterial->value,
                                'unit' => $packageMaterial->unit,
                                'count' => $packageMaterial->count,
                                'type' => $packageMaterial->type,
                            ]);

                            $priceTotal += $packageMaterial->price * $packageMaterial->count;
                        }

                        // 비선호 품목으로 인해 빠진 품목이 있다면 최소가격만큼 채우기
                        $priceMinPackage = $presetProduct->package_type == TypePackage::SINGLE ? $package->price_single : $package->price_bungle;

                        while($priceTotal < $priceMinPackage){
                            $packageMaterial = $packageMaterials->get(rand(0, count($packageMaterials) - 1));

                            $prevMaterial = $presetProduct->materials()->where('materials.id', $packageMaterial->material_id)->first();

                            // 기존 개수에만 추가
                            if($prevMaterial){
                                $presetProduct->materials()->updateExistingPivot($packageMaterial->material_id, [
                                    'count' => $prevMaterial->pivot->count + 1,
                                ]);

                                $priceTotal += $packageMaterial->price;
                            }else{ // 새로 추가
                                $presetProduct->materials()->attach($packageMaterial->material_id, [
                                    'price' => $packageMaterial->price,
                                    'price_origin' => $packageMaterial->price_origin,
                                    'unit' => $packageMaterial->unit,
                                    'value' => $packageMaterial->value,
                                    'count' => $packageMaterial->count,
                                    'type' => $packageMaterial->type,
                                ]);

                                $priceTotal += $packageMaterial->price * $packageMaterial->count;
                            }
                        }

                    }
                }

                $presetProduct->update(['alert_pack' => 1]);
            }
        }
    }
}
