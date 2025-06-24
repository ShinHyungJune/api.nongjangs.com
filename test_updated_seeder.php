<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Check if test@naver.com user exists
$user = \App\Models\User::where('email', 'test@naver.com')->first();

if (!$user) {
    echo "Test user not found. Creating a test user...\n";
    $user = \App\Models\User::factory()->create([
        'email' => 'test@naver.com',
        'password' => \Illuminate\Support\Facades\Hash::make('password'),
    ]);
    echo "Created test user with ID: {$user->id}\n";
}

// Run the seeder
echo "Running InitSeeder...\n";
$seeder = new \Database\Seeders\InitSeeder();
$seeder->run();

// Check if getCurrentPackagePresetProduct returns a result
$presetProduct = $user->getCurrentPackagePresetProduct();

if ($presetProduct) {
    echo "Success! getCurrentPackagePresetProduct returned a result:\n";
    echo "PresetProduct ID: " . $presetProduct->id . "\n";
    echo "Package ID: " . $presetProduct->package_id . "\n";
    echo "State: " . $presetProduct->state . "\n";
    echo "Package Type: " . $presetProduct->package_type . "\n";
    
    // Check if materials are attached
    $materials = $presetProduct->materials;
    echo "Materials count: " . count($materials) . "\n";
} else {
    echo "Error: getCurrentPackagePresetProduct returned null\n";
}

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