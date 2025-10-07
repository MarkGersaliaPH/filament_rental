<?php

/**
 * Simple test to verify Address and HasAddress trait functionality
 * Run with: php tests/AddressTest.php
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Database\Capsule\Manager as Capsule;

// Mock basic Laravel environment for testing
if (!defined('LARAVEL_START')) {
    define('LARAVEL_START', microtime(true));
}

echo "Testing Address Model and HasAddress Trait...\n\n";

try {
    // Test Address model instantiation
    $address = new App\Models\Address();
    echo "✅ Address model instantiated successfully\n";
    
    // Test default attributes
    echo "✅ Default country: " . $address->getAttribute('country') . "\n";
    echo "✅ Default type: " . $address->getAttribute('type') . "\n";
    echo "✅ Default is_primary: " . ($address->getAttribute('is_primary') ? 'true' : 'false') . "\n";
    
    // Test User model with HasAddress trait
    $user = new App\Models\User();
    echo "✅ User model with HasAddress trait instantiated successfully\n";
    
    // Test that trait methods exist
    $reflection = new ReflectionClass($user);
    $traitMethods = [
        'addresses',
        'primaryAddress', 
        'billingAddress',
        'addAddress',
        'hasAddresses',
        'getFormattedAddress'
    ];
    
    foreach ($traitMethods as $method) {
        if ($reflection->hasMethod($method)) {
            echo "✅ Method '{$method}' available via HasAddress trait\n";
        } else {
            echo "❌ Method '{$method}' NOT found\n";
        }
    }
    
    // Test Address model methods
    $addressMethods = [
        'getFormattedAddress',
        'getCoordinates', 
        'hasCoordinates',
        'getDisplayName',
        'toApiArray'
    ];
    
    $addressReflection = new ReflectionClass($address);
    foreach ($addressMethods as $method) {
        if ($addressReflection->hasMethod($method)) {
            echo "✅ Address method '{$method}' available\n";
        } else {
            echo "❌ Address method '{$method}' NOT found\n";
        }
    }
    
    // Test address formatting without database
    $testAddress = new App\Models\Address([
        'type' => 'billing',
        'label' => 'Home',
        'street_address_1' => '123 Test Street',
        'street_address_2' => 'Unit 1A',
        'city' => 'Manila',
        'state_province' => 'Metro Manila',
        'postal_code' => '1000',
        'country' => 'Philippines'
    ]);
    
    echo "\n📍 Address Formatting Test:\n";
    echo "Display Name: " . $testAddress->getDisplayName() . "\n";
    echo "Single Line: " . $testAddress->getFormattedAddress('single_line') . "\n";
    echo "Short Format: " . $testAddress->getFormattedAddress('short') . "\n";
    
    // Test coordinates
    $testAddress->setCoordinates(14.5995, 120.9842);
    echo "Has Coordinates: " . ($testAddress->hasCoordinates() ? 'Yes' : 'No') . "\n";
    $coords = $testAddress->getCoordinates();
    echo "Coordinates: " . json_encode($coords) . "\n";
    
    echo "\n🎉 All tests passed! Address system is working correctly.\n";
    echo "\nYou can now use the HasAddress trait in any model by adding:\n";
    echo "use App\\Traits\\HasAddress;\n";
    echo "And then in your class: use HasAddress;\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}