<?php

// C:\laragon\www\mvc\src\controllers\UserController.php

namespace Reybi\MVC\Controllers;

use Reybi\MVC\Models\User;
use Reybi\MVC\Security\CSRFProtection;
use Reybi\MVC\Security\InputValidator;
use Reybi\MVC\Security\InputSanitizer;
use Reybi\MVC\Security\RateLimiter;

class UserController extends Controller
{
    private const MAX_LOGIN_ATTEMPTS = 5;
    private const MAX_REGISTER_ATTEMPTS = 3;
    private const RATE_LIMIT_WINDOW = 900; // 15 minutes

    public function register()
    {
        // Check rate limiting
        if (RateLimiter::isLimited('register', null, self::MAX_REGISTER_ATTEMPTS, self::RATE_LIMIT_WINDOW)) {
            $limitInfo = RateLimiter::getLimitInfo('register', null, self::MAX_REGISTER_ATTEMPTS, self::RATE_LIMIT_WINDOW);
            $this->view('register', [
                'error' => 'Too many registration attempts. Please try again in ' . ceil($limitInfo['time_until_reset'] / 60) . ' minutes.',
                'rate_limited' => true,
                'csrf_token' => CSRFProtection::getToken() // Make sure this is here
            ]);
            return;
        }

        // Display the registration form with CSRF token
        $token = CSRFProtection::getToken();

        // Debug: Log token generation
        error_log("DEBUG: Generated CSRF token for register view: " . substr($token, 0, 10) . "...");

        $this->view('register', [
            'csrf_token' => $token
        ]);
    }

