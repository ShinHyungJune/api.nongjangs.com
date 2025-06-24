<?php

require 'vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Create a test user
$user = \App\Models\User::factory()->create([
    'email' => 'test_auth_user_current@example.com',
    'password' => \Illuminate\Support\Facades\Hash::make('password'),
]);

echo "Created test user with ID: {$user->id}\n";

// Create a package setting for the user
$packageSetting = \App\Models\PackageSetting::factory()->create([
    'user_id' => $user->id,
    'active' => 1,
]);

echo "Created package setting with ID: {$packageSetting->id}\n";

// Create an ongoing package with a very low count to ensure it's returned by getOngoing()
$ongoingPackage = \App\Models\Package::factory()->create([
    'start_pack_wait_at' => \Carbon\Carbon::now()->subDay(),
    'will_delivery_at' => \Carbon\Carbon::now()->addDays(7),
    'count' => 1, // Set a very low count
]);

echo "Created ongoing package with ID: {$ongoingPackage->id}\n";

// Verify that this package is returned by getOngoing()
$ongoingPackageFromMethod = \App\Models\Package::getOngoing();
echo "Package::getOngoing(): " . ($ongoingPackageFromMethod ? "Found (ID: {$ongoingPackageFromMethod->id})" : "Not found") . "\n";

// Create a preset for the user
$preset = \App\Models\Preset::factory()->create([
    'user_id' => $user->id,
]);

echo "Created preset with ID: {$preset->id}\n";

// Create a preset product for the user
$presetProduct = \App\Models\PresetProduct::factory()->create([
    'preset_id' => $preset->id,
    'package_id' => $ongoingPackage->id,
    'state' => \App\Enums\StatePresetProduct::WAIT,
]);

echo "Created preset product with ID: {$presetProduct->id}\n";

// Log in as the test user
\Illuminate\Support\Facades\Auth::login($user);

// Test auth()->user()->getCurrentPackagePresetProduct()
$currentPackagePresetProduct = auth()->user()->getCurrentPackagePresetProduct();

echo "auth()->user()->getCurrentPackagePresetProduct(): " . ($currentPackagePresetProduct ? "Found (ID: {$currentPackagePresetProduct->id})" : "Not found") . "\n";

// If a PresetProduct is returned, print its details
if ($currentPackagePresetProduct) {
    echo "PresetProduct details:\n";
    echo "  ID: {$currentPackagePresetProduct->id}\n";
    echo "  Package ID: {$currentPackagePresetProduct->package_id}\n";
    echo "  State: {$currentPackagePresetProduct->state}\n";
    echo "  Package active: {$currentPackagePresetProduct->package_active}\n";
    echo "  Package name: {$currentPackagePresetProduct->package_name}\n";
    echo "  Package count: {$currentPackagePresetProduct->package_count}\n";
    echo "  Package will delivery at: {$currentPackagePresetProduct->package_will_delivery_at}\n";
}

// Clean up
$presetProduct->delete();
$preset->delete();
$ongoingPackage->delete();
$packageSetting->delete();
$user->delete();

echo "Test completed and cleaned up.\n";