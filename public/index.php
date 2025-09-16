<?php

// C:\laragon\www\mvc\public\index.php

// Turn on error reporting for development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Debug information (remove in production)
if (isset($_GET['debug'])) {
    echo "<h3>Debug Information:</h3>";
    echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
    echo "Request URI: " . $_SERVER['REQUEST_URI'] . "<br>";
    echo "Script Name: " . $_SERVER['SCRIPT_NAME'] . "<br>";
    echo "Current Directory: " . __DIR__ . "<br>";
    echo "Autoload file exists: " . (file_exists(__DIR__ . '/../vendor/autoload.php') ? 'Yes' : 'No') . "<br>";
    echo "<hr>";
}

// Start session
session_start();

// Require composer autoloader
require __DIR__ . '/../vendor/autoload.php';

use Gawis\MVC\Controllers\UserController;
use Gawis\MVC\Controllers\ProfileController;

// Get the requested URL path
$requestUri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

// Handle both GET and POST requests
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Simple routing mechanism
try {
    switch ($requestUri) {
        case '':
        case 'home':
            // Homepage
            showHomepage();
            break;

        case 'register':
            $controller = new UserController();
            if ($requestMethod === 'GET') {
                $controller->register();
            } elseif ($requestMethod === 'POST') {
                $controller->handleRegister();
            }
            break;

        case 'login':
            $controller = new UserController();
            if ($requestMethod === 'GET') {
                $controller->login();
            } elseif ($requestMethod === 'POST') {
                $controller->handleLogin();
            }
            break;

        case 'dashboard':
            $controller = new UserController();
            $controller->dashboard();
            break;

        case 'profile':
            $controller = new UserController();
            $controller->profile();
            break;

        case 'profile/update':
            $controller = new ProfileController();
            $controller->update();
            break;

        case 'logout':
            $controller = new UserController();
            $controller->logout();
            break;

        case 'profile':
            $controller = new UserController();
            $controller->profile();
            break;

        default:
            // 404 Not Found
            http_response_code(404);
            show404();
            break;
    }
} catch (Exception $e) {
    // Log error and show generic error page
    error_log("Application Error: " . $e->getMessage());
    http_response_code(500);
    showError("An error occurred. Please try again later.");
}

/**
 * Display the homepage
 */
