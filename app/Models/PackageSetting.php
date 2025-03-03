<?php

namespace App\Models;

use App\Enums\StatePresetProduct;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

class PackageSetting extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'will_order_at' => 'date',
    ];

    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        self::creating(function (PackageSetting $packageSetting) {
            $packageSetting->name = $packageSetting->user->nickname."의 꾸러미";
        });

        self::created(function (PackageSetting $packageSetting) {
            $packageSetting->createPresetProduct();
        });

        self::updated(function (PackageSetting $packageSetting) {
            $prevActive = $packageSetting->getOriginal('active');

            if(!$prevActive && $packageSetting->active){
                $ongoingPackagePresetProduct = $packageSetting->user->ongoingPackagePresetProducts()->first();

                if(!$ongoingPackagePresetProduct)
                    $packageSetting->createPresetProduct();
            }

            if($prevActive && !$packageSetting->active) {
                $packageSetting->user->presetProducts()->whereNotNull('package_id')->where('state', StatePresetProduct::BEFORE_PAYMENT)->delete();

                StopHistory::create([
                    'user_id' => $packageSetting->user_id,
                    'reason' => $packageSetting->reason,
                    'and_so_on' => $packageSetting->and_so_on,
                    'memo' => $packageSetting->memo,
                ]);
            }
        });
    }

    public function createPresetProduct()
    {
        $canOrderPackage = Package::getCanOrder();

        $preset = Preset::create([
            'user_id' => $this->user->id,
        ]);

        $result = $preset->attachProducts([
            'package_id' => $canOrderPackage->id
        ], $this);

        if(!$result['success'])
            Log::error('[패키지 생성] 회차출고생성 실패', ['message' => $result['message']]);
    }

    public function stopHistories()
    {
        return $this->hasMany(StopHistory::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }

    public function delivery(): BelongsTo
    {
        return $this->belongsTo(Delivery::class);
    }

    public function firstPackage(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'first_package_id');
    }

    public function materials()
    {
        return $this->belongsToMany(Material::class, 'package_setting_material')->withPivot([
            'unlike'
        ]);
    }


}
