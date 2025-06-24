<?php

require 'vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Find the test@naver.com user
$user = \App\Models\User::where('email', 'test@naver.com')->first();

if (!$user) {
    echo "User test@naver.com not found\n";
    exit(1);
}

// Check if the user has an active PackageSetting
$packageSetting = $user->packageSetting;
echo "PackageSetting: " . ($packageSetting ? "Found (active: {$packageSetting->active})" : "Not found") . "\n";

// Check if there's an ongoing package
$ongoingPackage = \App\Models\Package::getOngoing();
echo "Ongoing Package: " . ($ongoingPackage ? "Found (ID: {$ongoingPackage->id}, count: {$ongoingPackage->count})" : "Not found") . "\n";

// Check if the user has a PresetProduct for the ongoing package
if ($ongoingPackage) {
    $presetProduct = $user->presetProducts()->where('package_id', $ongoingPackage->id)->first();
    echo "PresetProduct for ongoing package: " . ($presetProduct ? "Found (ID: {$presetProduct->id})" : "Not found") . "\n";
}

// Check if the user's getCurrentPackagePresetProduct method returns a PresetProduct
$currentPackagePresetProduct = $user->getCurrentPackagePresetProduct();
echo "getCurrentPackagePresetProduct: " . ($currentPackagePresetProduct ? "Found (ID: {$currentPackagePresetProduct->id})" : "Not found") . "\n";

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