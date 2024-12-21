<?php

namespace Database\Seeders;

use App\Enums\DeliveryCompany;
use App\Enums\StateMachine;
use App\Enums\StateOrder;
use App\Enums\StatePresetProduct;
use App\Enums\StateUser;
use App\Enums\TypeBanner;
use App\Enums\TypeProduct;
use App\Enums\TypeUser;
use App\Models\AdditionalProduct;
use App\Models\Alarm;
use App\Models\Answer;
use App\Models\Banner;
use App\Models\BusinessCategory;
use App\Models\BusinessQuestion;
use App\Models\Category;
use App\Models\Certification;
use App\Models\City;
use App\Models\Color;
use App\Models\Comment;
use App\Models\Company;
use App\Models\Base;
use App\Models\County;
use App\Models\Coupon;
use App\Models\CouponGroup;
use App\Models\CouponHistory;
use App\Models\Delivery;
use App\Models\Event;
use App\Models\Faq;
use App\Models\FaqCategory;
use App\Models\Feedback;
use App\Models\Generator;
use App\Models\Intro;
use App\Models\Notice;
use App\Models\Order;
use App\Models\PayMethod;
use App\Models\Phrase;
use App\Models\PhraseProductCategory;
use App\Models\PhraseReceiverCategory;
use App\Models\PointHistory;
use App\Models\Preset;
use App\Models\PresetProduct;
use App\Models\Product;
use App\Models\Prototype;
use App\Models\Qna;
use App\Models\QnaCategory;
use App\Models\RecommendCategory;
use App\Models\Review;
use App\Models\Size;
use App\Models\User;
use Carbon\Carbon;
use Faker\Provider\Payment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class InitSeeder extends Seeder
{
    protected $imgs = [
        "/images/biz_program_01.png",
        "/images/biz_program_02.png",
        "/images/biz_program_03.png",
        "/images/middleBanner.png",
        "/images/middleBanner1.png",
        "/images/middleBanner2.png",
    ];

    protected $user;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("SET foreign_key_checks=0");

        User::truncate();
        Banner::truncate();
        Category::truncate();
        PayMethod::truncate();
        Product::truncate();
        FaqCategory::truncate();
        Faq::truncate();
        Qna::truncate();
        Notice::truncate();
        PayMethod::truncate();
        CouponGroup::truncate();
        Coupon::truncate();
        PointHistory::truncate();
        CouponHistory::truncate();
        RecommendCategory::truncate();
        FaqCategory::truncate();
        Faq::truncate();
        Event::truncate();
        Intro::truncate();
        PhraseProductCategory::truncate();
        PhraseReceiverCategory::truncate();
        Phrase::truncate();
        Preset::truncate();
        Review::truncate();
        Order::truncate();
        Prototype::truncate();
        PresetProduct::truncate();

        DB::table("media")->truncate();
        DB::statement("SET foreign_key_checks=1");

        $this->createUsers();

        $this->user = User::where('ids', 'test')->first();

        $this->createDeliveries();
        $this->createBanners();
        $this->createCategories();
        $this->createPayMethods();
        $this->createCouponGroups();
        $this->createProducts();
        $this->createPointHistories();
        $this->createCouponHistories();
        $this->createRecommendCategories();
        $this->createFaqCategories();
        $this->createEvents();
        $this->createIntros();
        $this->createPhraseProductCategories();
        $this->createPhraseReceiverCategories();
        // $this->createPhrases();
        // $this->createReviews();
        // $this->createQnaCategories();
        // $this->createCoupons();
        // $this->createPointHistories();
        // $this->createOrders();
        // $this->createProtoTypes();
    }

    public function createPrototypes()
    {
        $presetProducts = PresetProduct::where('state', StatePresetProduct::FINISH_PROTOTYPE)->cursor();

        foreach($presetProducts as $presetProduct){
            for($i =0; $i < rand(1,2); $i++){
                $prototype = Prototype::factory()->create([
                    'title' => '프로토타입',
                    'preset_product_id' => $presetProduct->id,
                ]);

                $prototype->addMedia(public_path($this->imgs[rand(0, count($this->imgs) - 1)]))->preservingOriginal()->toMediaCollection("img", "s3");

                Comment::factory()->count(2)->create([
                    'prototype_id' => $prototype->id,
                    'preset_product_id' => $presetProduct->id
                ]);

                Feedback::factory()->create([
                    'preset_product_id' => $presetProduct->id,
                    'admin' => 1,
                ]);

                Feedback::factory()->create([
                    'preset_product_id' => $presetProduct->id,
                    'admin' => 0,
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

    public function createQnaCategories()
    {
        $user = User::where('ids', 'test')->first();

        $items = [
            [
                'title' => '상장 제작',
            ],
            [
                'title' => '결제 및 배송',
            ],
            [
                'title' => 'A/S 및 교환',
            ],
            [
                'title' => '적립금',
            ],
            [
                'title' => '세금계산서',
            ],
            [
                'title' => '기타',
            ],
        ];

        foreach($items as $item){
            $qnaCategory = QnaCategory::create($item);

            Qna::factory()->count(10)->create(['user_id' => $user->id, 'qna_category_id' => $qnaCategory->id, 'answer' => '답변예시', 'answered_at' => Carbon::now()]);
            Qna::factory()->count(10)->create(['user_id' => $user->id, 'qna_category_id' => $qnaCategory->id]);
        }
    }
    public function createReviews()
    {
        $user = User::where('ids', 'test')->first();

        // 상품별 리뷰
        $products = Product::get();

        foreach($products as $product){
            Review::factory()->count(10)->create(['product_id' => $product->id]);
            Review::factory()->count(10)->create(['product_id' => $product->id, 'user_id' => $user->id]);
            $photoReviews = Review::factory()->count(5)->create(['product_id' => $product->id, 'photo' => 1]);

            foreach($photoReviews as $photoReview){
                if (config("app.env") != 'local') {
                    $photoReview->addMedia(public_path($this->imgs[rand(0, count($this->imgs) - 1)]))->preservingOriginal()->toMediaCollection("imgs", "s3");
                    $photoReview->addMedia(public_path($this->imgs[rand(0, count($this->imgs) - 1)]))->preservingOriginal()->toMediaCollection("imgs", "s3");
                }
            }
        }

        // 리뷰작성가능 상품조합 목록
        $presets = Preset::factory()->count(5)->create([
            'user_id' => $user->id,
        ]);

        foreach($presets as $preset){
            $product = Product::inRandomOrder()->first();
            $size = $product->sizes()->inRandomOrder()->first();
            $color = $product->colors()->inRandomOrder()->first();

            PresetProduct::factory()->create([
                'product_id' => $product->id,
                'preset_id' => $preset->id,
                'color_id' => $color->id,
                'size_id' => $size->id,
                'state' => StatePresetProduct::CONFIRMED,
            ]);
        }
    }

    public function createPhrases()
    {
        $phraseProductCategories = PhraseProductCategory::get();
        $phraseReceiverCategories = PhraseReceiverCategory::get();

        foreach($phraseProductCategories as $phraseProductCategory){
            foreach($phraseReceiverCategories as $phraseReceiverCategory){
                Phrase::factory()->create([
                    'phrase_product_category_id' => $phraseProductCategory->id,
                    'phrase_receiver_category_id' => $phraseReceiverCategory->id,
                    'description' => "[{$phraseProductCategory->title}] [{$phraseReceiverCategory->title}] 문구예시"
                ]);
            }
        }
    }
    public function createPhraseProductCategories()
    {
        $items = [
            [
                'title' => '감사패',
            ],
            [
                'title' => '공로패',
            ],
            [
                'title' => '재직기념패',
            ],
            [
                'title' => '학위패',
            ],
            [
                'title' => '송공패',
            ],
            [
                'title' => '임영패',
            ],
            [
                'title' => '교회상패',
            ],
            [
                'title' => '이글패',
            ],
            [
                'title' => '싱글패',
            ],
            [
                'title' => '홀인원패',
            ],
            [
                'title' => '골프패',
            ],
            [
                'title' => '시상식/우승',
            ],
        ];

        foreach($items as $item){
            PhraseProductCategory::factory()->create($item);
        }
    }

    public function createPhraseReceiverCategories()
    {
        $items = [
            [
                'title' => '회사',
            ],
            [
                'title' => '학교',
            ],
            [
                'title' => '단체',
            ],
            [
                'title' => '관공서',
            ],
            [
                'title' => '가족',
            ],
            [
                'title' => '기타',
            ],
        ];

        foreach($items as $item){
            PhraseReceiverCategory::factory()->create($item);
        }
    }



    public function createIntros()
    {
        Intro::factory()->create(['use'=>1]);
    }
    public function createEvents()
    {
        Event::factory()->count(2)->create([
            'started_at' => Carbon::now(),
            'finished_at' => Carbon::now()->addDays(60),
        ]);

        Event::factory()->count(2)->create([
            'started_at' => Carbon::now(),
            'finished_at' => Carbon::now()->addDays(60),
        ]);

        Event::factory()->count(3)->create([
            'started_at' => Carbon::now()->subDays(10),
            'finished_at' => Carbon::now()->subDays(1)
        ]);

        $events = Event::get();

        foreach($events as $event){
            $event->addMedia(public_path($this->imgs[rand(0, count($this->imgs) - 1)]))->preservingOriginal()->toMediaCollection("img", "s3");
        }
    }

    public function createFaqCategories()
    {
        $items = [
            [
                'title' => '제품 및 시안제작'
            ],
            [
                'title' => '제품 구매 및 취소'
            ],
            [
                'title' => '결제 및 배송방법'
            ],
            [
                'title' => '교환/반품/환불'
            ],
            [
                'title' => '마일리지'
            ],
        ];

        foreach($items as $item){
            $faqCategory = FaqCategory::factory()->create($item);

            for($i=1; $i<=20; $i++){
                Faq::factory()->create([
                    'faq_category_id' => $faqCategory->id,
                    'title' => "[{$faqCategory->title}] 예시".$i
                ]);
            }
        }
    }

    public function createRecommendCategories()
    {
        $items = [
            [
                'title' => '변함없는 감사한 마음을
                변함없이 오래도록',
                'subtitle' => 'MD가 추천하는 베스트 테스트 모음'
            ],
            [
                'title' => '노력의 결실을 오래 기억하도록
당신의 노력을 응원합니다',
                'subtitle' => '테스트 부제 모음',
            ],
            [
                'title' => '당신의 새로운 시작을
응원합니다',
                'subtitle' => '모두가 환영하는 테스트 부제 모음',
            ],
        ];

        foreach($items as $item){
            $createdItem = RecommendCategory::factory()->create($item);

            $createdItem->addMedia(public_path($this->imgs[rand(0, count($this->imgs) - 1)]))->preservingOriginal()->toMediaCollection("img", "s3");
        }
    }

    public function createOrders()
    {
        $waitOrders = Order::factory()->count(2)->create([
            'user_id' => $this->user->id,
            'state' => StateOrder::WAIT,
        ]);

        $successOrders = Order::factory()->count(5)->create([
            'user_id' => $this->user->id,
            'state' => StateOrder::SUCCESS,
        ]);

        $cancelOrders = Order::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'state' => StateOrder::CANCEL,
        ]);

        foreach($waitOrders as $order){
            $this->attachPresets($order);
        }
        foreach($successOrders as $order){
            $this->attachPresets($order);
        }
        foreach($cancelOrders as $order){
            $this->attachPresets($order);
        }
    }

    public function attachPresets(Order $order)
    {
        $products = Product::has('media')->where('product_id', null)->where(['open' => 1])->inRandomOrder()->take(2)->get();

        foreach($products as $product){
            $size = Size::factory()->create([
                'product_id' => $product->id,
            ]);
            $color = Color::factory()->create([
                'product_id' => $product->id,
            ]);
            $additionalProduct = Product::factory()->create([
                'product_id' => $product->id
            ]);
        }

        $preset = Preset::factory()->create([
            'user_id' => $this->user->id,
            'order_id' => $order->id,
            'count' => 1,
        ]);

        foreach($products as $product){
            $size = $product->sizes()->inRandomOrder()->first();
            $color = $product->colors()->inRandomOrder()->first();
            $additionalProduct = $product->products()->inRandomOrder()->first();

            PresetProduct::factory()->create([
                'preset_id' => $preset->id,
                'product_id' => $product->id,
                'size_id' => $size->id,
                'color_id' => $color->id,
                'state' => StatePresetProduct::READY
            ]);

            PresetProduct::factory()->create([
                'preset_id' => $preset->id,
                'product_id' => $product->id,
                'size_id' => $size->id,
                'color_id' => $color->id,
                'state' => StatePresetProduct::ONGOING_PROTOTYPE
            ]);

            PresetProduct::factory()->create([
                'preset_id' => $preset->id,
                'product_id' => $product->id,
                'size_id' => $size->id,
                'color_id' => $color->id,
                'state' => StatePresetProduct::FINISH_PROTOTYPE
            ]);

            PresetProduct::factory()->create([
                'preset_id' => $preset->id,
                'product_id' => $product->id,
                'size_id' => $size->id,
                'color_id' => $color->id,
                'state' => StatePresetProduct::ONGOING_DELIVERY,
                'delivery_number' => '584641143570',
                'delivery_company' => DeliveryCompany::CJ,
            ]);

            PresetProduct::factory()->create([
                'preset_id' => $preset->id,
                'product_id' => $product->id,
                'size_id' => $size->id,
                'color_id' => $color->id,
                'state' => StatePresetProduct::DELIVERED,
                'delivery_number' => '584641143570',
                'delivery_company' => DeliveryCompany::CJ,
            ]);

            PresetProduct::factory()->create([
                'preset_id' => $preset->id,
                'product_id' => $product->id,
                'size_id' => $size->id,
                'color_id' => $color->id,
                'state' => StatePresetProduct::CONFIRMED,
                'delivery_number' => '584641143570',
                'delivery_company' => DeliveryCompany::CJ,
            ]);

            PresetProduct::factory()->create([
                'additional' => 1,
                'preset_id' => $preset->id,
                'product_id' => $additionalProduct->id,
            ]);
        }

    }

    public function createPointHistories()
    {
        $users = User::get();

        foreach($users as $user){
            PointHistory::factory()->count(10)->create([
                'user_id' => $user->id,
                'increase' => 1
            ]);

            PointHistory::factory()->count(10)->create([
                'user_id' => $user->id,
                'increase' => 0
            ]);
        }
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

    public function createProducts()
    {
        $categories = Category::get();

        foreach($categories as $category){
            for($i=0; $i<2; $i++){
                $createdItem = Product::factory()->create([
                    'title' => "[{$category->title}] 상품".($i + 1),
                    'pop' => 1,
                    'special' => 1,
                    'recommend' => 1,
                ]);

                $createdItem->categories()->attach($category->id);
            }
        }

        $products = Product::get();
        foreach($products as $product) {
            Color::factory()->create(['product_id' => $product->id, 'title' => '화이트']);
            Color::factory()->create(['product_id' => $product->id, 'title' => '블랙']);
            Size::factory()->create(['product_id' => $product->id, 'title' => '소 (15cm * 15cm)']);
            Size::factory()->create(['product_id' => $product->id, 'title' => '중 (30cm * 30cm)']);
            Size::factory()->create(['product_id' => $product->id, 'title' => '대 (60cm * 60cm)']);
            Product::factory()->create(['product_id' => $product->id, 'title' => '추가상품예시1']);
            Product::factory()->create(['product_id' => $product->id, 'title' => '추가상품예시2']);

            if (config("app.env") != 'local') {
                $product->addMedia(public_path($this->imgs[rand(0, count($this->imgs) - 1)]))->preservingOriginal()->toMediaCollection("imgs", "s3");
                $product->addMedia(public_path($this->imgs[rand(0, count($this->imgs) - 1)]))->preservingOriginal()->toMediaCollection("imgs", "s3");
                $product->addMedia(public_path($this->imgs[rand(0, count($this->imgs) - 1)]))->preservingOriginal()->toMediaCollection("imgs_prototype", "s3");
                $product->addMedia(public_path($this->imgs[rand(0, count($this->imgs) - 1)]))->preservingOriginal()->toMediaCollection("imgs_prototype", "s3");
                $product->addMedia(public_path($this->imgs[rand(0, count($this->imgs) - 1)]))->preservingOriginal()->toMediaCollection("imgs_real", "s3");
                $product->addMedia(public_path($this->imgs[rand(0, count($this->imgs) - 1)]))->preservingOriginal()->toMediaCollection("imgs_real", "s3");
                $product->addMedia(public_path($this->imgs[rand(0, count($this->imgs) - 1)]))->preservingOriginal()->toMediaCollection("imgs_circle", "s3");
                $product->addMedia(public_path($this->imgs[rand(0, count($this->imgs) - 1)]))->preservingOriginal()->toMediaCollection("imgs_circle", "s3");
            }
        }
    }

    public function createCouponGroups()
    {
        $items = [
            [
                'title' => '5% 할인',
                'ratio_discount' => 5,
                'duration' => '60'
            ],

            [
                'title' => '10% 할인',
                'ratio_discount' => 10,
                'duration' => '60'
            ],
        ];

        $users = User::get();

        foreach($items as $item){
            $createdItem = CouponGroup::create($item);

            foreach($users as $user){
                Coupon::factory()->create([
                    'coupon_group_id' => $createdItem->id,
                    'user_id' => $user->id,
                ]);
            }
        }
    }

    public function createUsers()
    {
        User::factory()->create([
            "ids" => "test",
            "type" => TypeUser::COMMON,
            "email" => "test@naver.com",
            "password" => Hash::make("test"),
            "contact" => "01030217486",
            "name" => "일반 이름",
        ]);

        User::factory()->create([
            "ids" => "company",
            "type" => TypeUser::COMPANY,
            "email" => "company@naver.com",
            "password" => Hash::make("company"),
            "contact" => "01030217486",
            "name" => "사업자 이름",
        ]);

        User::factory()->create([
            "ids" => "admin",
            "type" => TypeUser::COMPANY,
            "email" => "admin@naver.com",
            "password" => Hash::make("admin"),
            "contact" => "01030217486",
            "name" => "관리자 이름",
            "admin" => 1,
        ]);
    }

    public function createCategories()
    {
        $items = [
            [
                'title' => '감사패',
            ],
            [
                'title' => '트로피',
            ],
            [
                'title' => '싱글패',
            ],
            [
                'title' => '이글패',
            ],
            [
                'title' => '홀인원',
            ],
            [
                'title' => '교회',
            ],
            [
                'title' => '명패',
            ],
            [
                'title' => '기타',
            ],
        ];

        foreach($items as $item){
            $createdItem = Category::factory()->create($item);

            if(config("app.env") != 'local'){
                $createdItem->addMedia(public_path($this->imgs[rand(0, count($this->imgs) - 1)]))->preservingOriginal()->toMediaCollection("imgs", "s3");
                $createdItem->addMedia(public_path($this->imgs[rand(0, count($this->imgs) - 1)]))->preservingOriginal()->toMediaCollection("example", "s3");
            }
        }
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
                "pg" => "html5_inicis",
                "method" => "card",
                "name" => "신용카드",
                "commission" => "7",
            ],
        ];

        foreach($payMethods as $payMethod){
            PayMethod::create([
                "pg" => $payMethod["pg"],
                "method" => $payMethod["method"],
                "name" => $payMethod["name"],
                "commission" => $payMethod["commission"],
            ]);
        }
    }
    public function createBanners()
    {
        $items = [
            [
                'type' => TypeBanner::MAIN,
                'tag' => '트로피',
                'title' => '
                빛나는 순간을 더욱 빛나게 하는
오직 하나뿐인 디자인을 그려냅니다.',
                'description' => '장인의 상패에서는 노력해 온 순간과 행복을 나눈 시간을 
오래도록 추억할 수 있도록 기록합니다.',
                'url' => '/products?category_id=2',
                'img' => '/images/banner1.png',
            ],
            [
                'type' => TypeBanner::MAIN,
                'tag' => '감사패',
                'title' => '
                감사패 테스트 빛나는 순간을 더욱 빛나게 하는
오직 하나뿐인 디자인을 그려냅니다.',
                'description' => '장인의 상패에서는 노력해 온 순간과 행복을 나눈 시간을 
오래도록 추억할 수 있도록 기록합니다.',
                'url' => '/products?category_id=1',
                'img' => '/images/banner1.png',
            ],
            [
                'type' => TypeBanner::MIDDLE,
                'title' => '문구추천',
                'url' => '/phrases',
                'img' => '/images/middleBanner1.png',
            ],
            [
                'type' => TypeBanner::MIDDLE,
                'title' => '당일제작',
                'url' => '/products',
                'img' => '/images/middleBanner2.png',
            ],
            [
                'type' => TypeBanner::BAND,
                'title' => '가성비 상패',
                'url' => '/products',
                'img' => '/images/bandBanner1.png',
            ],
        ];

        foreach($items as $item){
            $createdItem = Banner::create(\Illuminate\Support\Arr::except($item, 'img'));

            if(config("app.env") != 'local'){
                $createdItem->addMedia(public_path($item['img']))->preservingOriginal()->toMediaCollection("img", "s3");
            }
        }
    }

}
