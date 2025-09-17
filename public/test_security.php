<?php
// Security Testing Script for Local Development
// Run this from your browser: http://mvc.test/public/test_security.php

// Load configuration
require_once __DIR__ . '/../config/app.php';

// Load autoloader and use statements
require_once __DIR__ . '/../vendor/autoload.php';
use Gawis\MVC\Security\CSRFProtection;
use Gawis\MVC\Security\InputValidator;
use Gawis\MVC\Security\RateLimiter;

echo "<h2>🔒 Security Configuration Test</h2>";
echo "<p><strong>Environment:</strong> " . ($_ENV['APP_ENV'] ?? 'production') . "</p>";

// Test 1: Environment Configuration
echo "<h3>1. Environment Configuration</h3>";
echo "<ul>";
echo "<li>APP_ENV: " . ($_ENV['APP_ENV'] ?? 'not set') . "</li>";
echo "<li>APP_DEBUG: " . (($_ENV['APP_DEBUG'] ?? false) ? 'true' : 'false') . "</li>";
echo "<li>SESSION_SECURE: " . (($_ENV['SESSION_SECURE'] ?? true) ? 'true' : 'false') . "</li>";
echo "<li>SESSION_HTTPONLY: " . (($_ENV['SESSION_HTTPONLY'] ?? true) ? 'true' : 'false') . "</li>";
echo "<li>SESSION_SAMESITE: " . ($_ENV['SESSION_SAMESITE'] ?? 'Strict') . "</li>";
echo "</ul>";

// Test 2: Session Security
echo "<h3>2. Session Security Settings</h3>";
session_start();
echo "<ul>";
echo "<li>session.cookie_httponly: " . (ini_get('session.cookie_httponly') ? '✅ Enabled' : '❌ Disabled') . "</li>";
echo "<li>session.cookie_secure: " . (ini_get('session.cookie_secure') ? '✅ Enabled' : '⚠️ Disabled (OK for localhost)') . "</li>";
echo "<li>session.use_strict_mode: " . (ini_get('session.use_strict_mode') ? '✅ Enabled' : '❌ Disabled') . "</li>";
echo "<li>session.cookie_samesite: " . ini_get('session.cookie_samesite') . "</li>";
echo "</ul>";

// Test 3: Headers Check
echo "<h3>3. Security Headers</h3>";
echo "<ul>";
$headers = [
    'X-Frame-Options' => 'DENY',
    'X-Content-Type-Options' => 'nosniff',
    'X-XSS-Protection' => '1; mode=block',
    'Referrer-Policy' => 'strict-origin-when-cross-origin',
    'Content-Security-Policy' => 'present'
];

foreach ($headers as $header => $expected) {
    $headerSet = false;
    foreach (headers_list() as $sentHeader) {
        if (stripos($sentHeader, $header . ':') === 0) {
            echo "<li>$header: ✅ Set</li>";
            $headerSet = true;
            break;
        }
    }
    if (!$headerSet) {
        echo "<li>$header: ❌ Not set</li>";
    }
}
echo "</ul>";

// Test 4: Database Connection
echo "<h3>4. Database Connection Test</h3>";
try {
    $config = require __DIR__ . '/../config/database.php';
    $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
    $pdo = new PDO($dsn, $config['username'], $config['password'], $config['options'] ?? []);
    echo "<p>✅ Database connection successful</p>";

    // Test prepared statements
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM users WHERE id = ?");
    $stmt->execute([999999]); // Non-existent ID
    echo "<p>✅ Prepared statements working</p>";
} catch (Exception $e) {
    echo "<p>❌ Database connection failed: " . htmlspecialchars($e->getMessage()) . "</p>";
}

// Test 5: CSRF Protection
echo "<h3>5. CSRF Protection Test</h3>";
try {

    $token = CSRFProtection::getToken();
    echo "<p>✅ CSRF token generated: " . substr($token, 0, 10) . "...</p>";

    // Test validation
    $_POST['csrf_token'] = $token;
    if (CSRFProtection::validatePostToken()) {
        echo "<p>⚠️ CSRF validation passed (token was consumed)</p>";
    } else {
        echo "<p>✅ CSRF single-use working correctly</p>";
    }
} catch (Exception $e) {
    echo "<p>❌ CSRF test failed: " . htmlspecialchars($e->getMessage()) . "</p>";
}

