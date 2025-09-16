<?php

// C:\laragon\www\mvc\src\security\CSRFProtection.php

namespace Gawis\MVC\Security;

class CSRFProtection
{
    private const TOKEN_NAME = 'csrf_token';
    private const TOKEN_LENGTH = 32;

    /**
     * Generate a new CSRF token and store it in session
     */
    public static function generateToken(): string
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $token = bin2hex(random_bytes(self::TOKEN_LENGTH));
        $_SESSION[self::TOKEN_NAME] = $token;

        return $token;
    }

    /**
     * Validate CSRF token from request
     */
    public static function validateToken(?string $token): bool
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (empty($token) || empty($_SESSION[self::TOKEN_NAME])) {
            return false;
        }

        $isValid = hash_equals($_SESSION[self::TOKEN_NAME], $token);

        // Regenerate token after validation (single use)
        if ($isValid) {
            unset($_SESSION[self::TOKEN_NAME]);
        }

        return $isValid;
    }

    /**
     * Get the current CSRF token (create if not exists)
     */
    public static function getToken(): string
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (empty($_SESSION[self::TOKEN_NAME])) {
            return self::generateToken();
        }

        return $_SESSION[self::TOKEN_NAME];
    }

    /**
     * Generate HTML hidden input field for CSRF token
     */
    public static function getTokenField(): string
    {
        $token = self::getToken();
        return '<input type="hidden" name="' . self::TOKEN_NAME . '" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }

    /**
     * Validate token from POST request
     */
    public static function validatePostToken(): bool
    {
        $token = $_POST[self::TOKEN_NAME] ?? null;
        return self::validateToken($token);
    }

    /**
     * Get token name for JavaScript usage
     */
    public static function getTokenName(): string
    {
        return self::TOKEN_NAME;
    }
}