    public function handleRegister()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/register');
            return;
        }

        // Debug: Check if CSRF token was received
        $receivedToken = $_POST['csrf_token'] ?? null;
        error_log("DEBUG: Received CSRF token: " . ($receivedToken ? substr($receivedToken, 0, 10) . "..." : "NULL"));

        // Check rate limiting
        if (RateLimiter::isLimited('register', null, self::MAX_REGISTER_ATTEMPTS, self::RATE_LIMIT_WINDOW)) {
            $limitInfo = RateLimiter::getLimitInfo('register', null, self::MAX_REGISTER_ATTEMPTS, self::RATE_LIMIT_WINDOW);
            $this->view('register', [
                'error' => 'Too many registration attempts. Please try again in ' . ceil($limitInfo['time_until_reset'] / 60) . ' minutes.',
                'rate_limited' => true,
                'csrf_token' => CSRFProtection::getToken()
            ]);
            return;
        }

        // Validate CSRF token
        if (!CSRFProtection::validatePostToken()) {
            RateLimiter::recordAttempt('register');
            error_log("DEBUG: CSRF validation failed for register");
            $this->view('register', [
                'error' => 'Security token validation failed. Please try again.',
                'csrf_token' => CSRFProtection::getToken()
            ]);
            return;
        }

        // Sanitize input data
        $username = InputSanitizer::sanitizeUsername($_POST['username'] ?? '');
        $email = InputSanitizer::sanitizeEmail($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Create validator and add rules
        $validator = new InputValidator();
        $validator
            ->addRule('username', 'required')
            ->addRule('username', 'username', null, 'Username must be 3-20 characters and contain only letters, numbers, hyphens, and underscores')
            ->addRule('username', 'min_length', 3)
            ->addRule('username', 'max_length', 20)
            ->addRule('username', 'no_html')
            ->addRule('username', 'no_sql_keywords')

            ->addRule('email', 'required')
            ->addRule('email', 'email')
            ->addRule('email', 'max_length', 100)
            ->addRule('email', 'no_html')
            ->addRule('email', 'no_sql_keywords')

            ->addRule('password', 'required')
            ->addRule('password', 'min_length', 8)
            ->addRule('password', 'max_length', 128)
            ->addRule('password', 'password_strength');

        // Validate input
        $inputData = [
            'username' => $username,
            'email' => $email,
            'password' => $password
        ];

        if (!$validator->validate($inputData)) {
            RateLimiter::recordAttempt('register');
            $this->view('register', [
                'errors' => $validator->getErrors(),
                'old_input' => $inputData,
                'csrf_token' => CSRFProtection::getToken()
            ]);
            return;
        }

        // Additional security checks
        if ($this->isDuplicateSubmission($username, $email)) {
            RateLimiter::recordAttempt('register');
            $this->view('register', [
                'error' => 'Please wait before submitting again.',
                'csrf_token' => CSRFProtection::getToken()
            ]);
            return;
        }

        // Try to create user
        $user = new User();
        $result = $user->create($username, $email, $password);

        if ($result) {
            // Clear rate limiting on successful registration
            RateLimiter::clearLimit('register');

            // Log successful registration
            $this->logSecurityEvent('user_registered', [
                'username' => $username,
                'email' => $email,
                'ip' => $this->getClientIP()
            ]);

            $this->view('register', [
                'success' => 'Registration successful! You can now login.',
                'csrf_token' => CSRFProtection::getToken()
            ]);
        } else {
            RateLimiter::recordAttempt('register');
            $this->view('register', [
                'error' => 'Registration failed. Username or email might already exist.',
                'old_input' => $inputData,
                'csrf_token' => CSRFProtection::getToken()
            ]);
        }
    }

    public function login()
    {
        // Check rate limiting
        if (RateLimiter::isLimited('login', null, self::MAX_LOGIN_ATTEMPTS, self::RATE_LIMIT_WINDOW)) {
            $limitInfo = RateLimiter::getLimitInfo('login', null, self::MAX_LOGIN_ATTEMPTS, self::RATE_LIMIT_WINDOW);
            $this->view('login', [
                'error' => 'Too many login attempts. Please try again in ' . ceil($limitInfo['time_until_reset'] / 60) . ' minutes.',
                'rate_limited' => true,
                'csrf_token' => CSRFProtection::getToken()
            ]);
            return;
        }

        // Display the login form with CSRF token
        $token = CSRFProtection::getToken();

        // Debug: Log token generation
        error_log("DEBUG: Generated CSRF token for login view: " . substr($token, 0, 10) . "...");

        $this->view('login', [
            'csrf_token' => $token
        ]);
    }

    public function handleLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login');
            return;
        }

        // Debug: Check if CSRF token was received
        $receivedToken = $_POST['csrf_token'] ?? null;
        error_log("DEBUG: Received CSRF token: " . ($receivedToken ? substr($receivedToken, 0, 10) . "..." : "NULL"));

        // Check rate limiting
        if (RateLimiter::isLimited('login', null, self::MAX_LOGIN_ATTEMPTS, self::RATE_LIMIT_WINDOW)) {
            $limitInfo = RateLimiter::getLimitInfo('login', null, self::MAX_LOGIN_ATTEMPTS, self::RATE_LIMIT_WINDOW);
            $this->view('login', [
                'error' => 'Too many login attempts. Please try again in ' . ceil($limitInfo['time_until_reset'] / 60) . ' minutes.',
                'rate_limited' => true,
                'csrf_token' => CSRFProtection::getToken()
            ]);
            return;
        }

        // Validate CSRF token
        if (!CSRFProtection::validatePostToken()) {
            RateLimiter::recordAttempt('login');
            $this->logSecurityEvent('csrf_validation_failed', [
                'action' => 'login',
                'ip' => $this->getClientIP()
            ]);

            error_log("DEBUG: CSRF validation failed for login");

            $this->view('login', [
                'error' => 'Security token validation failed. Please try again.',
                'csrf_token' => CSRFProtection::getToken()
            ]);
            return;
        }

        // Apply progressive delay for repeated failures
        $delay = RateLimiter::getProgressiveDelay('login_fail');
        if ($delay > 0) {
            sleep($delay);
        }

        // Sanitize input data
        $username = InputSanitizer::sanitizeText($_POST['username'] ?? '', ['max_length' => 100]);
        $password = $_POST['password'] ?? '';

        // Create validator and add rules
        $validator = new InputValidator();
        $validator
            ->addRule('username', 'required')
            ->addRule('username', 'max_length', 100)
            ->addRule('username', 'no_html')
            ->addRule('username', 'no_sql_keywords')

            ->addRule('password', 'required')
            ->addRule('password', 'max_length', 128);

        // Validate input
        $inputData = [
            'username' => $username,
            'password' => $password
        ];

        if (!$validator->validate($inputData)) {
            RateLimiter::recordAttempt('login');
            RateLimiter::recordProgressiveAttempt('login_fail');

            $this->view('login', [
                'errors' => $validator->getErrors(),
                'old_input' => ['username' => $username],
                'csrf_token' => CSRFProtection::getToken()
            ]);
            return;
        }

        // Attempt authentication
        $user = new User();
        $userData = $user->authenticate($username, $password);

        if ($userData) {
            // Clear rate limiting on successful login
            RateLimiter::clearLimit('login');
            RateLimiter::clearProgressiveDelay('login_fail');

            // Regenerate session ID for security
            session_regenerate_id(true);

            // Set session data
            $_SESSION['user_id'] = $userData['id'];
            $_SESSION['username'] = $userData['username'];
            $_SESSION['login_time'] = time();
            $_SESSION['ip_address'] = $this->getClientIP();

            // Log successful login
            $this->logSecurityEvent('user_login_success', [
                'user_id' => $userData['id'],
                'username' => $userData['username'],
                'ip' => $this->getClientIP()
            ]);

            $this->redirect('/dashboard');
        } else {
            // Record failed attempt
            RateLimiter::recordAttempt('login');
            RateLimiter::recordProgressiveAttempt('login_fail');

            // Log failed login attempt
            $this->logSecurityEvent('user_login_failed', [
                'attempted_username' => $username,
                'ip' => $this->getClientIP()
            ]);

            $this->view('login', [
                'error' => 'Invalid username or password.',
                'old_input' => ['username' => $username],
                'csrf_token' => CSRFProtection::getToken()
            ]);
        }
    }

    public function dashboard()
    {
        // Check if user is logged in
        if (!$this->isLoggedIn()) {
            $this->redirect('/login');
            return;
        }

        // Validate session integrity
        if (!$this->validateSession()) {
            $this->logout();
            return;
        }

        $this->view('dashboard', [
            'username' => $_SESSION['username'],
            'csrf_token' => CSRFProtection::getToken()
        ]);
    }

    public function profile()
    {
        // Check if user is logged in
        if (!$this->isLoggedIn()) {
            $this->redirect('/login');
            return;
        }

        // Validate session integrity
        if (!$this->validateSession()) {
            $this->logout();
            return;
        }

        // Get user data
        $user = new User();
        $userData = $user->getProfileData($_SESSION['user_id']);

        if (!$userData) {
            $this->logSecurityEvent('profile_access_invalid_user', [
                'session_user_id' => $_SESSION['user_id'],
                'ip' => $this->getClientIP()
            ]);
            $this->logout();
            return;
        }

        $this->view('profile', [
            'user' => $userData,
            'csrf_token' => CSRFProtection::getToken()
        ]);
    }

    public function logout()
    {
        if (isset($_SESSION['user_id'])) {
            // Log logout
            $this->logSecurityEvent('user_logout', [
                'user_id' => $_SESSION['user_id'],
                'username' => $_SESSION['username'] ?? 'unknown',
                'ip' => $this->getClientIP()
            ]);
        }

        // Clear all session data
        $_SESSION = [];

        // Destroy session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        // Destroy session
        session_destroy();

        $this->redirect('/');
    }

    /**
     * Check if user is logged in
     */
    private function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    /**
     * Validate session integrity
     */
    private function validateSession(): bool
    {
        // Check if session has required data
        if (!isset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['login_time'])) {
            return false;
        }

        // Check session timeout (24 hours)
        if (time() - $_SESSION['login_time'] > 86400) {
            return false;
        }

        // Check IP address consistency (optional, can be disabled for mobile users)
        if (isset($_SESSION['ip_address']) && $_SESSION['ip_address'] !== $this->getClientIP()) {
            // Log potential session hijacking
            $this->logSecurityEvent('session_ip_mismatch', [
                'user_id' => $_SESSION['user_id'],
                'original_ip' => $_SESSION['ip_address'],
                'current_ip' => $this->getClientIP()
            ]);
            // For now, just log it but don't invalidate (can be too strict)
        }

        return true;
    }

    /**
     * Check for duplicate submission
     */
    private function isDuplicateSubmission(string $username, string $email): bool
    {
        $key = 'last_submission_' . hash('sha256', $username . $email);
        $now = time();

        if (isset($_SESSION[$key]) && ($now - $_SESSION[$key]) < 5) {
            return true;
        }

        $_SESSION[$key] = $now;
        return false;
    }

    /**
     * Get client IP address
     */
    private function getClientIP(): string
    {
        $headers = [
            'HTTP_CF_CONNECTING_IP',
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ];

        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ip = $_SERVER[$header];
                if (strpos($ip, ',') !== false) {
                    $ip = trim(explode(',', $ip)[0]);
                }
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }

        return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    }

    /**
     * Log security events
     */
    private function logSecurityEvent(string $event, array $data = []): void
    {
        $logData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'event' => $event,
            'data' => $data,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'referer' => $_SERVER['HTTP_REFERER'] ?? ''
        ];

        // Ensure logs directory exists
        $logsDir = __DIR__ . '/../../logs';
        if (!file_exists($logsDir)) {
            mkdir($logsDir, 0755, true);
        }

        // Log to file (in production, consider using a proper logging library)
        $logMessage = json_encode($logData) . "\n";
        error_log($logMessage, 3, $logsDir . '/security.log');
    }
}
