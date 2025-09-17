<?php

// C:\laragon\www\mvc\src\controllers\ProfileController.php

namespace Gawis\MVC\Controllers;

use Gawis\MVC\Models\User;
use Gawis\MVC\Security\CSRFProtection;
use Gawis\MVC\Security\InputValidator;
use Gawis\MVC\Security\InputSanitizer;
use Gawis\MVC\Security\RateLimiter;

class ProfileController extends Controller
{
    private const MAX_UPDATE_ATTEMPTS = 5;
    private const RATE_LIMIT_WINDOW = 600; // 10 minutes

    /**
     * Show user profile page
     */
    public function index()
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
        $userData = $user->findById($_SESSION['user_id']);

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

    /**
     * Handle profile update
     */
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/profile');
            return;
        }

        // Check if user is logged in
        if (!$this->isLoggedIn()) {
            $this->redirect('/login');
            return;
        }

        // Check rate limiting
        $identifier = $_SESSION['user_id'];
        if (RateLimiter::isLimited('profile_update', $identifier, self::MAX_UPDATE_ATTEMPTS, self::RATE_LIMIT_WINDOW)) {
            $limitInfo = RateLimiter::getLimitInfo('profile_update', $identifier, self::MAX_UPDATE_ATTEMPTS, self::RATE_LIMIT_WINDOW);

            $user = new User();
            $userData = $user->findById($_SESSION['user_id']);

            $this->view('profile', [
                'user' => $userData,
                'error' => 'Too many update attempts. Please try again in ' . ceil($limitInfo['time_until_reset'] / 60) . ' minutes.',
                'rate_limited' => true,
                'csrf_token' => CSRFProtection::getToken()
            ]);
            return;
        }

        // Validate CSRF token
        if (!CSRFProtection::validatePostToken()) {
            RateLimiter::recordAttempt('profile_update', $identifier);
            $this->logSecurityEvent('csrf_validation_failed', [
                'action' => 'profile_update',
                'user_id' => $_SESSION['user_id'],
                'ip' => $this->getClientIP()
            ]);

            $user = new User();
            $userData = $user->findById($_SESSION['user_id']);

            $this->view('profile', [
                'user' => $userData,
                'error' => 'Security token validation failed. Please try again.',
                'csrf_token' => CSRFProtection::getToken()
            ]);
            return;
        }

        // Sanitize input data
        $email = InputSanitizer::sanitizeEmail($_POST['email'] ?? '');
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Create validator and add rules
        $validator = new InputValidator();
        $validator
            ->addRule('email', 'required')
            ->addRule('email', 'email')
            ->addRule('email', 'max_length', 100)
            ->addRule('email', 'no_html')
            ->addRule('email', 'no_sql_keywords');

        // Password validation (only if changing password)
        if (!empty($newPassword) || !empty($confirmPassword)) {
            $validator
                ->addRule('current_password', 'required', null, 'Current password is required to change password')
                ->addRule('new_password', 'required')
                ->addRule('new_password', 'min_length', 12)
                ->addRule('new_password', 'max_length', 128)
                ->addRule('new_password', 'password_strength')
                ->addRule('confirm_password', 'required', null, 'Please confirm your new password');
        }

        // Validate input
        $inputData = [
            'email' => $email,
            'current_password' => $currentPassword,
            'new_password' => $newPassword,
            'confirm_password' => $confirmPassword
        ];

        if (!$validator->validate($inputData)) {
            RateLimiter::recordAttempt('profile_update', $identifier);

            $user = new User();
            $userData = $user->findById($_SESSION['user_id']);

            $this->view('profile', [
                'user' => $userData,
                'errors' => $validator->getErrors(),
                'old_input' => ['email' => $email],
                'csrf_token' => CSRFProtection::getToken()
            ]);
            return;
        }

        // Additional password validation
        if (!empty($newPassword)) {
            if ($newPassword !== $confirmPassword) {
                RateLimiter::recordAttempt('profile_update', $identifier);

                $user = new User();
                $userData = $user->findById($_SESSION['user_id']);

                $this->view('profile', [
                    'user' => $userData,
                    'error' => 'New password and confirmation do not match.',
                    'old_input' => ['email' => $email],
                    'csrf_token' => CSRFProtection::getToken()
                ]);
                return;
            }

            // Verify current password
            $user = new User();
            if (!$user->verifyCurrentPassword($_SESSION['user_id'], $currentPassword)) {
                RateLimiter::recordAttempt('profile_update', $identifier);

                $this->logSecurityEvent('profile_password_change_failed', [
                    'user_id' => $_SESSION['user_id'],
                    'reason' => 'invalid_current_password',
                    'ip' => $this->getClientIP()
                ]);

                $userData = $user->findById($_SESSION['user_id']);

                $this->view('profile', [
                    'user' => $userData,
                    'error' => 'Current password is incorrect.',
                    'old_input' => ['email' => $email],
                    'csrf_token' => CSRFProtection::getToken()
                ]);
                return;
            }
        }

        // Update profile
        $user = new User();
        $updateSuccess = true;
        $successMessage = '';

        // Update email
        if ($user->updateEmail($_SESSION['user_id'], $email)) {
            $successMessage .= 'Email updated successfully. ';

            $this->logSecurityEvent('profile_email_updated', [
                'user_id' => $_SESSION['user_id'],
                'new_email' => $email,
                'ip' => $this->getClientIP()
            ]);
        } else {
            $updateSuccess = false;
        }

        // Update password if provided
        if (!empty($newPassword) && $updateSuccess) {
            if ($user->changePassword($_SESSION['user_id'], $newPassword)) {
                $successMessage .= 'Password updated successfully.';

                $this->logSecurityEvent('profile_password_changed', [
                    'user_id' => $_SESSION['user_id'],
                    'ip' => $this->getClientIP()
                ]);
            } else {
                $updateSuccess = false;
            }
        }

        // Get updated user data
        $userData = $user->findById($_SESSION['user_id']);

        if ($updateSuccess) {
            // Clear rate limiting on success
            RateLimiter::clearLimit('profile_update', $identifier);

            $this->view('profile', [
                'user' => $userData,
                'success' => trim($successMessage),
                'csrf_token' => CSRFProtection::getToken()
            ]);
        } else {
            RateLimiter::recordAttempt('profile_update', $identifier);

            $this->view('profile', [
                'user' => $userData,
                'error' => 'Profile update failed. Email might already be in use.',
                'old_input' => ['email' => $email],
                'csrf_token' => CSRFProtection::getToken()
            ]);
        }
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

        return true;
    }

    /**
     * Logout user
     */
    private function logout(): void
    {
        if (isset($_SESSION['user_id'])) {
            $this->logSecurityEvent('user_logout_forced', [
                'user_id' => $_SESSION['user_id'],
                'reason' => 'session_validation_failed',
                'ip' => $this->getClientIP()
            ]);
        }

        $_SESSION = [];
        session_destroy();
        $this->redirect('/');
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

        // Log to file
        $logMessage = json_encode($logData) . "\n";
        error_log($logMessage, 3, $logsDir . '/security.log');
    }
}
