<?php
// Simple redirect to security test
// Access via: http://mvc.test/security-test.php

try {
    // Load and run the security test
    require_once __DIR__ . '/public/test_security.php';
} catch (Exception $e) {
    echo "<h2>❌ Security Test Error</h2>";
    echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>File: " . htmlspecialchars($e->getFile()) . "</p>";
    echo "<p>Line: " . $e->getLine() . "</p>";
}
?>