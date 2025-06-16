<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Preset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_use_coupons_works_with_null_preset()
    {
        // Create a user
        $user = User::factory()->create();

        // Test with null preset
        $coupons = $user->canUseCoupons(null);

        // Assert that the method returns a valid result
        $this->assertNotNull($coupons);
    }

    /** @test */
    public function can_use_coupons_works_with_preset()
    {
        // Create a user
        $user = User::factory()->create();

        // Create a preset
        $preset = Preset::factory()->create([
            'user_id' => $user->id,
        ]);

        // Test with preset
        $coupons = $user->canUseCoupons($preset);

        // Assert that the method returns a valid result
        $this->assertNotNull($coupons);
    }
}