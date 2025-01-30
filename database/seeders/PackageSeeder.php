<?php

namespace Database\Seeders;

use App\Enums\TypeCategory;
use App\Enums\TypeMaterial;
use App\Enums\TypePackageMaterial;
use App\Enums\TypeTag;
use App\Models\Category;
use App\Models\Material;
use App\Models\Package;
use App\Models\PackageMaterial;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Package::factory()->create();
    }

    public function createPackages()
    {
        $packages = Package::factory()->count(2)->create();

        $willDeliveryAt = \Illuminate\Support\Carbon::now()->addWeek()->startOfWeek()->addDays(4);

        Package::latest()->first()->update([
            'start_pack_wait_at' => (clone $willDeliveryAt)->addDays(1)->setHour(0)->setMinutes(0),
            'finish_pack_wait_at' => \Illuminate\Support\Carbon::now()->addWeek()->startOfWeek()->addDays(0)->setHour(16)->setMinutes(0),
            'start_pack_at' => Carbon::now()->addWeek()->startOfWeek()->addDays(0)->setHour(16)->setMinutes(0),
            'finish_pack_at' => Carbon::now()->addWeek()->startOfWeek()->addDays(1)->setHour(9)->setMinutes(0),
            'start_delivery_ready_at' => Carbon::now()->addWeek()->startOfWeek()->addDays(1)->setHour(9)->setMinutes(0),
            'finish_delivery_ready_at' => Carbon::now()->addWeek()->startOfWeek()->addDays(2)->setHour(18)->setMinutes(0),
            'start_will_out_at' => Carbon::now()->addWeek()->startOfWeek()->addDays(2)->setHour(18)->setMinutes(0),
            'finish_will_out_at' => Carbon::now()->addWeek()->startOfWeek()->addDays(3)->setHour(18)->setMinutes(0),
            'will_delivery_at' => $willDeliveryAt,
        ]);

        $categories = [
            [
                'title' => '근채류',
                'type' => TypeCategory::PACKAGE,
                'materials' => [
                    [
                        'type' => TypeMaterial::PACKAGE,
                        'title' => '적근대',
                        'img' => '/images/material1.png',

                        'pivot' => [
                            'type' => TypePackageMaterial::SINGLE,
                            'count' => 120,
                            'unit' => 'g',
                            'price_origin' => 1800,
                            'price' => 1800
                        ],
                    ],
                    [
                        'type' => TypeMaterial::PACKAGE,
                        'title' => '적근대',
                        'img' => '/images/material1.png',

                        'pivot' => [
                            'type' => TypePackageMaterial::CAN_SELECT,
                            'count' => 120,
                            'unit' => 'g',
                            'price_origin' => 1800,
                            'price' => 1800
                        ],
                    ],
                    [
                        'type' => TypeMaterial::PACKAGE,
                        'title' => '적근대',
                        'img' => '/images/material1.png',

                        'pivot' => [
                            'type' => TypePackageMaterial::BUNGLE,
                            'count' => 240,
                            'unit' => 'g',
                            'price_origin' => 3600,
                            'price' => 3600
                        ],
                    ],


                    [
                        'type' => TypeMaterial::PACKAGE,
                        'title' => '청경채',
                        'img' => '/images/material2.png',

                        'pivot' => [
                            'type' => TypePackageMaterial::SINGLE,
                            'count' => 500,
                            'unit' => 'g',
                            'price_origin' => 1800,
                            'price' => 1800
                        ],
                    ],
                    [
                        'type' => TypeMaterial::PACKAGE,
                        'title' => '청경채',
                        'img' => '/images/material2.png',

                        'pivot' => [
                            'type' => TypePackageMaterial::CAN_SELECT,
                            'count' => 500,
                            'unit' => 'g',
                            'price_origin' => 1800,
                            'price' => 1800
                        ],
                    ],
                    [
                        'type' => TypeMaterial::PACKAGE,
                        'title' => '청경채',
                        'img' => '/images/material2.png',

                        'pivot' => [
                            'type' => TypePackageMaterial::BUNGLE,
                            'count' => 500,
                            'unit' => 'g',
                            'price_origin' => 3600,
                            'price' => 3600
                        ],
                    ],


                    [
                        'type' => TypeMaterial::PACKAGE,
                        'title' => '쪽파',
                        'img' => '/images/material3.png',

                        'pivot' => [
                            'type' => TypePackageMaterial::SINGLE,
                            'count' => 120,
                            'unit' => 'g',
                            'price_origin' => 5000,
                            'price' => 5000
                        ],
                    ],
                    [
                        'type' => TypeMaterial::PACKAGE,
                        'title' => '쪽파',
                        'img' => '/images/material1.png',

                        'pivot' => [
                            'type' => TypePackageMaterial::CAN_SELECT,
                            'count' => 120,
                            'unit' => 'g',
                            'price_origin' => 5000,
                            'price' => 5000
                        ],
                    ],
                    [
                        'type' => TypeMaterial::PACKAGE,
                        'title' => '쪽파',
                        'img' => '/images/material1.png',

                        'pivot' => [
                            'type' => TypePackageMaterial::BUNGLE,
                            'count' => 240,
                            'unit' => 'g',
                            'price_origin' => 10000,
                            'price' => 10000
                        ],
                    ],


                    [
                        'type' => TypeMaterial::PACKAGE,
                        'title' => '브로콜리',
                        'img' => '/images/material4.png',

                        'pivot' => [
                            'type' => TypePackageMaterial::SINGLE,
                            'count' => 1,
                            'unit' => '개',
                            'price_origin' => 1300,
                            'price' => 1300
                        ],
                    ],
                    [
                        'type' => TypeMaterial::PACKAGE,
                        'title' => '브로콜리',
                        'img' => '/images/material4.png',

                        'pivot' => [
                            'type' => TypePackageMaterial::CAN_SELECT,
                            'count' => 1,
                            'unit' => '개',
                            'price_origin' => 1300,
                            'price' => 1300
                        ],
                    ],
                    [
                        'type' => TypeMaterial::PACKAGE,
                        'title' => '브로콜리',
                        'img' => '/images/material4.png',

                        'pivot' => [
                            'type' => TypePackageMaterial::BUNGLE,
                            'count' => 2,
                            'unit' => '개',
                            'price_origin' => 2600,
                            'price' => 2600
                        ],
                    ],


                    [
                        'type' => TypeMaterial::PACKAGE,
                        'title' => '양상추',
                        'img' => '/images/material5.png',

                        'pivot' => [
                            'type' => TypePackageMaterial::SINGLE,
                            'count' => 120,
                            'unit' => 'g',
                            'price_origin' => 1800,
                            'price' => 1800
                        ],
                    ],
                    [
                        'type' => TypeMaterial::PACKAGE,
                        'title' => '양상추',
                        'img' => '/images/material5.png',

                        'pivot' => [
                            'type' => TypePackageMaterial::CAN_SELECT,
                            'count' => 120,
                            'unit' => 'g',
                            'price_origin' => 1800,
                            'price' => 1800
                        ],
                    ],
                    [
                        'type' => TypeMaterial::PACKAGE,
                        'title' => '양상추',
                        'img' => '/images/material5.png',

                        'pivot' => [
                            'type' => TypePackageMaterial::BUNGLE,
                            'count' => 240,
                            'unit' => 'g',
                            'price_origin' => 3600,
                            'price' => 3600
                        ],
                    ],


                    [
                        'type' => TypeMaterial::PACKAGE,
                        'title' => '상추',
                        'img' => '/images/material6.png',

                        'pivot' => [
                            'type' => TypePackageMaterial::SINGLE,
                            'count' => 120,
                            'unit' => 'g',
                            'price_origin' => 1800,
                            'price' => 1800
                        ],
                    ],
                    [
                        'type' => TypeMaterial::PACKAGE,
                        'title' => '상추',
                        'img' => '/images/material6.png',

                        'pivot' => [
                            'type' => TypePackageMaterial::CAN_SELECT,
                            'count' => 120,
                            'unit' => 'g',
                            'price_origin' => 1800,
                            'price' => 1800
                        ],
                    ],
                    [
                        'type' => TypeMaterial::PACKAGE,
                        'title' => '상추',
                        'img' => '/images/material6.png',

                        'pivot' => [
                            'type' => TypePackageMaterial::BUNGLE,
                            'count' => 240,
                            'unit' => 'g',
                            'price_origin' => 3600,
                            'price' => 3600
                        ],
                    ],
                ],
            ],
        ];

        $tag1 = Tag::create(['type' => TypeTag::PACKAGE, 'title' => '무농약']);
        $tag2 = Tag::create(['type' => TypeTag::PACKAGE, 'title' => '세척']);

        foreach($categories as $category){
            $createdCategory = Category::create(Arr::except($category, ['materials']));

            foreach($category['materials'] as $material){
                $material['category_id'] = $createdCategory->id;

                $createdMaterial = Material::create(Arr::except($material, ['pivot', 'img']));

                foreach($packages as $package){
                    $package->materials()->attach($createdMaterial->id, $material['pivot']);
                    $packageMaterial = PackageMaterial::where('package_id', $package->id)->where('material_id', $createdMaterial->id)->first();
                    $packageMaterial->tags()->sync([$tag1->id, $tag2->id]);
                }

                if(config("app.env") != 'local'){
                    $createdMaterial->addMedia(public_path($material['img']))->preservingOriginal()->toMediaCollection("img", "s3");
                }
            }
        }
    }
}
