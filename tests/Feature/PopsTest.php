<?php



use App\Enums\TypeBanner;
use App\Models\Banner;
use App\Models\Coupon;
use App\Models\Program;
use App\Models\User;
use App\Models\Waiting;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PopsTest extends TestCase
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
        /*$banners = Banner::factory()->count(5)->create();

        $items = $this->json('get', '/api/banners')->decodeResponseJson()['data'];

        $this->assertEquals(count($banners), count($items));*/
    }

    /** @test */
    public function 진행중인_목록만_조회할_수_있다()
    {
        // open이고 started_at과 finished_at 기간이 맞아야함
        /*$aBanners = Banner::factory()->count(5)->create([
            'type' => TypeBanner::BAND
        ]);


        $bBanners = Banner::factory()->count(3)->create([
            'type' => TypeBanner::CATEGORY
        ]);

        $items = $this->json('get', '/api/banners', [
            'type' => TypeBanner::CATEGORY,
        ])->decodeResponseJson()['data'];

        $this->assertEquals(count($bBanners), count($items));*/
    }
}