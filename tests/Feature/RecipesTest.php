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

class RecipesTest extends TestCase
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

    }

    /** @test */
    public function 검색어로_목록을_조회할_수_있다()
    {

    }

    /** @test */
    public function 태그들을_포함하는_목록을_조회할_수_있다()
    {

    }

    /** @test */
    public function 좋아요순으로_목록을_조회할_수_있다()
    {

    }

    /** @test */
    public function 등록순으로_목록을_조회할_수_있다()
    {

    }

    /** @test */
    public function 좋아요여부를_조회할_수_있다()
    {

    }

    /** @test */
    public function 북마크여부를_조회할_수_있다()
    {

    }

    /** @test */
    public function 이번주_꾸러미와_관련된_목록을_조회할_수_있다()
    {

    }

    /** @test */
    public function 상세를_조회할_수_있다()
    {

    }

    /** @test */
    public function 상세를_조회하면_조회수가_갱신된다()
    {

    }
}