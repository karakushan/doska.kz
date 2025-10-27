<?php
/**
 * Test file for xdebug debugging
 */

// Simple test variables
$test_var = "Hello from Xdebug!";
$numbers = [1, 2, 3, 4, 5];

// Function to test step debugging
function calculate_sum($array) {
    $sum = 0;
    foreach ($array as $num) {
        $sum += $num;
    }
    return $sum;
}

// Test calculation
$result = calculate_sum($numbers);

// Test output
echo "<h1>Xdebug Test</h1>";
echo "<p>Test variable: " . $test_var . "</p>";
echo "<p>Sum of numbers: " . $result . "</p>";
echo "<p>Xdebug is " . (extension_loaded('xdebug') ? 'ENABLED' : 'DISABLED') . "</p>";

// Display xdebug info
if (extension_loaded('xdebug')) {
    echo "<h2>Xdebug Configuration:</h2>";
    echo "<pre>";
    echo "Mode: " . ini_get('xdebug.mode') . "\n";
    echo "Client Host: " . ini_get('xdebug.client_host') . "\n";
    echo "Client Port: " . ini_get('xdebug.client_port') . "\n";
    echo "Start with Request: " . ini_get('xdebug.start_with_request') . "\n";
    echo "</pre>";
}

// Breakpoint test point - set breakpoint here
$final_message = "If you see this in the debugger, Xdebug is working!";
echo "<p>" . $final_message . "</p>";

