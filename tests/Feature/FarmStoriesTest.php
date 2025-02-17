<?php


use App\Models\Farm;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FarmStoriesTest extends TestCase
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

        ];
    }

    /** @test */
    public function 누구나_목록을_조회할_수_있다()
    {
        $farmStories = \App\Models\FarmStory::factory()->count(5)->create();

        $items = $this->json('get', '/api/farmStories', [])->decodeResponseJson()['data'];

        $this->assertEquals(count($farmStories), count($items));
    }

    /** @test */
    public function 검색어로_목록을_조회할_수_있다()
    {
        // word
        $word = 'test';

        $includeFarmStories = \App\Models\FarmStory::factory()->count(5)->create([
            'title' => $word,
        ]);

        $excludeFarmStories = \App\Models\FarmStory::factory()->count(5)->create([

        ]);

        $items = $this->json('get', '/api/farmStories', [
            'word' => $word,
        ])->decodeResponseJson()['data'];

        $this->assertEquals(count($includeFarmStories), count($items));
    }

    /** @test */
    public function 좋아요순으로_목록을_조회할_수_있다 ()
    {
        /*order_by
	count_like
	created_at*/
        $secondItem = \App\Models\FarmStory::factory()->create();
        $firstItem = \App\Models\FarmStory::factory()->create();
        $thirdItem = \App\Models\FarmStory::factory()->create();

        \App\Models\Like::factory()->count(3)->create([
            'likeable_id' => $firstItem->id,
            'likeable_type' => \App\Models\FarmStory::class
        ]);

        \App\Models\Like::factory()->count(2)->create([
            'likeable_id' => $secondItem->id,
            'likeable_type' => \App\Models\FarmStory::class
        ]);

        \App\Models\Like::factory()->count(1)->create([
            'likeable_id' => $thirdItem->id,
            'likeable_type' => \App\Models\FarmStory::class
        ]);

        $items = $this->json('get', '/api/farmStories', [
            'order_by' => 'count_like',
        ])->decodeResponseJson()['data'];

        $prevItem = null;

        foreach($items as $item){
            if($prevItem){
                $this->assertTrue($item['count_like'] < $prevItem['count_like']);
            }
            $prevItem = $item;
        }
    }

    /** @test */
    public function 등록순으로_목록을_조회할_수_있다()
    {
        $secondItem = \App\Models\FarmStory::factory()->create([
            'created_at' => \Carbon\Carbon::now()->subDays(1)
        ]);
        $firstItem = \App\Models\FarmStory::factory()->create([
            'created_at' => \Carbon\Carbon::now()->subDays(0)
        ]);
        $thirdItem = \App\Models\FarmStory::factory()->create([
            'created_at' => \Carbon\Carbon::now()->subDays(3)
        ]);

        $items = $this->json('get', '/api/farmStories', [
            'order_by' => 'created_at',
        ])->decodeResponseJson()['data'];

        $prevItem = null;

        foreach($items as $item){
            if($prevItem){
                $this->assertTrue($item['created_at'] < $prevItem['created_at']);

            }
            $prevItem = $item;
        }
    }

    /** @test */
    public function 태그들을_포함한_목록을_조회할_수_있다()
    {
        // tags
        $tag = \App\Models\Tag::factory()->create();

        $includeFarmStories = \App\Models\FarmStory::factory()->count(5)->create([

        ]);

        foreach($includeFarmStories as $farmStory){
            $farmStory->tags()->attach($tag);
        }

        $excludeFarmStories = \App\Models\FarmStory::factory()->count(3)->create([

        ]);

        $items = $this->json('get', '/api/farmStories', [
            'tag_ids' => [$tag->id],
        ])->decodeResponseJson()['data'];

        $this->assertEquals(count($includeFarmStories), count($items));
    }

    /** @test */
    public function 농가별_목록을_조회할_수_있다()
    {
        $farm = \App\Models\Farm::factory()->create();

        $includeFarmStories = \App\Models\FarmStory::factory()->count(5)->create([
            'farm_id' => $farm->id,
        ]);

        $excludeFarmStories = \App\Models\FarmStory::factory()->count(3)->create([
            'farm_id' => Farm::factory()->create()->id,
        ]);

        $items = $this->json('get', '/api/farmStories', [
            'farm_id' => $farm->id,
        ])->decodeResponseJson()['data'];

        $this->assertEquals(count($includeFarmStories), count($items));
    }

    /** @test */
    public function 특정_농가를_제외한_목록을_조회할_수_있다()
    {
        $farm = \App\Models\Farm::factory()->create();

        $includeFarmStories = \App\Models\FarmStory::factory()->count(5)->create([
            'farm_id' => $farm->id,
        ]);

        $excludeFarmStories = \App\Models\FarmStory::factory()->count(3)->create([
            'farm_id' => Farm::factory()->create()->id,
        ]);

        $items = $this->json('get', '/api/farmStories', [
            'exclude_farm_id' => $farm->id,
        ])->decodeResponseJson()['data'];

        $this->assertEquals(count($excludeFarmStories), count($items));
    }



    /** @test */
    public function 데이터를_조회할_수_있다()
    {
        $farmStory = \App\Models\FarmStory::factory()->create([

        ]);

        $item = $this->json('get', '/api/farmStories/'.$farmStory->id, [

        ])->decodeResponseJson()['data'];

        $this->assertEquals($farmStory->id, $item['id']);
    }

    /** @test */
    public function 데이터를_조회하면_조회수가_갱신된다()
    {
        $farmStory = \App\Models\FarmStory::factory()->create([
            'count_view' => 0,
        ]);

        $item = $this->json('get', '/api/farmStories/'.$farmStory->id, [

        ])->decodeResponseJson()['data'];

        $this->assertEquals($farmStory->count_view + 1, $item['count_view']);
    }

    /** @test */
    public function 데이터에서_좋아요여부를_조회할_수_있다()
    {
        $farmStory = \App\Models\FarmStory::factory()->create([

        ]);

        $item = $this->json('get', '/api/farmStories/'.$farmStory->id, [

        ])->decodeResponseJson()['data'];

        $this->assertEquals(0, $item['is_like']);

        \App\Models\Like::factory()->create([
            'likeable_type' => \App\Models\FarmStory::class,
            'likeable_id' => $farmStory->id,
            'user_id' => $this->user->id,
        ]);

        $item = $this->json('get', '/api/farmStories/'.$farmStory->id, [

        ])->decodeResponseJson()['data'];

        $this->assertEquals(1, $item['is_like']);
    }

}
