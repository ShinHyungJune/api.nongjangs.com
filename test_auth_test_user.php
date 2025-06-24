<?php

require 'vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Get the test user
$user = \App\Models\User::where('email', 'test@naver.com')->first();

if (!$user) {
    echo "Test user not found. Creating a test user...\n";
    $user = \App\Models\User::factory()->create([
        'email' => 'test@naver.com',
        'password' => \Illuminate\Support\Facades\Hash::make('password'),
    ]);
    echo "Created test user with ID: {$user->id}\n";
}

// Create a test package purchase using InitSeeder
echo "Creating test package purchase using InitSeeder...\n";
$seeder = new \Database\Seeders\InitSeeder();

// Use reflection to set the protected property
$reflectionClass = new ReflectionClass($seeder);
$reflectionProperty = $reflectionClass->getProperty('user');
$reflectionProperty->setAccessible(true);
$reflectionProperty->setValue($seeder, $user);

// Call the createTestPackagePurchase method
$reflectionMethod = $reflectionClass->getMethod('createTestPackagePurchase');
$reflectionMethod->setAccessible(true);
$reflectionMethod->invoke($seeder);

// Check if $user->getCurrentPackagePresetProduct() returns a result
$presetProduct = $user->getCurrentPackagePresetProduct();

echo "Direct call to user->getCurrentPackagePresetProduct(): " . ($presetProduct ? "Found (ID: {$presetProduct->id})" : "Not found") . "\n";

// Log in as the test user
\Illuminate\Support\Facades\Auth::login($user);

// Check if auth()->user() returns the correct user
$authUser = auth()->user();
echo "auth()->user(): " . ($authUser ? "Found (ID: {$authUser->id}, Email: {$authUser->email})" : "Not found") . "\n";

// Check if auth()->user()->getCurrentPackagePresetProduct() returns a result
$authPresetProduct = auth()->user()->getCurrentPackagePresetProduct();

echo "auth()->user()->getCurrentPackagePresetProduct(): " . ($authPresetProduct ? "Found (ID: {$authPresetProduct->id})" : "Not found") . "\n";

// If a PresetProduct is returned, print its details
if ($authPresetProduct) {
    echo "PresetProduct details:\n";
    echo "  ID: {$authPresetProduct->id}\n";
    echo "  Package ID: {$authPresetProduct->package_id}\n";
    echo "  State: {$authPresetProduct->state}\n";
    echo "  Package Type: {$authPresetProduct->package_type}\n";
    
    // Check if materials are attached
    $materials = $authPresetProduct->materials;
    echo "  Materials count: " . count($materials) . "\n";
}

echo "Test completed.\n";