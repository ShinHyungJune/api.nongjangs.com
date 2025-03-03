<?php


use App\Enums\StateOrder;
use App\Enums\StatePresetProduct;
use App\Enums\TypeDiscount;
use App\Enums\TypePackage;
use App\Enums\TypePackageMaterial;
use App\Models\Count;
use App\Models\Coupon;
use App\Models\CouponGroup;
use App\Models\Grade;
use App\Models\Material;
use App\Models\Order;
use App\Models\Package;
use App\Models\PackageChangeHistory;
use App\Models\PackageSetting;
use App\Models\Preset;
use App\Models\PresetProduct;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PresetProductsTest extends TestCase
{
    use RefreshDatabase;

    protected $grade;

    protected $user;

    protected $packageSetting;
    protected $singleMaterials;
    protected $bungleMaterials;
    protected $selectMaterials;

    protected $other;

    protected $form;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $this->grade = Grade::factory()->create();

        $this->user = User::factory()->create([
            'grade_id' => $this->grade->id,
        ]);

        $this->packageSetting = PackageSetting::factory()->create([
            'user_id' => $this->user->id,
            'active' => 1
        ]);

        $this->other = User::factory()->create();


        $this->actingAs($this->user);

        $this->form = [

        ];
    }

    public function createPackage()
    {
        $package = Package::factory()->create([
            'start_pack_wait_at' => Carbon::now()->subDays(2),
            'finish_pack_wait_at' => Carbon::now()->subDays(2),
            'start_pack_at' => Carbon::now()->subDays(1),
            'finish_pack_at' => Carbon::now()->addDays(3),
            'start_will_out_at' => Carbon::now()->addDays(4),
            'finish_will_out_at' => Carbon::now()->addDays(5),
            'will_delivery_at' => Carbon::now()->addDays(7),
        ]);

        $this->singleMaterials = Material::factory()->count(10)->create();
        $this->bungleMaterials = Material::factory()->count(6)->create();
        $this->selectMaterials = Material::factory()->count(3)->create();

        foreach($this->singleMaterials as $material){
            $price = rand(5000,10000);

            $package->materials()->attach($material->id, [
                'type' => TypePackageMaterial::SINGLE,
                'value' => rand(1,100),
                'unit' => 'g',
                'price_origin' => $price,
                'price' => $price - 1000,
            ]);
        }
        foreach($this->bungleMaterials as $material){
            $price = rand(5000,10000);

            $package->materials()->attach($material->id, [
                'type' => TypePackageMaterial::BUNGLE,
                'value' => rand(1,100),
                'unit' => 'g',
                'price_origin' => $price,
                'price' => $price - 1000,
            ]);
        }
        foreach($this->selectMaterials as $material){
            $price = rand(5000,10000);

            $package->materials()->attach($material->id, [
                'type' => TypePackageMaterial::CAN_SELECT,
                'value' => rand(1,100),
                'unit' => 'g',
                'price_origin' => $price,
                'price' => $price - 1000,
            ]);
        }

        return $package;
    }

    /** @test */
    public function 쿠폰을_적용할_수_있다()
    {
        $coupon = Coupon::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $preset = Preset::factory()->create([
            'user_id' => $this->user->id
        ]);

        $presetProduct = PresetProduct::factory()->create([
            'preset_id' => $preset->id,
        ]);

        $this->json('patch', '/api/presetProducts/coupon/'.$presetProduct->id, [
            'coupon_id' => $coupon->id,
        ])->assertStatus(200);
    }

    /** @test */
    public function 현재_주문_가능한_상품조합의_상품일_경우에만_쿠폰을_적용할_수_있다()
    {
        $coupon = Coupon::factory()->create([
            'user_id' => $this->user->id,
        ]);

        // 남의거
        $preset = Preset::factory()->create([
            'user_id' => $this->other->id
        ]);

        $presetProduct = PresetProduct::factory()->create([
            'preset_id' => $preset->id,
        ]);

        $this->json('patch', '/api/presetProducts/coupon/'.$presetProduct->id, [
            'coupon_id' => $coupon->id,
        ])->assertStatus(403);

        // 이미 주문 완료
        $preset = Preset::factory()->create([
            'user_id' => $this->user->id,
            'order_id' => Order::factory()->create(['state' => StateOrder::SUCCESS])
        ]);

        $presetProduct = PresetProduct::factory()->create([
            'preset_id' => $preset->id,
        ]);

        $this->json('patch', '/api/presetProducts/coupon/'.$presetProduct->id, [
            'coupon_id' => $coupon->id,
        ])->assertStatus(403);
    }

    public function 자신의_쿠폰이_아니면_적용할_수_없다()
    {
        $coupon = Coupon::factory()->create([
            'user_id' => $this->other->id,
        ]);

        $preset = Preset::factory()->create([
            'user_id' => $this->user->id
        ]);

        $presetProduct = PresetProduct::factory()->create([
            'preset_id' => $preset->id,
        ]);

        $this->json('patch', '/api/presetProducts/coupon/'.$presetProduct->id, [
            'coupon_id' => $coupon->id,
        ])->assertStatus(403);
    }

    public function 이미_사용한_쿠폰은_적용할_수_없다()
    {
        $coupon = Coupon::factory()->create([
            'user_id' => $this->user->id,
            'use' => 1,
        ]);

        $preset = Preset::factory()->create([
            'user_id' => $this->user->id
        ]);

        $presetProduct = PresetProduct::factory()->create([
            'preset_id' => $preset->id,
        ]);

        $this->json('patch', '/api/presetProducts/coupon/'.$presetProduct->id, [
            'coupon_id' => $coupon->id,
        ])->assertStatus(403);
    }

    public function 쿠폰사용불가_상품에는_쿠폰을_적용할_수_없다()
    {
        $coupon = Coupon::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $preset = Preset::factory()->create([
            'user_id' => $this->user->id
        ]);

        $presetProduct = PresetProduct::factory()->create([
            'preset_id' => $preset->id,
            'product_id' => Product::factory()->create([
                'can_use_coupon' => 0
            ])->id,
        ]);

        $this->json('patch', '/api/presetProducts/coupon/'.$presetProduct->id, [
            'coupon_id' => $coupon->id,
        ])->assertStatus(403);
    }

    /** @test */
    public function 쿠폰을_적용하면_상품조합의_비용과_출고의_쿠폰적용금액이_갱신된다()
    {
        $couponGroup = CouponGroup::factory()->create([
            'type_discount' => TypeDiscount::NUMBER,
            'value' => 1000,
            'max_price_discount' => 700
        ]);

        $coupon = Coupon::factory()->create([
            'user_id' => $this->user->id,
            'coupon_group_id' => $couponGroup->id,
        ]);

        $preset = Preset::factory()->create([
            'user_id' => $this->user->id
        ]);

        $presetProduct = PresetProduct::factory()->create([
            'preset_id' => $preset->id,
        ]);

        $item = $this->json('patch', '/api/presetProducts/coupon/'.$presetProduct->id, [
            'coupon_id' => $coupon->id,
        ])->decodeResponseJson()['data'];

        $this->assertEquals( 700, $item['price_coupon']);
        $this->assertEquals( $item['price'], $item['products_price'] - 700);
    }

    /** @test */
    public function 데이터에서_취소가능여부를_조회할_수_있다()
    {
        $preset = Preset::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $presetProduct = \App\Models\PresetProduct::factory()->create([
            'preset_id' => $preset->id,
            'state' => StatePresetProduct::READY,
        ]);

        $item = $this->json('get', '/api/presetProducts/' . $presetProduct->id, [

        ])->decodeResponseJson()['data'];

        $this->assertEquals(1, $item['can_cancel']);


        // 이미 상품 중 하나가 출고됨
        $presetProduct->update([
            'state' => StatePresetProduct::DELIVERED
        ]);

        $item = $this->json('get', '/api/presetProducts/' . $presetProduct->id, [

        ])->decodeResponseJson()['data'];

        $this->assertEquals(0, $item['can_cancel']);
    }

    /** @test */
    public function 취소를_할_수_있다()
    {
        $preset = Preset::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $presetProduct = \App\Models\PresetProduct::factory()->create([
            'preset_id' => $preset->id,
            'state' => StatePresetProduct::READY,
        ]);

        $item = $this->json('patch', '/api/presetProducts/cancel/' . $presetProduct->id, [

        ])->decodeResponseJson()['data'];

        $this->assertEquals(StatePresetProduct::CANCEL, $item['state']);
    }

    /** @test */
    public function 취소가능여부_거짓이라면_취소를_요청할_수_없다()
    {
        $preset = Preset::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $presetProduct = \App\Models\PresetProduct::factory()->create([
            'preset_id' => $preset->id,
            'state' => StatePresetProduct::WILL_OUT,
        ]);

        $item = $this->json('patch', '/api/presetProducts/cancel/' . $presetProduct->id, [

        ])->assertStatus(403);

    }

    /** @test */
    public function 취소요청을_생성할_수_있다()
    {
        // 취소요청상태로 갱신
        // 취소요청날짜 기록

        $preset = Preset::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $presetProduct = \App\Models\PresetProduct::factory()->create([
            'preset_id' => $preset->id,
            'state' => StatePresetProduct::WILL_OUT,
        ]);

        $item = $this->json('patch', '/api/presetProducts/requestCancel/' . $presetProduct->id, [
            'reason_request_cancel' => '요청사유',
        ])->assertStatus(200);

        $this->assertEquals(StatePresetProduct::REQUEST_CANCEL, $presetProduct->refresh()->state);
        $this->assertEquals(Carbon::now(), $presetProduct->refresh()->request_cancel_at);
    }

    /** @test */
    public function 취소요청을_취소할_수_있다()
    {
        // 취소하면 원래 상태로 되돌려야함 (state_origin 컬럼 추가 필요)
        $preset = Preset::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $presetProduct = \App\Models\PresetProduct::factory()->create([
            'preset_id' => $preset->id,
            'state' => StatePresetProduct::WILL_OUT,
        ]);

        $item = $this->json('patch', '/api/presetProducts/requestCancel/' . $presetProduct->id, [
            'reason_request_cancel' => '요청사유',
        ])->assertStatus(200);

        $item = $this->json('patch', '/api/presetProducts/recoverCancel/' . $presetProduct->id, [

        ])->assertStatus(200);

        $this->assertEquals(StatePresetProduct::WILL_OUT, $presetProduct->refresh()->state);
    }

    /** @test */
    public function 취소요청_가능여부가_참일때만_생성할_수_있다()
    {
        $preset = Preset::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $presetProduct = \App\Models\PresetProduct::factory()->create([
            'preset_id' => $preset->id,
            'state' => StatePresetProduct::BEFORE_PAYMENT,
        ]);

        $item = $this->json('patch', '/api/presetProducts/requestCancel/' . $presetProduct->id, [
            'reason_request_cancel' => '요청사유',
        ])->assertStatus(403);
    }

    /** @test */
    public function 취소완료_상태가_되면_포인트는_반횐된다()
    {
        $prevPoint = $this->user->point;

        $preset = Preset::factory()->create(['user_id' => $this->user->id]);

        $point = 1000;

        $presetProduct = PresetProduct::factory()->create([
            'preset_id' => $preset->id,
            'point' => $point,
            'state' => StatePresetProduct::READY,
        ]);

        $presetProduct->update(['state' => StatePresetProduct::CANCEL]);

        $this->assertEquals($prevPoint + $point, $this->user->refresh()->point);
    }

    /** @test */
    public function 취소완료_상태가_되면_쿠폰은_미사용_처리된다()
    {
        $coupon = Coupon::factory()->create(['use' => 1]);

        $preset = Preset::factory()->create(['user_id' => $this->user->id]);

        $presetProduct = PresetProduct::factory()->create([
            'preset_id' => $preset->id,
            'coupon_id' => $coupon->id,
            'state' => StatePresetProduct::READY,
        ]);

        $presetProduct->update(['state' => StatePresetProduct::CANCEL]);

        $this->assertEquals(0, $coupon->refresh()->use);
    }

    /** @test */
    public function 구매확정을_할_수_있다()
    {
        $preset = Preset::factory()->create(['user_id' => $this->user->id]);

        $presetProduct = PresetProduct::factory()->create([
            'preset_id' => $preset->id,
            'state' => StatePresetProduct::DELIVERED,
        ]);

        $item = $this->json('patch', '/api/presetProducts/confirm/' . $presetProduct->id, [

        ])->decodeResponseJson()['data'];

        $this->assertEquals(StatePresetProduct::CONFIRMED, $item['state']);
    }

    /** @test */
    public function 구매확정이_되면_적립금이_부여된다()
    {
        $prevPoint = $this->user->point;

        $grade = Grade::factory()->create(['ratio_refund' => 0.1]);

        $this->user->update([
            'grade_id' => $grade->id,
        ]);

        $preset = Preset::factory()->create(['user_id' => $this->user->id]);

        $presetProduct = PresetProduct::factory()->create([
            'preset_id' => $preset->id,
            'state' => StatePresetProduct::DELIVERED,
        ]);

        $item = $this->json('patch', '/api/presetProducts/confirm/' . $presetProduct->id, [

        ])->decodeResponseJson()['data'];

        $this->assertEquals($prevPoint + floor($presetProduct->price * $grade->ratio_refund), $this->user->refresh()->point);
    }

    /** @test */
    public function 구매확정이_되면_사용자의_등급이_갱신된다()
    {
        $preset = Preset::factory()->create(['user_id' => $this->user->id]);

        $presetProduct = PresetProduct::factory()->create([
            'preset_id' => $preset->id,
            'state' => StatePresetProduct::DELIVERED,
        ]);

        $this->grade->update(['ratio_refund' => 0.1]);

        $nextGrade = Grade::factory()->create(['level' => $this->grade->level + 1, 'min_price' => $presetProduct->price - 1]);

        $this->user->update([
            'grade_id' => $this->grade->id,
        ]);


        $item = $this->json('patch', '/api/presetProducts/confirm/' . $presetProduct->id, [

        ])->decodeResponseJson()['data'];

        $this->assertEquals($nextGrade->id, $this->user->refresh()->grade_id);
    }

    /** @test */
    public function 구매확정처리를_실행하면_배송완료상태_중_배송완료일자가_일주일_이상_지난_건들은_구매확정처리된다()
    {
        $oldDeliveriedPresetProducts = PresetProduct::factory()->count(3)->create([
           'state' => StatePresetProduct::DELIVERED,
           'delivery_at' => Carbon::now()->subDays(10),
        ]);

        $justDeliveriedPresetProducts = PresetProduct::factory()->count(5)->create([
            'state' => StatePresetProduct::DELIVERED,
            'delivery_at' => Carbon::now()->subDays(3),
        ]);

        $this->artisan('confirm:presetProducts');

        $this->assertEquals(count($oldDeliveriedPresetProducts), PresetProduct::where('state', StatePresetProduct::CONFIRMED)->count());
    }

    /** @test */
    public function 구매확정이_되면_누적구매액과_누적회차수가_갱신된다()
    {
        $preset = Preset::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $oldDeliveriedPresetProducts = PresetProduct::factory()->count(3)->create([
            'preset_id' => $preset->id,
            'state' => StatePresetProduct::DELIVERED,
            'delivery_at' => Carbon::now()->subDays(10),
        ]);

        $total = 0;

        $prevTotalOrderPrice = $this->user->total_order_price;

        foreach($oldDeliveriedPresetProducts as $presetProduct){
            $total += $presetProduct->price;

            $presetProduct->update(['state' => StatePresetProduct::CONFIRMED]);
        }

        $this->assertEquals($prevTotalOrderPrice + $total, $this->user->refresh()->total_order_price);
    }

    /** @test */
    public function 나의_현재_꾸러미출고를_조회할_수_있다()
    {
        // (마이페이지에 보여줄용)
        /*- **출고의 대상회차가 현재 진행중인 회차 리턴**
        - **패키지설정의 구독여부가 꺼져있다면 (active 0) null을 리턴**
        - **가장 최근 꾸러미 출고 리턴***/

        // 출고의 대상회차가 현재 진행중인 회차가 있는 경우
        $ongoingPackage = Package::factory()->create([
            'start_pack_wait_at' => Carbon::now()->subDay(),
            'will_delivery_at' => Carbon::now()->addDay(),
        ]);

        $futurePackage = Package::factory()->create([
            'start_pack_wait_at' => Carbon::now()->addDay(),
            'will_delivery_at' => Carbon::now()->addDays(7),
        ]);

        $preset = Preset::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $ongoingPresetProduct = PresetProduct::factory([
            'preset_id' => $preset->id,
            'package_id' => $ongoingPackage->id,
        ])->create();

        $futurePresetProduct = PresetProduct::factory([
            'preset_id' => $preset->id,
            'package_id' => $futurePackage->id
        ])->create();

        $item = $this->json('get', '/api/presetProducts/currentPackage')->decodeResponseJson()['data'];

        $this->assertEquals($ongoingPresetProduct->id, $item['id']);

        // 미래에 예정된 출고만 남아있을 경우
        $ongoingPresetProduct->delete();

        $item = $this->json('get', '/api/presetProducts/currentPackage')->decodeResponseJson()['data'];

        $this->assertEquals($futurePresetProduct->id, $item['id']);

        // 구독중지 상태인 경우
        $this->packageSetting->update(['active' => 0]);

        $item = $this->json('get', '/api/presetProducts/currentPackage')->decodeResponseJson()['data'];

        $this->assertEquals(null, $item);
    }

    /** @test */
    public function 품목구성알림을_실행하면_품목구성완료일이_지난_이번회차를_대상회차로_설정해놓은_패키지설정을_보유한_사용자들에게_알림이_발송된다()
    {
        /*# 알람조건
            - 이번 회차여야함
            - 현재시간이 품목구성완료일 이상이여야함
            - 품목구성알림여부가 거짓이어야함
            - 이번회차를 현재대상회차로 설정해놓은 출고를 보유한 사용자들이어야함

        # 알람
            - 알람 발송 후 품목구성알림여부 1로 업데이트
            - 해당 출고에 싱글,벙글이냐에 따라 품목 자동 연결*/
        $startPackage = Package::factory()->create([
            'start_pack_wait_at' => Carbon::now()->subDay(),
            'start_pack_at' => Carbon::now()->subDays(1)
        ]);

        $startPresetProducts = PresetProduct::factory()->count(2)->create(['package_id' => $startPackage->id]);

        $notStartPackage = Package::factory()->create([
            'start_pack_wait_at' => Carbon::now()->subDay(),
            'start_pack_at' => Carbon::now()->addDays(10),
        ]);

        $notStartPresetProducts = PresetProduct::factory()->count(3)->create(['package_id' => $notStartPackage->id]);

        $this->artisan('alert:packageStartPack');

        $this->assertEquals(count($startPresetProducts), PresetProduct::where('alert_pack', 1)->count());
    }

    /** @test */
    public function 품목구성알림이_실행되면_출고에_품목이_자동으로_연결된다()
    {
        $package = $this->createPackage();

        $preset = Preset::factory()->create(['user_id' => $this->user->id]);

        $presetProduct = PresetProduct::factory()->create(['package_type'=> TypePackage::BUNGLE, 'package_id' => $package->id, 'preset_id' => $preset->id]);

        $this->artisan('alert:packageStartPack');

        $this->assertEquals(count($this->bungleMaterials), $presetProduct->refresh()->materials()->count());
    }

    /** @test */
    public function 품목구성알림이_실행되면_출고에_비선호_품목은_제외하고_품목이_연결된다()
    {
        // 비선호 품목 제외처리
        // while문으로 반복 계속 돌면서 최소금액 채울때까지 더하기
        $package = $this->createPackage();

        $unlikeMaterials = $this->bungleMaterials->take(2);

        foreach($unlikeMaterials as $material){
            $this->packageSetting->materials()->attach($material->id, [
                'unlike' => 1
            ]);
        }

        $preset = Preset::factory()->create(['user_id' => $this->user->id]);

        $presetProduct = PresetProduct::factory()->create(['package_type' => TypePackage::BUNGLE, 'package_id' => $package->id, 'preset_id' => $preset->id]);

        $this->artisan('alert:packageStartPack');

        $priceTotal = 0;

        foreach($presetProduct->refresh()->materials as $material){
            $priceTotal += $material->pivot->count * $material->pivot->price;
        }

        $this->assertEquals(count($this->bungleMaterials) - count($unlikeMaterials), $presetProduct->refresh()->materials()->count());
        $this->assertTrue($this->packageSetting->price_bungle <= $priceTotal);
    }

    /** @test */
    public function 사용자는_출고의_품목구성을_수정할_수_있다()
    {
        /* materials
           id
           count
        */
        $package = $this->createPackage();

        $preset = Preset::factory()->create(['user_id' => $this->user->id]);

        $presetProduct = PresetProduct::factory()->create([
            'package_type' => TypePackage::BUNGLE,
            'package_id' => $package->id,
            'preset_id' => $preset->id
        ]);

        $materials = [];

        foreach($this->selectMaterials as $material){
            $materials[] = [
                'id' => $material->id,
                'count' => 1,
            ];
        }

        foreach($this->bungleMaterials as $material){
            $materials[] = [
                'id' => $material->id,
                'count' => 1,
            ];
        }

        $this->artisan('alert:packageStartPack');

        $this->assertEquals(count($this->bungleMaterials), $presetProduct->refresh()->materials()->count());

        $this->json('patch', '/api/presetProducts/materials/'.$presetProduct->id, [
            'materials' => $materials
        ]);

        $this->assertEquals(count($this->bungleMaterials) + count($this->selectMaterials), $presetProduct->refresh()->materials()->count());
    }

    /** @test */
    public function 출고의_대상회차에_있는_선택가능구성_및_기본구성_내에서만_수정할_수_있다()
    {

        /* materials
                   id
                   count
                */
        $package = $this->createPackage();

        $preset = Preset::factory()->create(['user_id' => $this->user->id]);

        $presetProduct = PresetProduct::factory()->create([
            'package_type' => TypePackage::BUNGLE,
            'package_id' => $package->id,
            'preset_id' => $preset->id
        ]);

        $material = Material::factory()->create();

        $materials = [
            [
                'id' => $material->id,
                'count' => 1,
            ],
        ];

        foreach($this->bungleMaterials as $material){
            $materials[] = [
                'id' => $material->id,
                'count' => 1,
            ];
        }

        $this->artisan('alert:packageStartPack');

        $this->json('patch', '/api/presetProducts/materials/'.$presetProduct->id, [
            'materials' => $materials
        ])->assertStatus(403);
    }

    /** @test */
    public function 출고의_대상회차가_품목구성상태가_아니라면_수정할_수_없다()
    {
        $package = $this->createPackage();

        $preset = Preset::factory()->create(['user_id' => $this->user->id]);

        $presetProduct = PresetProduct::factory()->create([
            'package_type' => TypePackage::BUNGLE,
            'package_id' => $package->id,
            'preset_id' => $preset->id
        ]);

        foreach($this->bungleMaterials as $material){
            $materials[] = [
                'id' => $material->id,
                'count' => 1,
            ];
        }

        $this->artisan('alert:packageStartPack');

        $this->json('patch', '/api/presetProducts/materials/'.$presetProduct->id, [
            'materials' => $materials
        ])->assertStatus(200);

        $package->update(['finish_pack_at' => Carbon::now()->subDay()]);

        $this->json('patch', '/api/presetProducts/materials/'.$presetProduct->id, [
            'materials' => $materials
        ])->assertStatus(403);

    }

    /** @test */
    public function 품목구성금액은_패키지유형의_최소금액을_맞춰야한다()
    {
        $package = $this->createPackage();

        $preset = Preset::factory()->create(['user_id' => $this->user->id]);

        $presetProduct = PresetProduct::factory()->create([
            'package_type' => TypePackage::BUNGLE,
            'package_id' => $package->id,
            'preset_id' => $preset->id
        ]);

        foreach($this->bungleMaterials as $material){
            $materials[] = [
                'id' => $material->id,
                'count' => 1,
            ];
        }

        array_splice($materials, 0, 1);

        $this->artisan('alert:packageStartPack');

        $this->json('patch', '/api/presetProducts/materials/'.$presetProduct->id, [
            'materials' => $materials
        ])->assertStatus(403);
    }

    /** @test */
    public function 출고에서_미루기_및_당기기_가능한_패키지를_조회할_수_있다()
    {
        $packages = Package::get();

        foreach($packages as $package){
            $package->presetProducts()->delete();

            $package->delete();
        }

        $currentPackage = Package::factory()->create([
            'count' => 1,
            'start_pack_wait_at' => Carbon::now()->subDays(4),
            'finish_pack_wait_at' => Carbon::now()->subDays(3),
            'start_pack_at' => Carbon::now()->subDays(2),
            'finish_pack_at' => Carbon::now()->subDays(1),
            'start_delivery_ready_at' => Carbon::now()->subDays(2),
            'finish_delivery_ready_at' => Carbon::now()->addDays(4),
            'start_will_out_at' => Carbon::now()->addDays(4),
            'finish_will_out_at' => Carbon::now()->addDays(4),
            'will_delivery_at' => Carbon::now()->addDays(4),
        ]);
        $futurePackage = Package::factory()->create([
            'count' => 2,
            'start_pack_wait_at' => Carbon::now()->addWeeks(1),
            'finish_pack_wait_at' => Carbon::now()->addWeeks(2),
            'start_pack_at' => Carbon::now()->addWeeks(2),
            'finish_pack_at' => Carbon::now()->addWeeks(2),
            'start_delivery_ready_at' => Carbon::now()->addWeeks(2),
            'finish_delivery_ready_at' => Carbon::now()->addWeeks(2),
            'start_will_out_at' => Carbon::now()->addWeeks(2),
            'finish_will_out_at' => Carbon::now()->addWeeks(2),
            'will_delivery_at' => Carbon::now()->addWeeks(2),
        ]);
        $moreFuturePackage = Package::factory()->create([
            'count' => 3,
            'start_pack_wait_at' => Carbon::now()->addWeeks(4),
            'finish_pack_wait_at' => Carbon::now()->addWeeks(4),
            'start_pack_at' => Carbon::now()->addWeeks(4),
            'finish_pack_at' => Carbon::now()->addWeeks(4),
            'start_delivery_ready_at' => Carbon::now()->addWeeks(4),
            'finish_delivery_ready_at' => Carbon::now()->addWeeks(4),
            'start_will_out_at' => Carbon::now()->addWeeks(4),
            'finish_will_out_at' => Carbon::now()->addWeeks(4),
            'will_delivery_at' => Carbon::now()->addWeeks(4),
        ]);

        $preset = Preset::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $this->user->presetProducts()->delete();

        $presetProduct = PresetProduct::factory()->create([
            'preset_id' => $preset->id,
            'package_id' => $futurePackage->id,
            'package_count' => $futurePackage->count,
        ]);

        $this->assertEquals($currentPackage->id, $presetProduct->refresh()->canFastPackage->id);
        $this->assertEquals($moreFuturePackage->id, $presetProduct->refresh()->canLatePackage->id);
    }

    /** @test */
    public function 배송당기기를_할_수_있다()
    {
        /*- 출고가 결제대기중이어야함
    - 해당 출고에 당기기 가능한 패키지가 있어야함
    - 당길 경우 대상회차가 현재주문가능회차로 변경됨
    - 배송당기기 히스토리가 생성됨*/
        $packages = Package::get();

        foreach($packages as $package){
            $package->presetProducts()->delete();

            $package->delete();
        }

        $currentPackage = Package::factory()->create([
            'count' => 1,
            'start_pack_wait_at' => Carbon::now()->subDays(4),
            'finish_pack_wait_at' => Carbon::now()->subDays(3),
            'start_pack_at' => Carbon::now()->subDays(2),
            'finish_pack_at' => Carbon::now()->subDays(1),
            'start_delivery_ready_at' => Carbon::now()->subDays(2),
            'finish_delivery_ready_at' => Carbon::now()->addDays(4),
            'start_will_out_at' => Carbon::now()->addDays(4),
            'finish_will_out_at' => Carbon::now()->addDays(4),
            'will_delivery_at' => Carbon::now()->addDays(4),
        ]);
        $futurePackage = Package::factory()->create([
            'count' => 2,
            'start_pack_wait_at' => Carbon::now()->addWeeks(1),
            'finish_pack_wait_at' => Carbon::now()->addWeeks(2),
            'start_pack_at' => Carbon::now()->addWeeks(2),
            'finish_pack_at' => Carbon::now()->addWeeks(2),
            'start_delivery_ready_at' => Carbon::now()->addWeeks(2),
            'finish_delivery_ready_at' => Carbon::now()->addWeeks(2),
            'start_will_out_at' => Carbon::now()->addWeeks(2),
            'finish_will_out_at' => Carbon::now()->addWeeks(2),
            'will_delivery_at' => Carbon::now()->addWeeks(2),
        ]);
        $moreFuturePackage = Package::factory()->create([
            'count' => 3,
            'start_pack_wait_at' => Carbon::now()->addWeeks(4),
            'finish_pack_wait_at' => Carbon::now()->addWeeks(4),
            'start_pack_at' => Carbon::now()->addWeeks(4),
            'finish_pack_at' => Carbon::now()->addWeeks(4),
            'start_delivery_ready_at' => Carbon::now()->addWeeks(4),
            'finish_delivery_ready_at' => Carbon::now()->addWeeks(4),
            'start_will_out_at' => Carbon::now()->addWeeks(4),
            'finish_will_out_at' => Carbon::now()->addWeeks(4),
            'will_delivery_at' => Carbon::now()->addWeeks(4),
        ]);

        $preset = Preset::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $this->user->presetProducts()->delete();

        $presetProduct = PresetProduct::factory()->create([
            'preset_id' => $preset->id,
            'package_id' => $futurePackage->id,
            'package_count' => $futurePackage->count,
        ]);

        $item = $this->json('patch', '/api/presetProducts/fast/'.$presetProduct->id)->decodeResponseJson()['data'];

        $this->assertEquals($currentPackage->id, $item['package']['id']);
        $this->assertEquals(1, PackageChangeHistory::count());
    }

    /** @test */
    public function 배송미루기를_할_수_있다()
    {
        /*- 출고가 결제대기중이어야함
    - 미룰 경우 대상회차가 현재회차의 바로 다음회차로 변경됨
    - 배송미루기 히스토리가 생성됨*/
        $packages = Package::get();

        foreach($packages as $package){
            $package->presetProducts()->delete();

            $package->delete();
        }

        $currentPackage = Package::factory()->create([
            'count' => 1,
            'start_pack_wait_at' => Carbon::now()->subDays(4),
            'finish_pack_wait_at' => Carbon::now()->subDays(3),
            'start_pack_at' => Carbon::now()->subDays(2),
            'finish_pack_at' => Carbon::now()->subDays(1),
            'start_delivery_ready_at' => Carbon::now()->subDays(2),
            'finish_delivery_ready_at' => Carbon::now()->addDays(4),
            'start_will_out_at' => Carbon::now()->addDays(4),
            'finish_will_out_at' => Carbon::now()->addDays(4),
            'will_delivery_at' => Carbon::now()->addDays(4),
        ]);
        $futurePackage = Package::factory()->create([
            'count' => 2,
            'start_pack_wait_at' => Carbon::now()->addWeeks(1),
            'finish_pack_wait_at' => Carbon::now()->addWeeks(2),
            'start_pack_at' => Carbon::now()->addWeeks(2),
            'finish_pack_at' => Carbon::now()->addWeeks(2),
            'start_delivery_ready_at' => Carbon::now()->addWeeks(2),
            'finish_delivery_ready_at' => Carbon::now()->addWeeks(2),
            'start_will_out_at' => Carbon::now()->addWeeks(2),
            'finish_will_out_at' => Carbon::now()->addWeeks(2),
            'will_delivery_at' => Carbon::now()->addWeeks(2),
        ]);
        $moreFuturePackage = Package::factory()->create([
            'count' => 3,
            'start_pack_wait_at' => Carbon::now()->addWeeks(4),
            'finish_pack_wait_at' => Carbon::now()->addWeeks(4),
            'start_pack_at' => Carbon::now()->addWeeks(4),
            'finish_pack_at' => Carbon::now()->addWeeks(4),
            'start_delivery_ready_at' => Carbon::now()->addWeeks(4),
            'finish_delivery_ready_at' => Carbon::now()->addWeeks(4),
            'start_will_out_at' => Carbon::now()->addWeeks(4),
            'finish_will_out_at' => Carbon::now()->addWeeks(4),
            'will_delivery_at' => Carbon::now()->addWeeks(4),
        ]);

        $preset = Preset::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $this->user->presetProducts()->delete();

        $presetProduct = PresetProduct::factory()->create([
            'preset_id' => $preset->id,
            'package_id' => $futurePackage->id,
            'package_count' => $futurePackage->count,
        ]);

        $item = $this->json('patch', '/api/presetProducts/late/'.$presetProduct->id)->decodeResponseJson()['data'];

        $this->assertEquals($moreFuturePackage->id, $item['package']['id']);
        $this->assertEquals(1, PackageChangeHistory::count());
    }

    // 후순위
    /** @test */
    public function 자동결제를_시도하면_현재_회차를_대상회차로_갖고있는_사용자들중_결제전인_출고에_대해_결제가_시도된다()
    {


    }

    /** @test */
    public function 결제가_성공사태가_되면_다음_출고가_자동생성된다()
    {
        // PackageSetting이 active라면 배송주기에 맞게 다음 출고 생성
    }


    /** @test */
    public function 결제를_시도할_시_사용자의_쿠폰자동적용여부가_참이라면_쿠폰이_자동적용된다()
    {
        // 쿠폰유형이 꾸러미용이고 최소주문금액 맞출 경우 최대금액까지만 쓸 수 있게 쿠폰 찾아야할듯
    }

    /** @test */
    public function 결제를_시도할_시_사용자의_적립금자동적용여부가_참이라면_적립금이_자동적용된다()
    {
        // 결제금액이 1000원 남을때까지만 적용
    }

    /** @test */
    public function 자동결제를_실패하면_세번까지_재시도한다()
    {

    }

    /** @test */
    public function 세번까지_재시도를_실패하면_다음_회차로_자동미루기가_된다()
    {
        /*- ***미루기 당기기 기록 생성 필요***
        - **쌓인 실패 orders의 수를 보기 (reason도 남겨)***/
    }

    /** @test */
    public function 배송상태갱신을_실행하면_배송상태가_수정된다()
    {
        // API 참고해서 추가설계필요
    }
}
