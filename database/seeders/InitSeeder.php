<?php

namespace Database\Seeders;

use App\Enums\DeliveryCompany;
use App\Enums\MomentCouponGroup;
use App\Enums\StateOrder;
use App\Enums\StatePresetProduct;
use App\Enums\StateProduct;
use App\Enums\TargetCouponGroup;
use App\Enums\TypeBanner;
use App\Enums\TypeCategory;
use App\Enums\TypeCouponGroup;
use App\Enums\TypeDelivery;
use App\Enums\TypeDeliveryPrice;
use App\Enums\TypeDiscount;
use App\Enums\TypeExpire;
use App\Enums\TypeMaterial;
use App\Enums\TypeOption;
use App\Enums\TypePackageMaterial;
use App\Enums\TypePackage;
use App\Enums\TypePointHistory;
use App\Enums\TypeTag;
use App\Models\Banner;
use App\Models\Bookmark;
use App\Models\Card;
use App\Models\Category;
use App\Models\City;
use App\Models\Comment;
use App\Models\Count;
use App\Models\County;
use App\Models\Coupon;
use App\Models\CouponGroup;
use App\Models\CouponHistory;
use App\Models\Delivery;
use App\Models\Faq;
use App\Models\FaqCategory;
use App\Models\Farm;
use App\Models\FarmStory;
use App\Models\Grade;
use App\Models\Like;
use App\Models\Material;
use App\Models\Option;
use App\Models\Order;
use App\Models\Package;
use App\Models\PackageMaterial;
use App\Models\PackageSetting;
use App\Models\PayMethod;
use App\Models\Point;
use App\Models\PointHistory;
use App\Models\Pop;
use App\Models\Preset;
use App\Models\PresetProduct;
use App\Models\Product;
use App\Models\Project;
use App\Models\Qna;
use App\Models\QnaCategory;
use App\Models\Recipe;
use App\Models\ReportCategory;
use App\Models\Review;
use App\Models\Size;
use App\Models\Tag;
use App\Models\User;
use App\Models\VegetableStory;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use ReflectionClass;

class InitSeeder extends Seeder
{
    protected $imgs = [
        "/images/about_slide_1.png",
        "/images/about_slide_1_1.png",
        "/images/about_slide_1_2.png",
        "/images/about_slide_1_3.png",
        "/images/about_slide_2_1.png",
        "/images/about_slide_2_2.png",
        "/images/about_slide_2_3.png",
        "/images/about_slide_3_1.png",
        "/images/about_slide_3_2.png",
        "/images/about_slide_3_3.png",
    ];

    protected $farmImgs = [
        "/images/farmImgs/farmImg1.png",
        "/images/farmImgs/farmImg2.png",
        "/images/farmImgs/farmImg3.png",
        "/images/farmImgs/farmImg4.png",
        "/images/farmImgs/farmImg5.png",
        "/images/farmImgs/farmImg6.png",
    ];

    protected $bannerImages = [
        '/images/banner1.jpg',
        '/images/banner2.jpg',
        '/images/banner3.jpg',
    ];

    protected $user;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->truncateTables();

        DB::statement("SET foreign_key_checks=0");

        $this->createDeliveryCompanies();
        $this->createGrades();
        $this->createUsers();
        $this->createBanners();
        $this->createPops();
        $this->createCounts();
        $this->createLocations();
        $this->createFarms();
        $this->createFarmStories();
        $this->createCategories();
        $this->createProducts();
        $this->createProject();
        $this->createTags();
        $this->createCouponGroups();
        $this->createReportCategories();
        $this->createPayMethods();
        $this->createPackages();
        $this->createPackageSettings();
        // $this->createCards();
        $this->createOrders();
        $this->createRecipes();
        $this->createReviews();
        $this->createVegetableStories();
        $this->createQnaCategories();
        $this->createFaqCategories();
        $this->createComments();
        $this->createBookmarks();
        $this->createPointHistories();

