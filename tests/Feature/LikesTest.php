<?php


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LikesTest extends TestCase
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
    public function 레시피에_대한_데이터를_생성할_수_있다()
    {
        /*likeable_type - Recipe
likeable_id*/
    }

    /** @test */
    public function 농가이야기에_대한_데이터를_생성할_수_있다()
    {
        l/*ikeable_type - Story
likeable_id*/
    }
}