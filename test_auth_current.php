<?php

require 'vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Check current time and timezone
echo "Current time: " . \Carbon\Carbon::now() . "\n";
echo "Current timezone: " . date_default_timezone_get() . "\n\n";

// Check existing packages
$packages = \App\Models\Package::orderBy('id')->get();

echo "Existing packages:\n";
foreach ($packages as $package) {
    echo "  ID: {$package->id}, Count: {$package->count}, start_pack_wait_at: {$package->start_pack_wait_at}, will_delivery_at: {$package->will_delivery_at}\n";
    
    // Check if this package would be returned by getOngoing()
    $startPackWaitCondition = $package->start_pack_wait_at <= \Carbon\Carbon::now();
    $willDeliveryCondition = $package->will_delivery_at >= \Carbon\Carbon::now()->subDay()->startOfDay();
    
    echo "    start_pack_wait_at <= now: " . ($startPackWaitCondition ? "true" : "false") . "\n";
    echo "    will_delivery_at >= now->subDay()->startOfDay(): " . ($willDeliveryCondition ? "true" : "false") . "\n";
    echo "    Would be returned by getOngoing(): " . (($startPackWaitCondition && $willDeliveryCondition) ? "true" : "false") . "\n";
}

// Check which package is returned by getOngoing()
$ongoingPackage = \App\Models\Package::getOngoing();
echo "\nPackage::getOngoing(): " . ($ongoingPackage ? "Found (ID: {$ongoingPackage->id}, Count: {$ongoingPackage->count})" : "Not found") . "\n\n";

// Create a test user
$user = \App\Models\User::factory()->create([
    'email' => 'test_auth_current@example.com',
    'password' => \Illuminate\Support\Facades\Hash::make('password'),
]);

echo "Created test user with ID: {$user->id}\n";

// Create a package setting for the user
$packageSetting = \App\Models\PackageSetting::factory()->create([
    'user_id' => $user->id,
    'active' => 1,
]);

echo "Created package setting with ID: {$packageSetting->id}\n";

// Create a new package that should be returned by getOngoing()
$newPackage = \App\Models\Package::factory()->create([
    'start_pack_wait_at' => \Carbon\Carbon::now()->subDays(2), // Definitely in the past
    'will_delivery_at' => \Carbon\Carbon::now()->addDays(7),   // Definitely in the future
    'count' => 0, // Set a very low count to ensure it's returned by getOngoing()
]);

echo "Created new package with ID: {$newPackage->id}\n";

// Check if this new package would be returned by getOngoing()
$startPackWaitCondition = $newPackage->start_pack_wait_at <= \Carbon\Carbon::now();
$willDeliveryCondition = $newPackage->will_delivery_at >= \Carbon\Carbon::now()->subDay()->startOfDay();

echo "  start_pack_wait_at <= now: " . ($startPackWaitCondition ? "true" : "false") . "\n";
echo "  will_delivery_at >= now->subDay()->startOfDay(): " . ($willDeliveryCondition ? "true" : "false") . "\n";
echo "  Would be returned by getOngoing(): " . (($startPackWaitCondition && $willDeliveryCondition) ? "true" : "false") . "\n";

// Check which package is returned by getOngoing() now
$ongoingPackage = \App\Models\Package::getOngoing();
echo "\nPackage::getOngoing() after creating new package: " . ($ongoingPackage ? "Found (ID: {$ongoingPackage->id}, Count: {$ongoingPackage->count})" : "Not found") . "\n";

// Create a preset for the user
$preset = \App\Models\Preset::factory()->create([
    'user_id' => $user->id,
]);

echo "Created preset with ID: {$preset->id}\n";

// Create a preset product for the user
$presetProduct = \App\Models\PresetProduct::factory()->create([
    'preset_id' => $preset->id,
    'package_id' => $newPackage->id,
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
$newPackage->delete();
$packageSetting->delete();
$user->delete();

echo "\nTest completed and cleaned up.\n";