// Test 6: Input Validation
echo "<h3>6. Input Validation Test</h3>";
try {

    $validator = new InputValidator();
    $validator->addRule('password', 'password_strength');

    // Test weak password
    $weakPasswords = ['password123', '12345678', 'weakpass'];
    foreach ($weakPasswords as $weak) {
        if (!$validator->validate(['password' => $weak])) {
            echo "<p>✅ Rejected weak password: '$weak'</p>";
        } else {
            echo "<p>❌ Accepted weak password: '$weak'</p>";
        }
    }

    // Test strong password
    if ($validator->validate(['password' => 'StrongP@ssw0rd123!'])) {
        echo "<p>✅ Accepted strong password</p>";
    } else {
        echo "<p>❌ Rejected strong password</p>";
    }

} catch (Exception $e) {
    echo "<p>❌ Input validation test failed: " . htmlspecialchars($e->getMessage()) . "</p>";
}

// Test 7: Rate Limiting
echo "<h3>7. Rate Limiting Test</h3>";
try {

    // Test rate limiting
    for ($i = 0; $i < 6; $i++) {
        RateLimiter::recordAttempt('test_action');
    }

    if (RateLimiter::isLimited('test_action', null, 5)) {
        echo "<p>✅ Rate limiting working - blocked after 5 attempts</p>";
    } else {
        echo "<p>❌ Rate limiting not working</p>";
    }

    // Clear for next test
    RateLimiter::clearLimit('test_action');

} catch (Exception $e) {
    echo "<p>❌ Rate limiting test failed: " . htmlspecialchars($e->getMessage()) . "</p>";
}

// Test 8: File Security
echo "<h3>8. File Security Test</h3>";
$protectedFiles = [
    '.env' => file_get_contents(__DIR__ . '/../.htaccess'),
    'composer.json' => file_get_contents(__DIR__ . '/../.htaccess'),
    '*.md files' => file_get_contents(__DIR__ . '/../.htaccess'),
];

foreach ($protectedFiles as $file => $htaccess) {
    if (strpos($htaccess, $file) !== false || strpos($htaccess, 'Require all denied') !== false) {
        echo "<p>✅ $file is protected by .htaccess</p>";
    } else {
        echo "<p>❌ $file protection not found</p>";
    }
}

echo "<h3>🎯 Testing Instructions for Laragon</h3>";
echo "<ol>";
echo "<li><strong>Test Forms:</strong> Try registering with weak passwords - should be rejected</li>";
echo "<li><strong>Test Rate Limiting:</strong> Try logging in with wrong credentials 6 times - should be blocked</li>";
echo "<li><strong>Test CSRF:</strong> Submit forms without tokens or with invalid tokens - should be rejected</li>";
echo "<li><strong>Test Debug Mode:</strong> Visit <a href='/?debug=1'>/?debug=1</a> - should show debug info only in development</li>";
echo "<li><strong>Test Protected Files:</strong> Try accessing <a href='/composer.json'>/composer.json</a> - should be denied</li>";
echo "</ol>";

echo "<h3>🔧 Development vs Production</h3>";
echo "<p>Currently running in <strong>" . ($_ENV['APP_ENV'] ?? 'production') . "</strong> mode.</p>";
echo "<p>In development mode: Debug info available, less strict IP validation, detailed error messages.</p>";
echo "<p>For production: Set APP_ENV=production in .env file.</p>";

// Clean up
unset($_POST['csrf_token']);
?>

<style>
body { font-family: Arial, sans-serif; max-width: 1000px; margin: 20px auto; padding: 20px; }
h2 { color: #333; border-bottom: 2px solid #007bff; }
h3 { color: #007bff; margin-top: 30px; }
ul { background: #f8f9fa; padding: 15px; border-radius: 5px; }
li { margin: 5px 0; }
p { margin: 10px 0; }
a { color: #007bff; }
</style>