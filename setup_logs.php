<?php
// Create this file as: C:\laragon\www\mvc\setup_logs.php
// Run once to create the logs directory and set proper permissions

$logsDir = __DIR__ . '/logs';

if (!file_exists($logsDir)) {
    mkdir($logsDir, 0755, true);
    echo "Created logs directory: $logsDir\n";
} else {
    echo "Logs directory already exists: $logsDir\n";
}

// Create .htaccess to protect log files
$htaccessContent = "# Deny access to log files\nRequire all denied";
file_put_contents($logsDir . '/.htaccess', $htaccessContent);

// Create initial security.log file
$securityLogFile = $logsDir . '/security.log';
if (!file_exists($securityLogFile)) {
    file_put_contents($securityLogFile, "# Security Event Log - Started " . date('Y-m-d H:i:s') . "\n");
    echo "Created security.log file\n";
}

echo "Logs setup complete!\n";