function showHomepage()
{
    $isLoggedIn = isset($_SESSION['user_id']);
    $username = $_SESSION['username'] ?? '';

?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>MVC Login System</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                max-width: 800px;
                margin: 50px auto;
                padding: 20px;
                background-color: #f5f5f5;
                line-height: 1.6;
            }

            .container {
                background: white;
                padding: 40px;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                text-align: center;
            }

            .hero {
                margin-bottom: 30px;
            }

            .hero h1 {
                color: #333;
                font-size: 2.5em;
                margin-bottom: 10px;
            }

            .hero p {
                color: #666;
                font-size: 1.2em;
            }

            .nav-links {
                margin: 30px 0;
            }

            .nav-links a,
            .btn {
                display: inline-block;
                background-color: #007bff;
                color: white;
                padding: 12px 24px;
                text-decoration: none;
                border-radius: 5px;
                margin: 0 10px;
                font-weight: bold;
                transition: background-color 0.3s;
            }

            .nav-links a:hover,
            .btn:hover {
                background-color: #0056b3;
            }

            .btn-secondary {
                background-color: #6c757d;
            }

            .btn-secondary:hover {
                background-color: #545b62;
            }

            .btn-danger {
                background-color: #dc3545;
            }

            .btn-danger:hover {
                background-color: #c82333;
            }

            .features {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 20px;
                margin-top: 40px;
            }

            .feature {
                background: #f8f9fa;
                padding: 20px;
                border-radius: 5px;
                border-left: 4px solid #007bff;
            }

            .feature h3 {
                margin-top: 0;
                color: #333;
            }

            .user-info {
                background: #d4edda;
                padding: 15px;
                border-radius: 5px;
                margin-bottom: 20px;
                border-left: 4px solid #28a745;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <div class="hero">
                <h1>🚀 MVC Login System</h1>
                <p>A simple and secure user authentication system built with PHP MVC architecture</p>
            </div>

            <?php if ($isLoggedIn): ?>
                <div class="user-info">
                    <h3>Welcome back, <?php echo htmlspecialchars($username); ?>! 👋</h3>
                    <p>You are currently logged in to the system.</p>
                </div>

                <div class="nav-links">
                    <a href="/dashboard" class="btn">📊 Dashboard</a>
                    <a href="/profile" class="btn btn-secondary">👤 Profile</a>
                    <a href="/logout" class="btn btn-danger">🚪 Logout</a>
                </div>
            <?php else: ?>
                <div class="nav-links">
                    <a href="/register">📝 Register</a>
                    <a href="/login">🔐 Login</a>
                </div>
            <?php endif; ?>

            <div class="features">
                <div class="feature">
                    <h3>🔒 Secure Authentication</h3>
                    <p>Password hashing and secure session management to protect user accounts.</p>
                </div>
                <div class="feature">
                    <h3>📱 Responsive Design</h3>
                    <p>Clean and modern interface that works on all devices and screen sizes.</p>
                </div>
                <div class="feature">
                    <h3>⚡ MVC Architecture</h3>
                    <p>Well-organized codebase following Model-View-Controller design pattern.</p>
                </div>
                <div class="feature">
                    <h3>✅ Form Validation</h3>
                    <p>Client and server-side validation to ensure data integrity and user experience.</p>
                </div>
            </div>

            <footer style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee; color: #666;">
                <p>&copy; <?php echo date('Y'); ?> MVC Login System. Built with PHP & MySQL.</p>
            </footer>
        </div>
    </body>

    </html>
<?php
}

/**
 * Display 404 error page
 */
function show404()
{
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>404 - Page Not Found</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                max-width: 600px;
                margin: 50px auto;
                padding: 20px;
                background-color: #f5f5f5;
                text-align: center;
            }

            .container {
                background: white;
                padding: 40px;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }

            .error-code {
                font-size: 6em;
                font-weight: bold;
                color: #dc3545;
                margin: 0;
            }

            .error-message {
                font-size: 1.5em;
                color: #666;
                margin: 20px 0;
            }

            .back-link {
                display: inline-block;
                background-color: #007bff;
                color: white;
                padding: 12px 24px;
                text-decoration: none;
                border-radius: 5px;
                margin-top: 20px;
            }

            .back-link:hover {
                background-color: #0056b3;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <div class="error-code">404</div>
            <div class="error-message">Oops! Page not found</div>
            <p>The page you're looking for doesn't exist or has been moved.</p>
            <a href="/" class="back-link">🏠 Go Back Home</a>
        </div>
    </body>

    </html>
<?php
}

/**
 * Display generic error page
 */
function showError($message = "An unexpected error occurred")
{
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Error</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                max-width: 600px;
                margin: 50px auto;
                padding: 20px;
                background-color: #f5f5f5;
                text-align: center;
            }

            .container {
                background: white;
                padding: 40px;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }

            .error-icon {
                font-size: 4em;
                color: #dc3545;
                margin-bottom: 20px;
            }

            .error-message {
                font-size: 1.2em;
                color: #666;
                margin: 20px 0;
            }

            .back-link {
                display: inline-block;
                background-color: #007bff;
                color: white;
                padding: 12px 24px;
                text-decoration: none;
                border-radius: 5px;
                margin-top: 20px;
            }

            .back-link:hover {
                background-color: #0056b3;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <div class="error-icon">⚠️</div>
            <h2>Something went wrong</h2>
            <div class="error-message"><?php echo htmlspecialchars($message); ?></div>
            <a href="/" class="back-link">🏠 Go Back Home</a>
        </div>
    </body>

    </html>
<?php
}
