<?php

require 'vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Get the test@naver.com user
$user = \App\Models\User::where('email', 'test@naver.com')->first();

if (!$user) {
    echo "User test@naver.com not found\n";
    exit(1);
}

// Set the authenticated user
\Illuminate\Support\Facades\Auth::login($user);

// Check if auth()->user() returns the correct user
$authUser = auth()->user();
echo "auth()->user(): " . ($authUser ? "Found (ID: {$authUser->id})" : "Not found") . "\n";

// Check if auth()->user()->getCurrentPackagePresetProduct() returns a PresetProduct
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