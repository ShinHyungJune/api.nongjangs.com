<?php

require 'vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Check existing packages
$packages = \App\Models\Package::orderBy('id')->get();

echo "Existing packages:\n";
foreach ($packages as $package) {
    echo "  ID: {$package->id}, Count: {$package->count}, start_pack_wait_at: {$package->start_pack_wait_at}, will_delivery_at: {$package->will_delivery_at}\n";
}

// Check which package is returned by getOngoing()
$ongoingPackage = \App\Models\Package::getOngoing();
echo "\nPackage::getOngoing(): " . ($ongoingPackage ? "Found (ID: {$ongoingPackage->id}, Count: {$ongoingPackage->count})" : "Not found") . "\n";

// Check if there are any preset products for the test user
$testUser = \App\Models\User::where('email', 'test@naver.com')->first();

if ($testUser) {
    echo "\nPreset products for test user (ID: {$testUser->id}):\n";
    $presetProducts = $testUser->presetProducts()->orderBy('id')->get();
    
    foreach ($presetProducts as $presetProduct) {
        echo "  ID: {$presetProduct->id}, Package ID: {$presetProduct->package_id}, State: {$presetProduct->state}\n";
    }
} else {
    echo "\nTest user not found.\n";
}

echo "\nTest completed.\n";