        // test@naver.com 사용자에 대한 패키지 구매 생성
        $this->user = User::where('email', 'test@naver.com')->first();
        if ($this->user) {
            $this->createTestPackagePurchase();
        }
    }
    private function truncateTables(): void
    {
        DB::statement('SET foreign_key_checks=0');

        DB::table('media')->truncate();

        $modelFiles = glob(app_path('Models/*.php'));

        $modelsToTruncate = [];

        foreach ($modelFiles as $file) {
            $className = $this->getClassNameFromFile($file);
            if (!$className) {
                continue;
            }

            try {
                $reflection = new ReflectionClass($className);

                // Eloquent 모델인지 확인
                if ($reflection->isSubclassOf(\Illuminate\Database\Eloquent\Model::class) &&
                    !$reflection->isAbstract()) {
                    $model = $reflection->newInstance();
                    $table = $model->getTable();
                    $modelsToTruncate[$table] = $className;
                }
            } catch (\Exception $e) {
                \Log::warning("Skipping {$className}: " . $e->getMessage());
                continue;
            }
        }

        foreach ($modelsToTruncate as $className) {
            try {
                $reflection = new ReflectionClass($className);
                $model = $reflection->newInstance();
                $table = $model->getTable();

                DB::table($table)->truncate();
                \Log::info("Truncated table: {$table}");
            } catch (\Exception $e) {
                \Log::error("Failed to truncate {$className}: " . $e->getMessage());
            }
        }

        DB::statement('SET foreign_key_checks=1');
    }

    private function getClassNameFromFile(string $file): ?string
    {
        $relativePath = str_replace(app_path('Models/'), '', $file);
        $className = 'App\\Models\\' . str_replace('/', '\\', substr($relativePath, 0, -4));

        if (!class_exists($className)) {
            return null;
        }

        return $className;
    }
    public function createBookmarks()
    {
        $recipes = Recipe::inRandomOrder()->take(5)->get();
        $vegetableStories = VegetableStory::inRandomOrder()->take(5)->get();

        foreach($recipes as $recipe){
            Bookmark::create([
                'user_id' => $this->user->id,
                'bookmarkable_id' => $recipe->id,
                'bookmarkable_type' => Recipe::class,
            ]);
        }

        foreach($vegetableStories as $vegetableStory){
            Bookmark::create([
                'user_id' => $this->user->id,
                'bookmarkable_id' => $vegetableStory->id,
                'bookmarkable_type' => VegetableStory::class,
            ]);
        }
    }

    public function createComments()
    {
        $vegetableStories = VegetableStory::get();

        foreach($vegetableStories as $vegetableStory){
            Comment::factory()->create([
                'commentable_type' => VegetableStory::class,
                'commentable_id' => $vegetableStory->id,
                'user_id' => $this->user->id
            ]);
        }
    }
    public function createQnaCategories()
    {
        $items = [
            [
                'title' => '배송'
            ],
            [
                'title' => '교환/반품/취소'
            ],
            [
                'title' => '서비스'
            ],
            [
                'title' => '주문/결제'
            ],
            [
                'title' => '상품확인'
            ],
            [
                'title' => '회원정보'
            ],
            [
                'title' => '기타'
            ],
        ];

        foreach($items as $item){
            $qnaCategory = QnaCategory::create($item);

            Qna::factory()->count(5)->create([
                'qna_category_id' => $qnaCategory->id,
                'user_id' => $this->user->id,
            ]);

            Qna::factory()->count(5)->create([
                'qna_category_id' => $qnaCategory->id,
                'user_id' => $this->user->id,
                'answer' => '답변예시입니다
                이런식으로 답변이 뜨면 되는데 줄바꿈도 제대로 되는지 봐주세요',
                'answered_at' => Carbon::now()
            ]);
        }
    }
    public function createFaqCategories()
    {
        $items = [
            [
                'title' => '배송'
            ],
            [
                'title' => '교환/반품/취소'
            ],
            [
                'title' => '서비스'
            ],
            [
                'title' => '주문/결제'
            ],
            [
                'title' => '회원정보'
            ],
        ];

        foreach($items as $item){
            $faqCategory = FaqCategory::create($item);

            Faq::factory()->count(10)->create([
                'faq_category_id' => $faqCategory->id,
            ]);
        }
    }
    public function createPackageSettings()
    {
        PackageSetting::factory()->create([
            'user_id' => $this->user->id
        ]);
    }

    public function createVegetableStories()
    {
        $items = VegetableStory::factory()->count(30)->create([
            'user_id' => $this->user->id,
        ]);

        foreach($items as $item){
            $tags = Tag::where('type', TypeTag::VEGETABLE_STORY)->inRandomOrder()->take(5)->get();

            $item->tags()->sync($tags->pluck("id")->toArray());

            if(config("app.env") != 'local'){
                $item->addMedia(public_path($this->farmImgs[rand(0, count($this->farmImgs) - 1)]))->preservingOriginal()->toMediaCollection("imgs", "s3");
            }
        }
    }

    public function createPresetProducts($preset)
    {
        $states = StatePresetProduct::getValues();

        foreach($states as $state){
            $product = Product::inRandomOrder()->first() ?? Product::factory()->create();
            $option = Option::inRandomOrder()->first() ?? Option::factory()->create();

            $presetProduct = PresetProduct::factory()->create([
                'preset_id' => $preset->id,
                'package_id' => null,
                'product_id' => $product->id,
                'product_price' => $product->price,
                'product_price_origin' => $product->price_origin,
                'count' => rand(1,2),
                'option_id' => $option->id,
                'option_title' => $option->title,
                'option_price' => $option->price,
                'option_type' => $option->type,
                'state'=> $state,
            ]);

            $package = Package::inRandomOrder()->first() ?? Package::factory()->create();

            $packageSetting = $this->user->packageSetting ?? PackageSetting::factory()->create(['user_id' => $this->user->id]);

            $packagePresetProduct = PresetProduct::factory()->create([
                'preset_id' => $preset->id,
                'package_id' => $package->id,
                'package_count' => $package->count,
                'package_name' => $packageSetting->name,
                'package_price' => $package->price_single,
                'package_type' => $packageSetting->type,
                'package_active' => rand(0, 1),
                'package_will_delivery_at' => $package->will_delivery_at,
                'product_id' => null,
                'state'=> $state,
            ]);

            $materials = $package->materials;

            foreach($materials as $material){
                $packagePresetProduct->materials()->attach($material->id, [
                    'price' => $material->pivot->price,
                    'price_origin' => $material->pivot->price_origin,
                    'unit' => $material->pivot->unit,
                    'count' => $material->pivot->count,
                    'value' => $material->pivot->value,
                ]);
            }
        }
    }
    public function createPresets($order)
    {
        $presetA = Preset::factory()->create([
            'order_id' => $order->id,
            'user_id' => $this->user->id,
        ]);

        $presetB = Preset::factory()->create([
            'order_id' => $order->id,
            'user_id' => $this->user->id,
        ]);

        $this->createPresetProducts($presetA);
    }

    public function createOrders()
    {
        $successOrder = Order::factory()->create([
            'user_id'=> $this->user->id,
            'state' => StateOrder::SUCCESS
        ]);

        $this->createPresets($successOrder);
    }
    public function createRecipes()
    {
        $recipes = Recipe::factory()->count(30)->create();

        foreach($recipes as $recipe){
            $package = Package::inRandomOrder()->first();
            $tags = Tag::where('type', TypeTag::RECIPE)->inRandomOrder()->take(5)->get();

            $recipe->packages()->attach($package->id);
            $recipe->tags()->sync($tags->pluck("id")->toArray());

            if(config("app.env") != 'local'){
                $recipe->addMedia(public_path($this->farmImgs[rand(0, count($this->farmImgs) - 1)]))->preservingOriginal()->toMediaCollection("imgs", "s3");
            }
        }
    }

    public function createCards()
    {
        Card::factory()->create([
            'user_id' => $this->user->id,
            'name' => '신한카드',
            'number' => '9754123458769998',
        ]);

        Card::factory()->create([
            'user_id' => $this->user->id,
            'name' => '현대카드',
            'number' => '4856789411145555',
        ]);
    }

    public function createPackage($week = 0)
    {
        // 현재 날짜에서 최소 2일 이후부터 시작하도록 설정 (getCanOrders 메서드와 호환되도록)
        $baseDate = Carbon::now()->addDays(3); // 최소 2일 이후보다 더 여유있게 3일 이후부터 시작
        $willDeliveryAt = (clone $baseDate)->addWeeks($week)->startOfWeek()->addDays(4);

        Package::factory()->create([
            'count' => Package::count() + 1,
            'start_pack_wait_at' => (clone $willDeliveryAt)->addDays(1)->setHour(0)->setMinutes(0),
            'finish_pack_wait_at' => (clone $baseDate)->addWeeks($week)->startOfWeek()->addDays(0)->setHour(16)->setMinutes(0),
            'start_pack_at' => (clone $baseDate)->addWeeks($week)->startOfWeek()->addDays(0)->setHour(16)->setMinutes(0),
            'finish_pack_at' => (clone $baseDate)->addWeeks($week)->startOfWeek()->addDays(1)->setHour(9)->setMinutes(0),
            'start_delivery_ready_at' => (clone $baseDate)->addWeeks($week)->startOfWeek()->addDays(1)->setHour(9)->setMinutes(0),
            'finish_delivery_ready_at' => (clone $baseDate)->addWeeks($week)->startOfWeek()->addDays(2)->setHour(18)->setMinutes(0),
            'start_will_out_at' => (clone $baseDate)->addWeeks($week)->startOfWeek()->addDays(2)->setHour(18)->setMinutes(0),
            'finish_will_out_at' => (clone $baseDate)->addWeeks($week)->startOfWeek()->addDays(3)->setHour(18)->setMinutes(0),
            'will_delivery_at' => $willDeliveryAt,
        ]);
    }

    public function createPackages()
    {
        $this->createPackage(0);
        $this->createPackage(1);
        $this->createPackage(2);
        $this->createPackage(3);

        $packages = Package::get();

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
                            'value' => 120,
                            'unit' => 'g',
                            'price_origin' => 5500,
                            'price' => 5500
                        ],
                    ],
                    [
                        'type' => TypeMaterial::PACKAGE,
                        'title' => '피망',
                        'img' => '/images/material1.png',

                        'pivot' => [
                            'type' => TypePackageMaterial::CAN_SELECT,
                            'value' => 120,
                            'unit' => 'g',
                            'price_origin' => 1800,
                            'price' => 1800
                        ],
                    ],
                    [
                        'type' => TypeMaterial::PACKAGE,
                        'title' => '당근',
                        'img' => '/images/material1.png',

                        'pivot' => [
                            'type' => TypePackageMaterial::BUNGLE,
                            'value' => 240,
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
                            'value' => 500,
                            'unit' => 'g',
                            'price_origin' => 4500,
                            'price' => 4500
                        ],
                    ],
                    [
                        'type' => TypeMaterial::PACKAGE,
                        'title' => '샐러리',
                        'img' => '/images/material2.png',

                        'pivot' => [
                            'type' => TypePackageMaterial::CAN_SELECT,
                            'value' => 500,
                            'unit' => 'g',
                            'price_origin' => 1800,
                            'price' => 1800
                        ],
                    ],
                    [
                        'type' => TypeMaterial::PACKAGE,
                        'title' => '감자',
                        'img' => '/images/material2.png',

                        'pivot' => [
                            'type' => TypePackageMaterial::BUNGLE,
                            'value' => 500,
                            'unit' => 'g',
                            'price_origin' => 4600,
                            'price' => 4600
                        ],
                    ],


                    [
                        'type' => TypeMaterial::PACKAGE,
                        'title' => '쪽파',
                        'img' => '/images/material3.png',

                        'pivot' => [
                            'type' => TypePackageMaterial::SINGLE,
                            'value' => 120,
                            'unit' => 'g',
                            'price_origin' => 5000,
                            'price' => 5000
                        ],
                    ],
                    [
                        'type' => TypeMaterial::PACKAGE,
                        'title' => '병아리콩',
                        'img' => '/images/material1.png',

                        'pivot' => [
                            'type' => TypePackageMaterial::CAN_SELECT,
                            'value' => 120,
                            'unit' => 'g',
                            'price_origin' => 5000,
                            'price' => 5000
                        ],
                    ],
                    [
                        'type' => TypeMaterial::PACKAGE,
                        'title' => '비트',
                        'img' => '/images/material1.png',

                        'pivot' => [
                            'type' => TypePackageMaterial::BUNGLE,
                            'value' => 240,
                            'unit' => 'g',
                            'price_origin' => 10900,
                            'price' => 10900
                        ],
                    ],


                    [
                        'type' => TypeMaterial::PACKAGE,
                        'title' => '브로콜리',
                        'img' => '/images/material4.png',

                        'pivot' => [
                            'type' => TypePackageMaterial::SINGLE,
                            'value' => 1,
                            'unit' => '개',
                            'price_origin' => 1300,
                            'price' => 1300
                        ],
                    ],
                    [
                        'type' => TypeMaterial::PACKAGE,
                        'title' => '토마토',
                        'img' => '/images/material4.png',

                        'pivot' => [
                            'type' => TypePackageMaterial::CAN_SELECT,
                            'value' => 1,
                            'unit' => '개',
                            'price_origin' => 1300,
                            'price' => 1300
                        ],
                    ],
                    [
                        'type' => TypeMaterial::PACKAGE,
                        'title' => '블루베리',
                        'img' => '/images/material4.png',

                        'pivot' => [
                            'type' => TypePackageMaterial::BUNGLE,
                            'value' => 2,
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
                            'value' => 120,
                            'unit' => 'g',
                            'price_origin' => 1800,
                            'price' => 1800
                        ],
                    ],
                    [
                        'type' => TypeMaterial::PACKAGE,
                        'title' => '상추',
                        'img' => '/images/material5.png',

                        'pivot' => [
                            'type' => TypePackageMaterial::CAN_SELECT,
                            'value' => 120,
                            'unit' => 'g',
                            'price_origin' => 1800,
                            'price' => 1800
                        ],
                    ],
                    [
                        'type' => TypeMaterial::PACKAGE,
                        'title' => '오이',
                        'img' => '/images/material5.png',

                        'pivot' => [
                            'type' => TypePackageMaterial::BUNGLE,
                            'value' => 240,
                            'unit' => 'g',
                            'price_origin' => 3600,
                            'price' => 3600
                        ],
                    ],


                    [
                        'type' => TypeMaterial::PACKAGE,
                        'title' => '검은콩',
                        'img' => '/images/material6.png',

                        'pivot' => [
                            'type' => TypePackageMaterial::SINGLE,
                            'value' => 120,
                            'unit' => 'g',
                            'price_origin' => 1800,
                            'price' => 1800
                        ],
                    ],
                    [
                        'type' => TypeMaterial::PACKAGE,
                        'title' => '치아씨드',
                        'img' => '/images/material6.png',

                        'pivot' => [
                            'type' => TypePackageMaterial::CAN_SELECT,
                            'value' => 120,
                            'unit' => 'g',
                            'price_origin' => 1800,
                            'price' => 1800
                        ],
                    ],
                    [
                        'type' => TypeMaterial::PACKAGE,
                        'title' => '대파',
                        'img' => '/images/material6.png',

                        'pivot' => [
                            'type' => TypePackageMaterial::BUNGLE,
                            'value' => 240,
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

    public function createReportCategories()
    {
        $items = [
            [
                'title' => '관련없는 이미지/내용'
            ],
            [
                'title' => '욕설/비방'
            ],
            [
                'title' => '광고/홍보글'
            ],
            [
                'title' => '도배 및 중복'
            ],
            [
                'title' => '기타'
            ],
        ];

        foreach($items as $item){
            ReportCategory::create($item);
        }
    }

    public function createCouponGroups()
    {
        $items = [
            [
                'title' => '개인정보입력 할인 쿠폰',
                'moment' => MomentCouponGroup::UPDATE_PROFILE,
                'type' => TypeCouponGroup::ALL,
                'target' => TargetCouponGroup::ALL,
                'type_discount' => TypeDiscount::NUMBER,
                'value' => 1000,
                'max_price_discount' => 1000,
                'min_price_order' => 1000,
                'type_expire' => TypeExpire::FROM_DOWNLOAD,
                'days' => 60,
            ],
            /*[ // 등급별로 각 등급 혜택금액에 맞게 넣어야함
                'title' => '등급쿠폰',
                'moment' => MomentCouponGroup::GRADE,
                'grade_id' => '',
                'type' => TypeCouponGroup::ALL,
                'target' => TargetCouponGroup::ALL,
                'type_discount' => TypeDiscount::NUMBER,
                'value' => '',
                'max_price_discount' => '',
                'min_price_order' => '',
                'type_expire' => TypeExpire::FROM_DOWNLOAD,
                'days' => 60,
            ],*/
            [
                'title' => '생일쿠폰',
                'moment' => MomentCouponGroup::BIRTHDAY,
                'type' => TypeCouponGroup::ALL,
                'target' => TargetCouponGroup::ALL,
                'type_discount' => TypeDiscount::NUMBER,
                'value' => 3000,
                'max_price_discount' => 3000,
                'min_price_order' => 3000,
                'type_expire' => TypeExpire::FROM_DOWNLOAD,
                'days' => 60,
            ],
            [
                'title' => '첫구매',
                'moment' => MomentCouponGroup::FIRST_ORDER,
                'type' => TypeCouponGroup::ALL,
                'target' => TargetCouponGroup::ALL,
                'type_discount' => TypeDiscount::NUMBER,
                'value' => 3000,
                'max_price_discount' => 3000,
                'min_price_order' => 3000,
                'type_expire' => TypeExpire::FROM_DOWNLOAD,
                'days' => 60,
            ],
            [
                'title' => '10% 할인 쿠폰',
                'type' => TypeCouponGroup::ALL,
                'target' => TargetCouponGroup::ALL,
                'type_discount' => TypeDiscount::RATIO,
                'value' => 10,
                'max_price_discount' => 10000,
                'min_price_order' => 3000,
                'type_expire' => TypeExpire::FROM_DOWNLOAD,
                'days' => 60,
            ],
            [
                'title' => '1,000원 할인 쿠폰',
                'type' => TypeCouponGroup::ALL,
                'target' => TargetCouponGroup::ALL,
                'type_discount' => TypeDiscount::NUMBER,
                'value' => 1000,
                'max_price_discount' => 1000,
                'min_price_order' => 3000,
                'type_expire' => TypeExpire::SPECIFIC,
                'started_at' => Carbon::now()->subDays(30),
                'finished_at' => Carbon::now()->addDays(30),
            ],
        ];

        foreach($items as $item){
            CouponGroup::create($item);
        }

        $users = User::get();

        $couponGroup = CouponGroup::where('title', '10% 할인 쿠폰')->first();

        foreach($users as $user){
            Coupon::factory()->create([
                'user_id' => $user->id,
                'coupon_group_id' => $couponGroup->id,
            ]);
        }

        $couponGroup = CouponGroup::where('title', '1,000원 할인 쿠폰')->first();

        foreach($users as $user){
            Coupon::factory()->create([
                'user_id' => $user->id,
                'coupon_group_id' => $couponGroup->id,
            ]);
        }

        CouponGroup::factory()->count(4)->create();
    }
    public function createProject()
    {
        $product = Product::first();

        $project = Project::factory()->create([
            'product_id' => $product->id,
            'started_at' => Carbon::now()->subMonths(1),
            'finished_at' => Carbon::now()->addMonths(1),
            'count_goal' => 2000,
            'count_participate' => 643,
        ]);

        $project->tags()->attach($product->tags->pluck("id")->toArray());

        if(config("app.env") != 'local'){
            $product->addMedia(public_path('/images/about_slide_1_1.png'))->preservingOriginal()->toMediaCollection("img", "s3");
        }

    }
    public function createProducts()
    {
        // 태그 연동 필요
        // 옵션 연동 필요
        $farm = Farm::inRandomOrder()->first();

        $items = [
            [
                'imgs' => [
                    '/images/about_slide_1_1.png',
                    '/images/about_slide_1_2.png',
                    '/images/about_slide_1_3.png',
                ],
                'requiredOptions' => [
                    [
                        'title' => '조생감귤 5kg',
                        'type' => TypeOption::REQUIRED,
                        'price' => 0,
                        'count' => 10000
                    ],
                    [
                        'title' => '조생감귤 5kg',
                        'type' => TypeOption::REQUIRED,
                        'price' => 15000,
                        'count' => 10000
                    ],
                ],
                'tags' => [
                    [
                        'type' => TypeTag::PRODUCT,
                        'title' => 'NEW',
                    ],
                    [
                        'type' => TypeTag::PRODUCT,
                        'title' => '특가'
                    ],
                    [
                        'type' => TypeTag::PRODUCT,
                        'title' => '무농약'
                    ]
                ],

                'state' => StateProduct::ONGOING,
                'category_id' => Category::inRandomOrder()->first()->id,
                'farm_id' => $farm->id,
                'city_id' => $farm->county->city_id,
                'county_id' => $farm->county->id,
                'title' => '노지 조생 감귤 5kg 10kg',
                'price' => 20000,
                'price_origin' => 40000,
                'need_tax' => 0,
                'can_use_coupon' => 1,
                'can_use_point' => 1,
                'type_delivery' => TypeDelivery::FREE,
                'type_delivery_price' => TypeDeliveryPrice::STATIC,
                'price_delivery' => 3000,
                'prices_delivery' => "[]",
                'min_price_for_free_delivery_price' => 50000,
                'can_delivery_far_place' => 1,
                // 'delivery_price_far_place' => 3000,
                'delivery_price_refund' => 3000,
                'delivery_address_refund' => '서울특별시 강남구 143 43',
                'description' => "
                <img src='https://api.nongjangs.com/images/%EA%B7%A4/1.png' />
                <br/>
                <img src='https://api.nongjangs.com/images/%EA%B7%A4/2.png' />
                <br/>
                <img src='https://api.nongjangs.com/images/%EA%B7%A4/3.png' />
                <br/>
                <img src='https://api.nongjangs.com/images/%EA%B7%A4/4.png' />
                <br/>
                <img src='https://api.nongjangs.com/images/%EA%B7%A4/5.png' />
                <br/>
                ",
            ],
            [
                'imgs' => [
                    '/images/about_slide_2_1.png',
                    '/images/about_slide_2_2.png',
                    '/images/about_slide_2_3.png',
                ],
                'requiredOptions' => [
                    [
                        'title' => '무농약 블루베리 생과 500g',
                        'type' => TypeOption::REQUIRED,
                        'price' => 0,
                        'count' => 10000
                    ],
                    [
                        'title' => '무농약 블루베리 생과 1kg',
                        'type' => TypeOption::REQUIRED,
                        'price' => 25900,
                        'count' => 10000
                    ],
                ],
                'tags' => [
                    [
                        'type' => TypeTag::PRODUCT,
                        'title' => 'NEW',
                    ],
                    [
                        'type' => TypeTag::PRODUCT,
                        'title' => '특가'
                    ],
                    [
                        'type' => TypeTag::PRODUCT,
                        'title' => '무농약'
                    ]
                ],

                'state' => StateProduct::ONGOING,
                'category_id' => Category::inRandomOrder()->first()->id,
                'farm_id' => $farm->id,
                'city_id' => $farm->county->city_id,
                'county_id' => $farm->county->id,
                'title' => '무농약 블루베리 생과 500g 1kg',
                'price' => 30900,
                'price_origin' => 99800,
                'need_tax' => 0,
                'can_use_coupon' => 1,
                'can_use_point' => 1,
                'type_delivery' => TypeDelivery::FREE,
                'type_delivery_price' => TypeDeliveryPrice::STATIC,
                'price_delivery' => 3000,
                'prices_delivery' => "[]",
                'min_price_for_free_delivery_price' => 50000,
                'can_delivery_far_place' => 1,
                // 'delivery_price_far_place' => 3000,
                'delivery_price_refund' => 3000,
                'delivery_address_refund' => '서울특별시 강남구 143 43',
                'description' => "
                <img src='https://api.nongjangs.com/images/%EB%B8%94%EB%A3%A8%EB%B2%A0%EB%A6%AC/1.png' />
                <br/>
                <img src='https://api.nongjangs.com/images/%EB%B8%94%EB%A3%A8%EB%B2%A0%EB%A6%AC/2.png' />
                <br/>
                <img src='https://api.nongjangs.com/images/%EB%B8%94%EB%A3%A8%EB%B2%A0%EB%A6%AC/3.png' />
                <br/>
                <img src='https://api.nongjangs.com/images/%EB%B8%94%EB%A3%A8%EB%B2%A0%EB%A6%AC/4.png' />
                <br/>
                <img src='https://api.nongjangs.com/images/%EB%B8%94%EB%A3%A8%EB%B2%A0%EB%A6%AC/5.png' />
                <br/>
                ",
            ],
            [
                'imgs' => [
                    '/images/about_slide_3_1.png',
                    '/images/about_slide_3_2.png',
                    '/images/about_slide_3_3.png',
                ],
                'requiredOptions' => [
                    [
                        'title' => '무농약 생 표고버섯 500g',
                        'type' => TypeOption::REQUIRED,
                        'price' => 0,
                        'count' => 10000
                    ],
                    [
                        'title' => '무농약 생 표고버섯 1kg',
                        'type' => TypeOption::REQUIRED,
                        'price' => 8900,
                        'count' => 10000
                    ],
                ],
                'tags' => [
                    [
                        'type' => TypeTag::PRODUCT,
                        'title' => 'NEW',
                    ],
                    [
                        'type' => TypeTag::PRODUCT,
                        'title' => '특가'
                    ],
                    [
                        'type' => TypeTag::PRODUCT,
                        'title' => '무농약'
                    ]
                ],

                'state' => StateProduct::ONGOING,
                'category_id' => Category::inRandomOrder()->first()->id,
                'farm_id' => $farm->id,
                'city_id' => $farm->county->city_id,
                'county_id' => $farm->county->id,
                'title' => '무농약 생 표고버섯 500g 1kg',
                'price' => 10000,
                'price_origin' => 20000,
                'need_tax' => 0,
                'can_use_coupon' => 1,
                'can_use_point' => 1,
                'type_delivery' => TypeDelivery::FREE,
                'type_delivery_price' => TypeDeliveryPrice::STATIC,
                'price_delivery' => 3000,
                'prices_delivery' => "[]",
                'min_price_for_free_delivery_price' => 50000,
                'can_delivery_far_place' => 1,
                // 'delivery_price_far_place' => 3000,
                'delivery_price_refund' => 3000,
                'delivery_address_refund' => '서울특별시 강남구 143 43',
                'description' => "
                <img src='https://api.nongjangs.com/images/%ED%91%9C%EA%B3%A0%EB%B2%84%EC%84%AF/1.png' />
                <br/>
                <img src='https://api.nongjangs.com/images/%ED%91%9C%EA%B3%A0%EB%B2%84%EC%84%AF/2.png' />
                <br/>
                <img src='https://api.nongjangs.com/images/%ED%91%9C%EA%B3%A0%EB%B2%84%EC%84%AF/3.png' />
                <br/>
                <img src='https://api.nongjangs.com/images/%ED%91%9C%EA%B3%A0%EB%B2%84%EC%84%AF/4.png' />
                <br/>
                <img src='https://api.nongjangs.com/images/%ED%91%9C%EA%B3%A0%EB%B2%84%EC%84%AF/5.png' />
                <br/>

                ",
            ],
        ];

        foreach($items as $item){
            $product = Product::factory()->create(\Arr::except($item, ['imgs', 'requiredOptions', 'tags']));

            foreach($item['requiredOptions'] as $option){
                $product->options()->create($option);
            }

            foreach($item['tags'] as $tag){
                $prevTag = Tag::where('type', TypeTag::PRODUCT)->where('title', $tag['title'])->first();

                if(!$prevTag)
                    $prevTag = Tag::factory()->create($tag);

                $product->tags()->attach($prevTag->id);
            }

            foreach($item['imgs'] as $img){
                if(config("app.env") != 'local'){
                    $product->addMedia(public_path($img))->preservingOriginal()->toMediaCollection("imgs", "s3");
                }
            }
        }

        /*Product::factory()->count(50)->create();*/
        $products = Product::factory()->count(30)->create();

        foreach($products as $product){
            $img = $this->imgs[rand(0, count($this->imgs) - 1)];

            foreach($items[0]['requiredOptions'] as $option){
                $product->options()->create($option);
            }

            foreach($items[0]['tags'] as $tag){
                $prevTag = Tag::where('type', TypeTag::PRODUCT)->where('title', $tag['title'])->first();

                if(!$prevTag)
                    $prevTag = Tag::factory()->create($tag);

                $product->tags()->attach($prevTag->id);
            }

            if(config("app.env") != 'local'){
                $product->addMedia(public_path($img))->preservingOriginal()->toMediaCollection("imgs", "s3");
            }
        }

    }

    public function createCategories()
    {
        $items = [
            [
                'title' => '과일류',
                'type' => TypeCategory::PRODUCT,
                'categories' => [
                    [
                        'title' => '복숭아'
                    ],
                    [
                        'title' => '사과'
                    ],
                    [
                        'title' => '귤'
                    ],
                    [
                        'title' => '포도'
                    ],
                ]
            ],
            [
                'title' => '채소류',
                'type' => TypeCategory::PRODUCT,
                'categories' => [
                    [
                        'title' => '샐러리'
                    ],
                    [
                        'title' => '청경채'
                    ],
                    [
                        'title' => '양파'
                    ],
                    [
                        'title' => '당근'
                    ],
                ]
            ],
            [
                'title' => '과일류',
                'type' => TypeCategory::PACKAGE,
                'categories' => [
                    [
                        'title' => '복숭아'
                    ],
                    [
                        'title' => '사과'
                    ],
                    [
                        'title' => '귤'
                    ],
                    [
                        'title' => '포도'
                    ],
                ]
            ],
            [
                'title' => '채소류',
                'type' => TypeCategory::PACKAGE,
                'categories' => [
                    [
                        'title' => '샐러리'
                    ],
                    [
                        'title' => '청경채'
                    ],
                    [
                        'title' => '양파'
                    ],
                    [
                        'title' => '당근'
                    ],
                ]
            ],
        ];

        foreach($items as $item){
            $category = Category::create(\Arr::except($item, ['categories']));

            foreach($item['categories'] as $subCategory){
                $subCategory = Category::create(array_merge($subCategory, ['category_id' => $category->id]));

                Material::factory()->create([
                    'category_id' => $subCategory->id,
                    'title' => '천도복숭아'
                ]);

                Material::factory()->create([
                    'category_id' => $subCategory->id,
                    'title' => '스테비아 토마토'
                ]);

                Material::factory()->create([
                    'category_id' => $subCategory->id,
                    'title' => '애플망고'
                ]);
            }
        }
    }

    public function createGrades()
    {
        $items = [
            [
                'title' => '씨앗 꾸러기',
                'ratio_refund' => 0.1,
                'min_price' => 30000,
                'min_count_package' => 1,
                'level' => 1,
                'img' => '/images/lv-1.png',
                'price_coupon' => 1000.
            ],
            [
                'title' => '새싹 꾸러기',
                'ratio_refund' => 0.2,
                'min_price' => 80000,
                'min_count_package' => 2,
                'level' => 2,
                'img' => '/images/lv-2.png',
                'price_coupon' => 2000.
            ],
            [
                'title' => '줄기 꾸러기',
                'ratio_refund' => 0.3,
                'min_price' => 150000,
                'min_count_package' => 5,
                'level' => 3,
                'img' => '/images/lv-3.png',
                'price_coupon' => 2500.
            ],
            [
                'title' => '잎새 꾸러기',
                'ratio_refund' => 1,
                'min_price' => 300000,
                'min_count_package' => 9,
                'level' => 4,
                'img' => '/images/lv-4.png',
                'price_coupon' => 3000.
            ],
            [
                'title' => '꽃잎 꾸러기',
                'ratio_refund' => 1.5,
                'min_price' => 500000,
                'min_count_package' => 14,
                'level' => 5,
                'img' => '/images/lv-5.png',
                'price_coupon' => 4000.
            ],
            [
                'title' => '열매 꾸러기',
                'ratio_refund' => 2,
                'min_price' => 700000,
                'min_count_package' => 26,
                'level' => 6,
                'img' => '/images/lv-6.png',
                'price_coupon' => 5000.
            ],
            [
                'title' => '풍요 꾸러기',
                'ratio_refund' => 2.5,
                'min_price' => 1000000,
                'min_count_package' => 38,
                'level' => 7,
                'img' => '/images/lv-7.png',
                'price_coupon' => 10000.
            ],
            [
                'title' => '영광 꾸러기',
                'ratio_refund' => 3,
                'min_price' => 1500000,
                'min_count_package' => 48,
                'level' => 8,
                'img' => '/images/lv-8.png',
                'price_coupon' => 15000.
            ],
        ];

        foreach($items as $item){
            $grade = Grade::create(\Arr::except($item, ['img', 'price_coupon']));

            if(config("app.env") != 'local')
                $grade->addMedia(public_path($item['img']))->preservingOriginal()->toMediaCollection("img", "s3");

            CouponGroup::create([
                'title' => $item['title']." 쿠폰",
                'moment' => MomentCouponGroup::GRADE,
                'grade_id' => $grade->id,
                'type' => TypeCouponGroup::ALL,
                'target' => TargetCouponGroup::ALL,
                'type_discount' => TypeDiscount::NUMBER,
                'value' => $item['price_coupon'],
                'max_price_discount' => $item['price_coupon'],
                'min_price_order' => $item['price_coupon'] + 1000,
                'type_expire' => TypeExpire::FROM_DOWNLOAD,
                'days' => 60,
            ]);
        }

    }

    public function createFarmStories()
    {
        $farmStories = FarmStory::factory()->count(20)->create();

        foreach($farmStories as $farmStory){
            $tags = Tag::inRandomOrder()->take(6)->get();

            if(count($tags) == 0)
                $tags = Tag::factory()->count(6)->create();

            $farmStory->tags()->attach($tags->pluck("id"));

            if(config("app.env") != 'local'){
                $farmStory->addMedia(public_path($this->farmImgs[rand(0, count($this->farmImgs) - 1)]))->preservingOriginal()->toMediaCollection("img", "s3");
            }
        }
    }
    public function createFarms()
    {
        Farm::factory()->count(15)->create();
    }
    public function createTags()
    {
        Tag::factory()->count(15)->create([
            'type' => TypeTag::PRODUCT
        ]);
        Tag::factory()->count(15)->create([
            'type' => TypeTag::FARM_STORY
        ]);
        Tag::factory()->count(15)->create([
            'type' => TypeTag::PACKAGE
        ]);
        Tag::factory()->count(15)->create([
            'type' => TypeTag::RECIPE
        ]);
        Tag::factory()->count(15)->create([
            'type' => TypeTag::VEGETABLE_STORY
        ]);
    }

    public function createCounts()
    {
        Count::create([
            'sum_weight' => 2226227,
            'sum_store' => 10023,
        ]);
    }

    public function createPops()
    {
        $items = [
            [
                'title' => '팝업예시',
                'url' => '/stories',
                'img' => $this->bannerImages[0],
                'open' => 1,
                'started_at' => Carbon::now()->subDays(1),
                'finished_at' => Carbon::now()->addWeeks(3),
            ],
        ];

        foreach($items as $item){
            $createdItem = Pop::create(\Illuminate\Support\Arr::except($item, ['img']));

            if(config("app.env") != 'local'){
                $createdItem->addMedia(public_path($item['img']))->preservingOriginal()->toMediaCollection("img", "s3");
            }
        }
    }

    public function createBanners()
    {
        $items = [
            [
                'type' => TypeBanner::MAIN,
                'title' => '작은 농장에서 온 큰 행복,
당신의 일상에 녹아들다',
                'subtitle' => '',
                'url' => '/packages/intro',
                'pc' => $this->bannerImages[0],
                'mobile' => $this->bannerImages[0],
                'button' => '여정 시작하기',
                'color_text' => 'black',
                'color_button' => '#308929',
                'started_at' => Carbon::now()->subDays(1),
                'finished_at' => Carbon::now()->addDays(3),
            ],
            /*[
                'type' => TypeBanner::MAIN,
                'title' => '작은 농장에서 온 큰 행복,
당신의 일상에 녹아들다',
                'subtitle' => '',
                'url' => '/packages/intro',
                'pc' => $this->bannerImages[1],
                'mobile' => $this->bannerImages[1],
                'button' => '여정 시작하기',
                'color_text' => 'white',
                'color_button' => '#FF9224',
                'started_at' => Carbon::now()->subDays(1),
                'finished_at' => Carbon::now()->addDays(3),
            ],*/

            [
                'type' => TypeBanner::FARM_STORY,
                'title' => '작은 농장에서 온 큰 행복,
당신의 일상에 녹아들다',
                'subtitle' => '',
                'url' => '/packages/intro',
                'pc' => $this->bannerImages[0],
                'mobile' => $this->bannerImages[0],
                'button' => '여정 시작하기',
                'color_text' => 'black',
                'color_button' => '#308929',
                'started_at' => Carbon::now()->subDays(1),
                'finished_at' => Carbon::now()->addDays(3),
            ],
            [
                'type' => TypeBanner::FARM_STORY,
                'title' => '작은 농장에서 온 큰 행복,
당신의 일상에 녹아들다',
                'subtitle' => '',
                'url' => '/packages/intro',
                'pc' => $this->bannerImages[1],
                'mobile' => $this->bannerImages[1],
                'button' => '여정 시작하기',
                'color_text' => 'white',
                'color_button' => '#FF9224',
                'started_at' => Carbon::now()->subDays(1),
                'finished_at' => Carbon::now()->addDays(3),
            ],

            [
                'type' => TypeBanner::PACKAGE,
                'title' => '작은 농장에서 온 큰 행복,
당신의 일상에 녹아들다',
                'subtitle' => '',
                'url' => '/packages/select',
                'pc' => $this->bannerImages[1],
                'mobile' => $this->bannerImages[1],
                'button' => '여정 시작하기',
                'color_text' => 'white',
                'color_button' => '#FF9224',
                'started_at' => Carbon::now()->subDays(1),
                'finished_at' => Carbon::now()->addDays(3),
            ],
            /*[
                'type' => TypeBanner::PACKAGE,
                'title' => '작은 농장에서 온 큰 행복,
당신의 일상에 녹아들다',
                'subtitle' => '',
                'url' => '/packages/select',
                'pc' => $this->bannerImages[1],
                'mobile' => $this->bannerImages[1],
                'button' => '여정 시작하기',
                'color_text' => 'white',
                'color_button' => '#FF9224',
                'started_at' => Carbon::now()->subDays(1),
                'finished_at' => Carbon::now()->addDays(3),
            ],*/

            [
                'type' => TypeBanner::PRODUCT,
                'title' => '작은 농장에서 온 큰 행복,
당신의 일상에 녹아들다',
                'subtitle' => '',
                'url' => '/packages/intro',
                'pc' => $this->bannerImages[0],
                'mobile' => $this->bannerImages[0],
                'button' => '여정 시작하기',
                'color_text' => 'black',
                'color_button' => '#308929',
                'started_at' => Carbon::now()->subDays(1),
                'finished_at' => Carbon::now()->addDays(3),
            ],
            /*[
                'type' => TypeBanner::PRODUCT,
                'title' => '작은 농장에서 온 큰 행복,
당신의 일상에 녹아들다',
                'subtitle' => '',
                'url' => '/packages/intro',
                'pc' => $this->bannerImages[1],
                'mobile' => $this->bannerImages[1],
                'button' => '여정 시작하기',
                'color_text' => 'white',
                'color_button' => '#FF9224',
                'started_at' => Carbon::now()->subDays(1),
                'finished_at' => Carbon::now()->addDays(3),
            ],*/
        ];

        foreach($items as $item){
            $createdItem = Banner::create(\Illuminate\Support\Arr::except($item, ['pc', 'mobile']));

            if(config("app.env") != 'local'){
                $createdItem->addMedia(public_path($item['pc']))->preservingOriginal()->toMediaCollection("pc", "s3");
                $createdItem->addMedia(public_path($item['mobile']))->preservingOriginal()->toMediaCollection("mobile", "s3");
            }
        }
    }

    public function createLocations()
    {
        $regions = [
            '서울특별시' => [
                '종로구', '중구', '용산구', '성동구', '광진구',
                '동대문구', '중랑구', '성북구', '강북구', '도봉구',
                '노원구', '은평구', '서대문구', '마포구', '양천구',
                '강서구', '구로구', '금천구', '영등포구', '동작구',
                '관악구', '서초구', '강남구', '송파구', '강동구'
            ],
            '부산광역시' => [
                '중구', '서구', '동구', '영도구', '부산진구',
                '동래구', '남구', '북구', '해운대구', '사하구',
                '금정구', '강서구', '연제구', '수영구', '사상구',
                '기장군'
            ],
            '대구광역시' => [
                '중구', '동구', '서구', '남구', '북구',
                '수성구', '달서구', '달성군'
            ],
            '인천광역시' => [
                '중구', '동구', '미추홀구', '연수구', '남동구',
                '부평구', '계양구', '서구', '강화군', '옹진군'
            ],
            '광주광역시' => [
                '동구', '서구', '남구', '북구', '광산구'
            ],
            '대전광역시' => [
                '동구', '중구', '서구', '유성구', '대덕구'
            ],
            '울산광역시' => [
                '중구', '남구', '동구', '북구', '울주군'
            ],
            '세종특별자치시' => [
                '세종특별자치시' // 세종시는 하나의 특별자치시로 구성되어 있습니다.
            ],
            '경기도' => [
                '수원시 장안구', '수원시 권선구', '수원시 팔달구', '수원시 영통구',
                '성남시 수정구', '성남시 중원구', '성남시 분당구',
                '안양시 만안구', '안양시 동안구',
                '안산시 상록구', '안산시 단원구',
                '용인시 처인구', '용인시 기흥구', '용인시 수지구',
                '부천시', '광명시', '평택시', '동두천시', '안성시',
                '고양시 덕양구', '고양시 일산동구', '고양시 일산서구',
                '과천시', '구리시', '남양주시', '오산시', '시흥시',
                '군포시', '의왕시', '하남시', '이천시', '용인시',
                '안산시', '파주시', '의정부시', '양주시', '여주시',
                '화성시', '광주시', '양평군', '포천시', '연천군'
            ],
            '강원도' => [
                '춘천시', '원주시', '강릉시', '동해시', '태백시',
                '속초시', '삼척시', '홍천군', '횡성군', '영월군',
                '평창군', '정선군', '철원군', '화천군', '양구군',
                '인제군', '고성군', '양양군'
            ],
            '충청북도' => [
                '청주시 상당구', '청주시 서원구', '청주시 흥덕구', '청주시 청원구',
                '충주시', '제천시', '보은군', '옥천군', '영동군',
                '증평군', '진천군', '괴산군', '음성군', '단양군'
            ],
            '충청남도' => [
                '천안시 동남구', '천안시 서북구', '공주시', '보령시', '아산시',
                '서산시', '논산시', '계룡시', '당진시', '금산군',
                '부여군', '서천군', '청양군', '홍성군', '예산군',
                '태안군'
            ],
            '전라북도' => [
                '전주시 완산구', '전주시 덕진구', '군산시', '익산시', '정읍시',
                '남원시', '김제시', '완주군', '진안군', '무주군',
                '장수군', '임실군', '순창군', '고창군', '부안군'
            ],
            '전라남도' => [
                '목포시', '여수시', '순천시', '나주시', '광양시',
                '담양군', '곡성군', '구례군', '고흥군', '보성군',
                '화순군', '장흥군', '강진군', '해남군', '영암군',
                '무안군', '함평군', '영광군', '장성군', '완도군',
                '진도군', '신안군'
            ],
            '경상북도' => [
                '포항시 남구', '포항시 북구', '경주시', '김천시', '안동시',
                '구미시', '영주시', '영천시', '상주시', '문경시',
                '경산시', '군위군', '의성군', '청송군', '영양군',
                '영덕군', '청도군', '고령군', '성주군', '칠곡군',
                '예천군', '봉화군', '울진군', '울릉군'
            ],
            '경상남도' => [
                '창원시 의창구', '창원시 성산구', '창원시 마산합포구', '창원시 마산회원구',
                '창원시 진해구', '진주시', '통영시', '고성군', '사천시',
                '김해시', '밀양시', '거제시', '양산시', '함안군',
                '창녕군', '고성군', '남해군', '하동군', '산청군',
                '함양군', '거창군', '합천군'
            ],
            '제주특별자치도' => [
                '제주시', '서귀포시'
            ]
        ];

        foreach ($regions as $cityName => $counties) {
            $city = City::create(['title' => $cityName]);

            foreach ($counties as $countyName) {
                County::create([
                    'city_id' => $city->id,
                    'title' => $countyName,
                ]);
            }
        }
    }

    public function createCoupons()
    {
        $couponGroups = CouponGroup::factory()->count(2)->create([
            'duration' => 360
        ]);

        $users = User::get();

        foreach($couponGroups as $couponGroup){
            foreach($users as $user){
                Coupon::factory()->count(3)->create([
                    'coupon_group_id' => $couponGroup->id,
                    'user_id' => $user->id
                ]);
            }
        }
    }

    public function createReviews()
    {
        $user = User::inRandomOrder()->first();

        // 상품별 리뷰
        $products = Product::inRandomOrder()->take(12)->get();

        foreach($products as $product){
            Review::factory()->count(3)->create(['product_id' => $product->id, 'package_id' => null]);
            Review::factory()->count(2)->create(['product_id' => null, 'user_id' => $user->id]);
            $photoReviews = Review::factory()->count(5)->create(['product_id' => $product->id, 'photo' => 1, 'user_id' => $this->user->id]);

            foreach($photoReviews as $photoReview){
                if (config("app.env") != 'local') {
                    $photoReview->addMedia(public_path($this->imgs[rand(0, count($this->imgs) - 1)]))->preservingOriginal()->toMediaCollection("imgs", "s3");
                    $photoReview->addMedia(public_path($this->imgs[rand(0, count($this->imgs) - 1)]))->preservingOriginal()->toMediaCollection("imgs", "s3");
                }
            }
        }

        // $reviews = Review::factory()->count(10)->create(['product_id' => null]);

        // 리뷰작성가능 상품조합 목록
        /*$presets = Preset::factory()->count(5)->create([
            'user_id' => $user->id,
        ]);

        foreach($presets as $preset){
            $product = Product::inRandomOrder()->first();

            PresetProduct::factory()->create([
                'product_id' => $product->id,
                'preset_id' => $preset->id,
                'state' => StatePresetProduct::CONFIRMED,
            ]);
        }*/
    }

    public function createPointHistories()
    {
        PointHistory::factory()->count(2)->create([
            'type' => TypePointHistory::USER_RECOMMENDED,
            'point_historiable_type' => User::class,
            'point_historiable_id' => User::inRandomOrder()->where('id', '!=', $this->user->id)->first()->id,
            'increase' => 1,
            'user_id' => $this->user->id,
        ]);

        PointHistory::factory()->count(2)->create([
            'type' => TypePointHistory::USER_RECOMMEND,
            'point_historiable_type' => User::class,
            'point_historiable_id' => User::inRandomOrder()->where('id', '!=', $this->user->id)->first()->id,
            'increase' => 1,
            'user_id' => $this->user->id,
        ]);

        PointHistory::factory()->count(30)->create([
            'type' => TypePointHistory::ORDER_CREATED,
            'point_historiable_type' => Order::class,
            'point_historiable_id' => Order::factory()->create()->id,
            'increase' => 0,
            'user_id' => $this->user->id,
        ]);

        PointHistory::factory()->count(1)->create([
            'type' => TypePointHistory::PRESET_PRODUCT_CANCLE,
            'point_historiable_type' => PresetProduct::class,
            'point_historiable_id' => PresetProduct::inRandomOrder()->first()->id,
            'increase' => 0,
            'user_id' => $this->user->id,
        ]);

        PointHistory::factory()->count(1)->create([
            'type' => TypePointHistory::PHOTO_REVIEW_CREATED,
            'point_historiable_type' => PresetProduct::class,
            'point_historiable_id' => PresetProduct::inRandomOrder()->first()->id,
            'increase' => 0,
            'user_id' => $this->user->id,
        ]);

        PointHistory::factory()->count(1)->create([
            'type' => TypePointHistory::TEXT_REVIEW_CREATED,
            'point_historiable_type' => PresetProduct::class,
            'point_historiable_id' => PresetProduct::inRandomOrder()->first()->id,
            'increase' => 0,
            'user_id' => $this->user->id,
        ]);

        PointHistory::factory()->count(1)->create([
            'type' => TypePointHistory::BEST_REVIEW_UPDATED,
            'point_historiable_type' => PresetProduct::class,
            'point_historiable_id' => PresetProduct::inRandomOrder()->first()->id,
            'increase' => 0,
            'user_id' => $this->user->id,
        ]);

        PointHistory::factory()->count(1)->create([
            'type' => TypePointHistory::BEST_REVIEW_UPDATED,
            'point_historiable_type' => PresetProduct::class,
            'point_historiable_id' => PresetProduct::inRandomOrder()->first()->id,
            'increase' => 0,
            'user_id' => $this->user->id,
        ]);

        PointHistory::factory()->count(1)->create([
            'type' => TypePointHistory::VEGETABLE_STORY_CREATED,
            'point_historiable_type' => VegetableStory::class,
            'point_historiable_id' => VegetableStory::inRandomOrder()->first()->id,
            'increase' => 1,
            'user_id' => $this->user->id,
        ]);

        PointHistory::factory()->count(1)->create([
            'type' => TypePointHistory::PRESET_PRODUCT_CONFIRM,
            'point_historiable_type' => PresetProduct::class,
            'point_historiable_id' => PresetProduct::inRandomOrder()->first()->id,
            'increase' => 1,
            'user_id' => $this->user->id,
        ]);

        PointHistory::factory()->count(1)->create([
            'type' => TypePointHistory::EXPIRED,
            'increase' => 1,
            'user_id' => $this->user->id,
        ]);
    }

    public function createCouponHistories()
    {
        $users = User::get();

        foreach($users as $user){
            CouponHistory::factory()->count(10)->create([
                'user_id' => $user->id,
                'increase' => 1
            ]);

            CouponHistory::factory()->count(10)->create([
                'user_id' => $user->id,
                'increase' => 0
            ]);
        }
    }

    public function createUsers()
    {
        $user = User::factory()->create([
            "email" => "test@naver.com",
            "password" => Hash::make("test@naver.com"),
            "contact" => "01030217486",
            "name" => "일반 이름",
        ]);

        $this->user = $user;

        User::factory()->create([
            "email" => "company@naver.com",
            "password" => Hash::make("company@naver.com"),
            "contact" => "01030217486",
            "name" => "사업자 이름",
        ]);

        User::factory()->create([
            "email" => "admin@naver.com",
            "password" => Hash::make("admin@naver.com"),
            "contact" => "01030217486",
            "name" => "관리자 이름",
            "admin" => 1,
        ]);
    }

    public function createDeliveries()
    {
        $users = User::get();

        foreach($users as $user){
            for($i=0; $i<3; $i++){
                Delivery::factory()->create([
                    'user_id' => $user->id
                ]);
            }
        }
    }

    public function createPayMethods()
    {
        $payMethods = [
            [
                "pg" => "danal_tpay",
                "method" => "EASY",
                "name" => "간편결제",
                "commission" => "7",
                "external" => 0,
                "channel_key" => "channel-key-06b7aa84-f6d8-4266-99eb-d52b9d51ae15",
            ],
            [
                "pg" => "danal_tpay",
                "method" => "CARD",
                "name" => "신용카드",
                "commission" => "7",
                "channel_key" => "channel-key-06b7aa84-f6d8-4266-99eb-d52b9d51ae15",
            ],
            [
                "pg" => "danal_tpay",
                "method" => "MOBILE",
                "name" => "휴대폰",
                "commission" => "7",
                "channel_key" => "channel-key-06b7aa84-f6d8-4266-99eb-d52b9d51ae15",
            ],
            [
                "pg" => "danal_tpay",
                "method" => "TRANSFER",
                "name" => "계좌이체",
                "commission" => "7",
                "used" => 0,
                "channel_key" => "channel-key-06b7aa84-f6d8-4266-99eb-d52b9d51ae15",
            ],
        ];

        foreach($payMethods as $payMethod){
            PayMethod::create($payMethod);
        }
    }

    public function createDeliveryCompanies()
    {
        // Create delivery companies based on the enum values
        \App\Models\DeliveryCompany::create([
            'title' => '대한통운',
            'code' => 'kr.cjlogistics',
        ]);

        \App\Models\DeliveryCompany::create([
            'title' => '롯데택배',
            'code' => 'kr.lotte',
        ]);

        \App\Models\DeliveryCompany::create([
            'title' => '우체국',
            'code' => 'kr.epost',
        ]);

        \App\Models\DeliveryCompany::create([
            'title' => '한진택배',
            'code' => 'kr.hanjin',
        ]);

        \App\Models\DeliveryCompany::create([
            'title' => '로젠택배',
            'code' => 'kr.logen',
        ]);

        \App\Models\DeliveryCompany::create([
            'title' => '경동택배',
            'code' => 'kr.kdexp',
        ]);
    }

    /**
     * test@naver.com 계정에 패키지 구매건 하나를 생성한다
     * 미루기, 당기기 테스트를 위한 데이터
     */
    public function createTestPackagePurchase()
    {
        // 성공한 주문 생성
        $order = Order::create([
            'user_id' => $this->user->id,
            'state' => StateOrder::SUCCESS,
            'price' => 0, // 초기값
            'success_at' => now(), // 결제 성공 시간
        ]);

        // 사용자의 프리셋 찾기 또는 생성
        $preset = Preset::where('user_id', $this->user->id)->first();

        if (!$preset) {
            $preset = Preset::create([
                'user_id' => $this->user->id,
                'title' => '기본 프리셋',
                'order_id' => $order->id, // 주문 연결
            ]);
        } else {
            // 기존 프리셋에 주문 연결
            $preset->update(['order_id' => $order->id]);
        }

        // 사용자의 PackageSetting 찾기 또는 생성
        $packageSetting = PackageSetting::where('user_id', $this->user->id)->first();

        if (!$packageSetting) {
            $packageSetting = PackageSetting::create([
                'user_id' => $this->user->id,
                'type_package' => TypePackage::BUNGLE,
                'term_week' => 3,
                'active' => 1,
                'name' => '테스트 꾸러미',
            ]);
        } else {
            // 기존 PackageSetting이 있으면 active를 1로 설정
            $packageSetting->update(['active' => 1]);
        }

        // 현재 진행 중인 패키지 찾기
        $package = Package::getOngoing();

        // 진행 중인 패키지가 없으면 첫 번째 패키지 사용
        if (!$package) {
            $package = Package::where('count', 1)->first();

            // 패키지가 "ongoing"으로 인식되도록 날짜 설정
            $package->update([
                'start_pack_wait_at' => Carbon::now()->subDay(),
                'will_delivery_at' => Carbon::now()->addDays(7),
            ]);
        }

        // 기존 패키지 구매건 중 현재 패키지와 동일한 것만 삭제
        // 다른 패키지 구매건은 유지
        PresetProduct::where('preset_id', $preset->id)
            ->where('package_id', $package->id)
            ->delete();

        // 새 패키지 구매건 생성
        $presetProduct = PresetProduct::create([
            'preset_id' => $preset->id,
            'package_id' => $package->id,
            'state' => StatePresetProduct::WAIT,
            'package_name' => $packageSetting->name,
            'package_count' => $package->count,
            'package_price' => $packageSetting->type_package == TypePackage::BUNGLE ? $package->price_bungle : $package->price_single,
            'package_will_delivery_at' => $package->will_delivery_at,
            'package_active' => 1, // 활성 상태로 설정
            'package_type' => $packageSetting->type_package,
        ]);

        // 주문 가격 업데이트
        $order->update([
            'price' => $packageSetting->type_package == TypePackage::BUNGLE ? $package->price_bungle : $package->price_single,
        ]);

        // 패키지 재료 추가
        $packageMaterials = $package->packageMaterials;

        foreach ($packageMaterials as $packageMaterial) {
            $presetProduct->materials()->attach($packageMaterial->material_id, [
                'price' => $packageMaterial->price,
                'price_origin' => $packageMaterial->price_origin,
                'unit' => $packageMaterial->unit,
                'value' => $packageMaterial->value,
                'count' => 1, // 기본 수량
                'type' => $packageMaterial->type,
            ]);
        }
    }

}
