<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

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

echo "Test completed.\n";