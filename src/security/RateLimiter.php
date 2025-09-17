<?php

// C:\laragon\www\mvc\src\security\RateLimiter.php

namespace Gawis\MVC\Security;

class RateLimiter
{
    private const SESSION_PREFIX = 'rate_limit_';
    private const DEFAULT_ATTEMPTS = 5;
    private const DEFAULT_WINDOW = 300; // 5 minutes

    /**
     * Check if action is rate limited
     */
    public static function isLimited(string $action, ?string $identifier = null, int $maxAttempts = self::DEFAULT_ATTEMPTS, int $windowSeconds = self::DEFAULT_WINDOW): bool
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $key = self::getKey($action, $identifier);
        $now = time();

        // Get current attempts data
        $attempts = $_SESSION[$key] ?? [];

        // Clean old attempts outside the window
        $attempts = array_filter($attempts, function ($timestamp) use ($now, $windowSeconds) {
            return ($now - $timestamp) < $windowSeconds;
        });

        // Update session
        $_SESSION[$key] = $attempts;

        return count($attempts) >= $maxAttempts;
    }

    /**
     * Record an attempt
     */
    public static function recordAttempt(string $action, ?string $identifier = null): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $key = self::getKey($action, $identifier);
        $now = time();

        // Get current attempts
        $attempts = $_SESSION[$key] ?? [];

        // Add new attempt
        $attempts[] = $now;

        // Store back to session
        $_SESSION[$key] = $attempts;
    }

    /**
     * Get remaining attempts
     */
    public static function getRemainingAttempts(string $action, ?string $identifier = null, int $maxAttempts = self::DEFAULT_ATTEMPTS, int $windowSeconds = self::DEFAULT_WINDOW): int
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $key = self::getKey($action, $identifier);
        $now = time();

        // Get current attempts data
        $attempts = $_SESSION[$key] ?? [];

        // Clean old attempts outside the window
        $attempts = array_filter($attempts, function ($timestamp) use ($now, $windowSeconds) {
            return ($now - $timestamp) < $windowSeconds;
        });

        // Update session
        $_SESSION[$key] = $attempts;

        return max(0, $maxAttempts - count($attempts));
    }

    /**
     * Get time until reset
     */
    public static function getTimeUntilReset(string $action, ?string $identifier = null, int $windowSeconds = self::DEFAULT_WINDOW): int
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $key = self::getKey($action, $identifier);
        $now = time();

        // Get current attempts data
        $attempts = $_SESSION[$key] ?? [];

        if (empty($attempts)) {
            return 0;
        }

        // Find oldest attempt
        $oldestAttempt = min($attempts);

        // Calculate time until window expires
        $timeUntilReset = $windowSeconds - ($now - $oldestAttempt);

        return max(0, $timeUntilReset);
    }

    /**
     * Clear rate limit for an action
     */
    public static function clearLimit(string $action, ?string $identifier = null): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $key = self::getKey($action, $identifier);
        unset($_SESSION[$key]);
    }

    /**
     * Get rate limit information
     */
    public static function getLimitInfo(string $action, ?string $identifier = null, int $maxAttempts = self::DEFAULT_ATTEMPTS, int $windowSeconds = self::DEFAULT_WINDOW): array
    {
        return [
            'is_limited' => self::isLimited($action, $identifier, $maxAttempts, $windowSeconds),
            'remaining_attempts' => self::getRemainingAttempts($action, $identifier, $maxAttempts, $windowSeconds),
            'time_until_reset' => self::getTimeUntilReset($action, $identifier, $windowSeconds),
            'max_attempts' => $maxAttempts,
            'window_seconds' => $windowSeconds
        ];
    }

    /**
     * Generate unique key for rate limiting
     */
    private static function getKey(string $action, ?string $identifier = null): string
    {
        // Use IP address if no identifier provided
        if ($identifier === null) {
            $identifier = self::getClientIP();
        }

        return self::SESSION_PREFIX . $action . '_' . hash('sha256', $identifier);
    }

    /**
     * Get client IP address
     */
    private static function getClientIP(): string
    {
        // Check for various headers that might contain the real IP
        $headers = [
            'HTTP_CF_CONNECTING_IP',     // Cloudflare
            'HTTP_CLIENT_IP',            // Proxy
            'HTTP_X_FORWARDED_FOR',      // Load balancer/proxy
            'HTTP_X_FORWARDED',          // Proxy
            'HTTP_X_CLUSTER_CLIENT_IP',  // Cluster
            'HTTP_FORWARDED_FOR',        // Proxy
            'HTTP_FORWARDED',            // Proxy
            'REMOTE_ADDR'                // Standard
        ];

        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ip = $_SERVER[$header];

                // Handle comma-separated IPs (X-Forwarded-For can contain multiple IPs)
                if (strpos($ip, ',') !== false) {
                    $ip = trim(explode(',', $ip)[0]);
                }

                // Validate IP (allow private ranges for local development)
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    // In development, allow all valid IPs including private ranges
                    if (($_ENV['APP_ENV'] ?? 'production') === 'development') {
                        return $ip;
                    }
                    // In production, exclude private and reserved ranges
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                        return $ip;
                    }
                }
            }
        }

        // Fallback to REMOTE_ADDR even if it's private/reserved
        return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    }

    /**
     * Check if action should be rate limited based on progressive delays
     */
    public static function getProgressiveDelay(string $action, ?string $identifier = null): int
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $key = self::getKey($action . '_progressive', $identifier);
        $attempts = $_SESSION[$key] ?? 0;

        // Progressive delay: 1s, 2s, 4s, 8s, 16s, etc. (max 60s)
        $delay = min(60, pow(2, $attempts));

        return (int)$delay;
    }

    /**
     * Record progressive delay attempt
     */
    public static function recordProgressiveAttempt(string $action, ?string $identifier = null): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $key = self::getKey($action . '_progressive', $identifier);
        $_SESSION[$key] = ($_SESSION[$key] ?? 0) + 1;
    }

    /**
     * Clear progressive delay
     */
    public static function clearProgressiveDelay(string $action, ?string $identifier = null): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $key = self::getKey($action . '_progressive', $identifier);
        unset($_SESSION[$key]);
    }
}
