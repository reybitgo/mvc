<?php

// Load environment variables from .env file
function loadEnvConfig($envFile = __DIR__ . '/../.env') {
    if (!file_exists($envFile)) {
        return;
    }

    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        // Parse key=value pairs
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);

            // Remove quotes if present
            if (preg_match('/^(["\'])(.*)\1$/', $value, $matches)) {
                $value = $matches[2];
            }

            // Convert boolean strings
            if (strtolower($value) === 'true') {
                $value = true;
            } elseif (strtolower($value) === 'false') {
                $value = false;
            }

            $_ENV[$key] = $value;
            putenv("$key=$value");
        }
    }
}

// Load environment configuration
loadEnvConfig();

// Application configuration
return [
    'app' => [
        'env' => $_ENV['APP_ENV'] ?? 'production',
        'debug' => $_ENV['APP_DEBUG'] ?? false,
        'url' => $_ENV['APP_URL'] ?? 'http://localhost',
    ],

    'session' => [
        'secure' => $_ENV['SESSION_SECURE'] ?? true,
        'httponly' => $_ENV['SESSION_HTTPONLY'] ?? true,
        'samesite' => $_ENV['SESSION_SAMESITE'] ?? 'Strict',
        'lifetime' => $_ENV['SESSION_LIFETIME'] ?? 86400,
    ],

    'security' => [
        'display_errors' => $_ENV['DISPLAY_ERRORS'] ?? false,
        'log_level' => $_ENV['LOG_LEVEL'] ?? 'error',
    ]
];