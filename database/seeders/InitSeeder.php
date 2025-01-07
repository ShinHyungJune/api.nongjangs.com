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
use App\Models\Count;
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
use App\Models\Pop;
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
        DB::statement("SET foreign_key_checks=0");

        User::truncate();
        Banner::truncate();
        Pop::truncate();
        Count::truncate();
        City::truncate();
        County::truncate();
        Category::truncate();
        PayMethod::truncate();
        Product::truncate();
        Faq::truncate();
        Qna::truncate();
        Notice::truncate();
        PayMethod::truncate();
        CouponGroup::truncate();
        Coupon::truncate();
        PointHistory::truncate();
        CouponHistory::truncate();
        Faq::truncate();
        Event::truncate();
        Preset::truncate();
        Review::truncate();
        Order::truncate();
        PresetProduct::truncate();

        DB::table("media")->truncate();
        DB::statement("SET foreign_key_checks=1");

        $this->createUsers();

        $this->createBanners();
        $this->createPops();
        $this->createCounts();
        $this->createLocations();
        /*$this->createCategories();
        $this->createPayMethods();
        $this->createCouponGroups();
        $this->createProducts();
        $this->createPointHistories();
        $this->createCouponHistories();*/
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
                'url' => '/stories',
                'pc' => $this->bannerImages[0],
                'mobile' => $this->bannerImages[0],
                'button' => '여정 시작하기',
                'color_text' => 'black',
                'color_button' => '#308929',
                'started_at' => Carbon::now()->subDays(1),
                'finished_at' => Carbon::now()->addDays(3),
            ],
            [
                'type' => TypeBanner::MAIN,
                'title' => '작은 농장에서 온 큰 행복,
당신의 일상에 녹아들다',
                'subtitle' => '',
                'url' => '/stories',
                'pc' => $this->bannerImages[1],
                'mobile' => $this->bannerImages[1],
                'button' => '여정 시작하기',
                'color_text' => 'white',
                'color_button' => '#FF9224',
                'started_at' => Carbon::now()->subDays(1),
                'finished_at' => Carbon::now()->addDays(3),
            ],

            [
                'type' => TypeBanner::FARM_STORY,
                'title' => '작은 농장에서 온 큰 행복,
당신의 일상에 녹아들다',
                'subtitle' => '',
                'url' => '/stories',
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
                'url' => '/stories',
                'pc' => $this->bannerImages[1],
                'mobile' => $this->bannerImages[1],
                'button' => '여정 시작하기',
                'color_text' => 'white',
                'color_button' => '#FF9224',
                'started_at' => Carbon::now()->subDays(1),
                'finished_at' => Carbon::now()->addDays(3),
            ],

            [
                'type' => TypeBanner::SUBSCRIBE,
                'title' => '작은 농장에서 온 큰 행복,
당신의 일상에 녹아들다',
                'subtitle' => '',
                'url' => '/stories',
                'pc' => $this->bannerImages[0],
                'mobile' => $this->bannerImages[0],
                'button' => '여정 시작하기',
                'color_text' => 'black',
                'color_button' => '#308929',
                'started_at' => Carbon::now()->subDays(1),
                'finished_at' => Carbon::now()->addDays(3),
            ],
            [
                'type' => TypeBanner::SUBSCRIBE,
                'title' => '작은 농장에서 온 큰 행복,
당신의 일상에 녹아들다',
                'subtitle' => '',
                'url' => '/stories',
                'pc' => $this->bannerImages[1],
                'mobile' => $this->bannerImages[1],
                'button' => '여정 시작하기',
                'color_text' => 'white',
                'color_button' => '#FF9224',
                'started_at' => Carbon::now()->subDays(1),
                'finished_at' => Carbon::now()->addDays(3),
            ],

            [
                'type' => TypeBanner::DYNAMIC,
                'title' => '작은 농장에서 온 큰 행복,
당신의 일상에 녹아들다',
                'subtitle' => '',
                'url' => '/stories',
                'pc' => $this->bannerImages[0],
                'mobile' => $this->bannerImages[0],
                'button' => '여정 시작하기',
                'color_text' => 'black',
                'color_button' => '#308929',
                'started_at' => Carbon::now()->subDays(1),
                'finished_at' => Carbon::now()->addDays(3),
            ],
            [
                'type' => TypeBanner::DYNAMIC,
                'title' => '작은 농장에서 온 큰 행복,
당신의 일상에 녹아들다',
                'subtitle' => '',
                'url' => '/stories',
                'pc' => $this->bannerImages[1],
                'mobile' => $this->bannerImages[1],
                'button' => '여정 시작하기',
                'color_text' => 'white',
                'color_button' => '#FF9224',
                'started_at' => Carbon::now()->subDays(1),
                'finished_at' => Carbon::now()->addDays(3),
            ],
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

}
