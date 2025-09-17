<?php
/**
 * Database Reset Script
 *
 * This script resets the database by:
 * 1. Dropping and recreating the users table
 * 2. Creating admin and member users with secure passwords
 * 3. Setting up proper roles and permissions
 *
 * Usage: php reset.php or visit http://mvc.test/reset.php
 *
 * SECURITY WARNING: This script should be removed or protected in production!
 */

// Load configuration
require_once __DIR__ . '/config/app.php';

// Only allow reset in development environment
if (($_ENV['APP_ENV'] ?? 'production') !== 'development') {
    die("❌ Reset script is only available in development environment.\n");
}

// Check if running from command line or web
$isCommandLine = (php_sapi_name() === 'cli');

if (!$isCommandLine) {
    // Web interface
    echo "<!DOCTYPE html><html><head><title>Database Reset</title>";
    echo "<style>body{font-family:Arial,sans-serif;max-width:800px;margin:50px auto;padding:20px;}</style>";
    echo "</head><body>";
    echo "<h1>🔄 Database Reset Script</h1>";
}

/**
 * Output message (works for both CLI and web)
 */
function output($message, $isError = false) {
    global $isCommandLine;

    if ($isCommandLine) {
        echo $message . "\n";
    } else {
        $color = $isError ? '#e74c3c' : '#27ae60';
        echo "<p style='color: $color;'>$message</p>";
    }
}

/**
 * Generate secure password hash
 */
function generatePasswordHash($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

try {
    output("🚀 Starting database reset...");

    // Load database configuration
    $dbConfig = require __DIR__ . '/config/database.php';

    // Create PDO connection
    $dsn = "mysql:host={$dbConfig['host']};charset={$dbConfig['charset']}";
    $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], $dbConfig['options'] ?? []);

    output("✅ Connected to MySQL server");

    // Create database if it doesn't exist
    $dbName = $dbConfig['dbname'];
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName`");
    $pdo->exec("USE `$dbName`");

    output("✅ Database '$dbName' ready");

    // Read and execute SQL file (using approach from dump/reset.php)
    $sqlFile = __DIR__ . '/db.sql';
    if (!file_exists($sqlFile)) {
        throw new Exception("SQL file not found: $sqlFile");
    }

    $sql = file_get_contents($sqlFile);
    if ($sql === false) {
        throw new Exception("Failed to read db.sql");
    }

    output("📄 Reading clean db.sql...");
    output("🗄️  Executing database schema...");

    // Execute the entire SQL file at once (like dump/reset.php does)
    $pdo->exec($sql);

    output("✅ Database schema created successfully");

    // Switch to the database (ensure we're using the correct one)
    $pdo->exec("USE `$dbName`");

    // Generate fresh password hashes for default users
    $adminPassword = 'AdminPass123!';
    $memberPassword = 'MemberPass123!';

    $adminHash = generatePasswordHash($adminPassword);
    $memberHash = generatePasswordHash($memberPassword);

    output("🔐 Generated secure password hashes");

    // Update admin user with fresh hash
    $stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE username = 'admin'");
    $stmt->execute([$adminHash]);

    // Update member user with fresh hash
    $stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE username = 'member'");
    $stmt->execute([$memberHash]);

    output("✅ Updated user passwords with fresh hashes");

    // Verify users were created
    $stmt = $pdo->query("SELECT id, username, email, role, is_active, created_at FROM users ORDER BY role DESC, id ASC");
    $users = $stmt->fetchAll();

    output("👥 Created users:");
    foreach ($users as $user) {
        $status = $user['is_active'] ? 'Active' : 'Inactive';
        $role = ucfirst($user['role']);
        output("   • {$user['username']} ({$user['email']}) - $role - $status");
    }

    // Create logs directory if it doesn't exist
    $logsDir = __DIR__ . '/logs';
    if (!file_exists($logsDir)) {
        mkdir($logsDir, 0755, true);
        output("📁 Created logs directory");
    }

    // Create logs .htaccess if it doesn't exist
    $logsHtaccess = $logsDir . '/.htaccess';
    if (!file_exists($logsHtaccess)) {
        file_put_contents($logsHtaccess, "# Deny access to log files\nRequire all denied");
        output("🔒 Protected logs directory");
    }

    // Clear any existing security logs and start fresh
    $securityLog = $logsDir . '/security.log';
    file_put_contents($securityLog, "# Security Event Log - Reset on " . date('Y-m-d H:i:s') . "\n");
    output("📝 Initialized security log");

    output("");
    output("🎉 Database reset completed successfully!");
    output("");
    output("📋 Default Accounts Created:");
    output("   👑 Admin: admin / AdminPass123!");
    output("   👤 Member: member / MemberPass123!");
    output("");
    output("🔗 You can now test:");
    output("   • Login: http://mvc.test/login");
    output("   • Register: http://mvc.test/register");
    output("   • Security Test: http://mvc.test/security-test");
    output("");

    if (!$isCommandLine) {
        echo "<h3>🧪 Quick Actions</h3>";
        echo "<p><a href='/login' style='background:#007bff;color:white;padding:8px 16px;text-decoration:none;border-radius:4px;'>Login as Admin</a></p>";
        echo "<p><a href='/register' style='background:#28a745;color:white;padding:8px 16px;text-decoration:none;border-radius:4px;'>Register New User</a></p>";
        echo "<p><a href='/security-test' style='background:#6c757d;color:white;padding:8px 16px;text-decoration:none;border-radius:4px;'>Run Security Test</a></p>";
        echo "<p><a href='/' style='background:#17a2b8;color:white;padding:8px 16px;text-decoration:none;border-radius:4px;'>← Back to Home</a></p>";
    }

} catch (Exception $e) {
    output("❌ Error: " . $e->getMessage(), true);

    if (!$isCommandLine) {
        echo "<h3>Troubleshooting</h3>";
        echo "<ul>";
        echo "<li>Make sure MySQL is running in Laragon</li>";
        echo "<li>Check database credentials in config/database.php</li>";
        echo "<li>Ensure APP_ENV=development in .env file</li>";
        echo "</ul>";
    }

    exit(1);
}

if (!$isCommandLine) {
    echo "</body></html>";
}
?>