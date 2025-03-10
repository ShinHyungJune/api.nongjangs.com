<?php


use App\Enums\TypeReview;
use App\Models\Count;
use App\Models\Like;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReviewsTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected $other;

    protected $form;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $this->user = User::factory()->create();
        $this->other = User::factory()->create();

        $this->actingAs($this->user);

        $this->form = [
            "title" => "123",
        ];
    }

    /** @test */
    public function 누구나_목록을_조회할_수_있다()
    {
        $reviews = \App\Models\Review::factory()->count(5)->create();

        $items = $this->json('get', '/api/reviews', [

        ])->decodeResponseJson()['data'];

        $this->assertEquals(count($reviews), count($items));
    }

    /** @test */
    public function 사용자별_목록을_조회할_수_있다()
    {
        $reviews = \App\Models\Review::factory()->count(5)->create([
            'user_id' => $this->user->id,
        ]);

        $otherReviews = \App\Models\Review::factory()->count(3)->create([
            'user_id' => $this->other->id,
        ]);

        $items = $this->json('get', '/api/reviews', [
            'user_id' => $this->other->id,
        ])->decodeResponseJson()['data'];

        $this->assertEquals(count($otherReviews), count($items));
    }

    /** @test */
    public function 상품별_목록을_조회할_수_있다()
    {
        $a = \App\Models\Product::factory()->create();
        $b = \App\Models\Product::factory()->create();
        $aReviews = \App\Models\Review::factory()->count(5)->create([
            'product_id' => $a->id,
        ]);

        $bReviews = \App\Models\Review::factory()->count(3)->create([
            'product_id' => $b->id,
        ]);

        $items = $this->json('get', '/api/reviews', [
            'product_id' => $a->id,
        ])->decodeResponseJson()['data'];

        $this->assertEquals(count($aReviews), count($items));
    }

    /** @test */
    public function 패키지별_목록을_조회할_수_있다()
    {
        $a = \App\Models\Package::factory()->create();
        $b = \App\Models\Package::factory()->create();
        $aReviews = \App\Models\Review::factory()->count(5)->create([
            'package_id' => $a->id,
        ]);

        $bReviews = \App\Models\Review::factory()->count(3)->create([
            'package_id' => $b->id,
        ]);

        $items = $this->json('get', '/api/reviews', [
            'package_id' => $a->id,
        ])->decodeResponseJson()['data'];

        $this->assertEquals(count($aReviews), count($items));
    }

    /** @test */
    public function 상품유형별_목록을_조회할_수_있다()
    {
        $a = \App\Models\Package::factory()->create();
        $b = \App\Models\Product::factory()->create();
        $aReviews = \App\Models\Review::factory()->count(5)->create([
            'package_id' => $a->id,
            'product_id' => null,
        ]);

        $bReviews = \App\Models\Review::factory()->count(3)->create([
            'product_id' => $b->id,
            'package_id' => null,
        ]);

        $items = $this->json('get', '/api/reviews', [
            'has_column' => 'package_id',
        ])->decodeResponseJson()['data'];

        $this->assertEquals(count($aReviews), count($items));
    }

    /** @test */
    public function 사진첨부여부가_참인_목록만_조회할_수_있다()
    {
        $photoReviews = \App\Models\Review::factory()->count(5)->create([
            'photo' => 1,
        ]);

        $notPhotoReviews = \App\Models\Review::factory()->count(3)->create([
            'photo' => 0,
        ]);

        $items = $this->json('get', '/api/reviews', [
            'photo' => 1,
        ])->decodeResponseJson()['data'];

        $this->assertEquals(count($photoReviews), count($items));
    }

    /** @test */
    public function 베스트순_목록을_조회할_수_있다()
    {
        $firstItem = \App\Models\Review::factory()->create([
            'best' => 1
        ]);

        $thirdItem = \App\Models\Review::factory()->create([
            'best' => 0
        ]);

        $secondItem = \App\Models\Review::factory()->create([
            'best' => 1
        ]);

        $items = $this->json('get', '/api/reviews', [
            'order_by' => 'best',
        ])->decodeResponseJson()['data'];

        $prevItem = null;

        foreach($items as $item){
            if($prevItem)
                $this->assertTrue($prevItem['best'] >= $item['best']);

            $prevItem = $item;
        }
    }

    /** @test */
    public function 최신순_목록을_조회할_수_있다()
    {
        $firstItem = \App\Models\Review::factory()->create([
            'created_at' => Carbon::now()->addDays(0)
        ]);

        $thirdItem = \App\Models\Review::factory()->create([
            'created_at' => Carbon::now()->addDays(3)
        ]);

        $secondItem = \App\Models\Review::factory()->create([
            'created_at' => Carbon::now()->addDays(2)
        ]);

        $items = $this->json('get', '/api/reviews', [
            'order_by' => 'created_at',
        ])->decodeResponseJson()['data'];

        $prevItem = null;

        foreach($items as $item){
            if($prevItem)
                $this->assertTrue($prevItem['format_created_at'] > $item['format_created_at']);

            $prevItem = $item;
        }
    }

    /** @test */
    public function 좋아요순_목록을_조회할_수_있다()
    {
        $firstItem = \App\Models\Review::factory()->create([

        ]);

        $thirdItem = \App\Models\Review::factory()->create([

        ]);

        $secondItem = \App\Models\Review::factory()->create([

        ]);

        Like::factory()->count(3)->create([
            'likeable_id' => $firstItem->id,
            'likeable_type' => Review::class
        ]);

        Like::factory()->count(1)->create([
            'likeable_id' => $thirdItem->id,
            'likeable_type' => Review::class
        ]);

        Like::factory()->count(2)->create([
            'likeable_id' => $secondItem->id,
            'likeable_type' => Review::class
        ]);

        $items = $this->json('get', '/api/reviews', [
            'order_by' => 'count_like',
        ])->decodeResponseJson()['data'];

        $prevItem = null;

        foreach($items as $item){
            if($prevItem)
                $this->assertTrue($prevItem['count_like'] > $item['count_like']);

            $prevItem = $item;
        }
    }


    /** @test */
    public function 사용자는_데이터를_생성할_수_있다()
    {
        $preset = \App\Models\Preset::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $presetProduct = \App\Models\PresetProduct::factory()->create([
            'preset_id' => $preset->id,
            'state' => \App\Enums\StatePresetProduct::CONFIRMED,
            'product_id' => Product::factory()->create()->id,
        ]);

        $this->form = [
            'preset_product_id' => $presetProduct->id,
            'description' => 'asdsad',
            'score' => 5,
            'imgs' => [
                ['file' => \Illuminate\Http\UploadedFile::fake()->image('image1.jpg')],
                ['file' => \Illuminate\Http\UploadedFile::fake()->image('image2.jpg')],
            ],
        ];

        $item = $this->json('post', '/api/reviews', $this->form)->decodeResponseJson()['data'];

        $this->assertEquals(1, $item['photo']);
    }

    /** @test */
    public function 사용자는_자신의_구매확정건에_대해서만_데이터를_생성할_수_있다()
    {
        $preset = \App\Models\Preset::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $presetProduct = \App\Models\PresetProduct::factory()->create([
            'preset_id' => $preset->id,
            'state' => \App\Enums\StatePresetProduct::READY,
            'product_id' => Product::factory()->create()->id,
        ]);

        $this->form = [
            'preset_product_id' => $presetProduct->id,
            'description' => 'asdsad',
            'score' => 5,
            'imgs' => [
                ['file' => \Illuminate\Http\UploadedFile::fake()->image('image1.jpg')],
                ['file' => \Illuminate\Http\UploadedFile::fake()->image('image2.jpg')],
            ],
        ];

        $this->json('post', '/api/reviews', $this->form)->assertStatus(403);
    }

    /** @test */
    public function 같은_주문건에_대해_중복생성할_수_없다()
    {
        $preset = \App\Models\Preset::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $presetProduct = \App\Models\PresetProduct::factory()->create([
            'preset_id' => $preset->id,
            'state' => \App\Enums\StatePresetProduct::CONFIRMED,
            'product_id' => Product::factory()->create()->id,
        ]);

        $this->form = [
            'preset_product_id' => $presetProduct->id,
            'description' => 'asdsad',
            'score' => 5,
            'imgs' => [
                ['file' => \Illuminate\Http\UploadedFile::fake()->image('image1.jpg')],
                ['file' => \Illuminate\Http\UploadedFile::fake()->image('image2.jpg')],
            ],
        ];

        $item = $this->json('post', '/api/reviews', $this->form)->decodeResponseJson()['data'];

        $this->assertEquals(1, $item['photo']);
    }

    /** @test */
    public function 수정할_수_있다()
    {
        $review = \App\Models\Review::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $test = '5';

        $this->form = [
            'description' => $test,
            'score' => 5,
        ];

        $item = $this->json('patch', '/api/reviews/'.$review->id, $this->form)->decodeResponseJson()['data'];

        $this->assertEquals($item['description'], $test);
    }

    /** @test */
    public function 수정시_사진존재여부에_따라_사진첨부여부가_결정된다()
    {
        $review = \App\Models\Review::factory()->create([
            'user_id' => $this->user->id,
            'photo' => 0,
        ]);

        $this->form = [
            
            'description' => "123",
            'score' => 5,
            "imgs" => [
                ['file' => \Illuminate\Http\UploadedFile::fake()->image('213.jpg')],
            ]
        ];

        $item = $this->json('patch', '/api/reviews/'.$review->id, $this->form)->decodeResponseJson()['data'];

        $this->assertEquals($item['photo'], 1);

        $medias = $review->getMedia('imgs');

        $removeImgIds = [];

        foreach($medias as $media){
            $removeImgIds[] = $media->id;
        }

        $this->form = [
            'description' => "123",
            'score' => 5,
            "imgs_remove_ids" => $removeImgIds
        ];

        $item = $this->json('patch', '/api/reviews/'.$review->id, $this->form)->decodeResponseJson()['data'];

        $this->assertEquals($item['photo'], 0);
    }

    /** @test */
    public function 삭제할_수_있다()
    {
        $review = \App\Models\Review::factory()->create([
            'user_id' => $this->user->id,
            'photo' => 0,
        ]);

        $item = $this->json('delete', '/api/reviews/'.$review->id, $this->form)->assertStatus(200);
    }

    /** @test */
    public function 생성시_포토리뷰_일반리뷰냐에_따라_적립금이_차등지급된다()
    {
        $prevPoint = $this->user->point;

        $preset = \App\Models\Preset::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $presetProduct = \App\Models\PresetProduct::factory()->create([
            'preset_id' => $preset->id,
            'state' => \App\Enums\StatePresetProduct::CONFIRMED,
            'product_id' => Product::factory()->create()->id,
        ]);

        $this->form = [
            'preset_product_id' => $presetProduct->id,
            'description' => 'asdsad',
            'score' => 5,
            'imgs' => [
                ['file' => \Illuminate\Http\UploadedFile::fake()->image('image1.jpg')],
                ['file' => \Illuminate\Http\UploadedFile::fake()->image('image2.jpg')],
            ],
        ];

        $item = $this->json('post', '/api/reviews', $this->form)->decodeResponseJson()['data'];

        $this->assertEquals($prevPoint + Review::$pointPhoto, $this->user->refresh()->point);
    }

    /** @test */
    public function 베스트리뷰로_선정될_시_추가적립금이_지급된다()
    {
        $prevPoint = $this->user->point;

        $review = Review::factory()->create([
            'best' => 0,
            'user_id' => $this->user->id,
        ]);

        $review->update(['best' => 1]);

        $this->assertEquals($prevPoint + Review::$pointBest, $this->user->refresh()->point);
    }
}
