<?php

// C:\laragon\www\mvc\src\security\InputSanitizer.php

namespace Gawis\MVC\Security;

class InputSanitizer
{
    /**
     * Sanitize string input (remove HTML, trim, etc.)
     */
    public static function sanitizeString(?string $input, bool $allowBasicHTML = false): string
    {
        if ($input === null) {
            return '';
        }

        // Trim whitespace
        $input = trim($input);

        if ($allowBasicHTML) {
            // Allow only safe HTML tags
            $allowedTags = '<p><br><strong><em><u><a>';
            $input = strip_tags($input, $allowedTags);
        } else {
            // Remove all HTML tags
            $input = strip_tags($input);
        }

        // Convert special characters to HTML entities
        $input = htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return $input;
    }

    /**
     * Sanitize email input
     */
    public static function sanitizeEmail(?string $email): string
    {
        if ($email === null) {
            return '';
        }

        $email = trim(strtolower($email));
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        return $email ?: '';
    }

    /**
     * Sanitize username (alphanumeric, underscore, hyphen only)
     */
    public static function sanitizeUsername(?string $username): string
    {
        if ($username === null) {
            return '';
        }

        $username = trim($username);
        $username = preg_replace('/[^a-zA-Z0-9_-]/', '', $username);

        return $username;
    }

    /**
     * Sanitize integer input
     */
    public static function sanitizeInt($input, int $default = 0): int
    {
        if ($input === null || $input === '') {
            return $default;
        }

        $sanitized = filter_var($input, FILTER_SANITIZE_NUMBER_INT);
        $int = filter_var($sanitized, FILTER_VALIDATE_INT);

        return $int !== false ? $int : $default;
    }

    /**
     * Sanitize float input
     */
    public static function sanitizeFloat($input, float $default = 0.0): float
    {
        if ($input === null || $input === '') {
            return $default;
        }

        $sanitized = filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $float = filter_var($sanitized, FILTER_VALIDATE_FLOAT);

        return $float !== false ? $float : $default;
    }

    /**
     * Sanitize URL input
     */
    public static function sanitizeUrl(?string $url): string
    {
        if ($url === null) {
            return '';
        }

        $url = trim($url);
        $url = filter_var($url, FILTER_SANITIZE_URL);

        return $url ?: '';
    }

    /**
     * Sanitize filename for safe file operations
     */
    public static function sanitizeFilename(?string $filename): string
    {
        if ($filename === null) {
            return '';
        }

        // Remove directory traversal attempts
        $filename = basename($filename);

        // Remove or replace dangerous characters
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);

        // Prevent hidden files
        if (strpos($filename, '.') === 0) {
            $filename = '_' . $filename;
        }

        // Limit length
        if (strlen($filename) > 255) {
            $filename = substr($filename, 0, 255);
        }

        return $filename;
    }

    /**
     * Remove potential SQL injection patterns
     */
    public static function removeSQLInjectionPatterns(string $input): string
    {
        // Remove common SQL injection patterns
        $patterns = [
            '/(\s*(;|--|\#|\*|\/\*|\*\/).*$)/i',  // Comments and semicolons
            '/\b(union|select|insert|update|delete|drop|create|alter|exec|execute)\b/i', // SQL keywords
            '/[\'\"]/i', // Quotes
            '/\b(or|and)\s+\d+\s*=\s*\d+/i', // OR/AND conditions
            '/\b(or|and)\s+[\'\"]\w*[\'\"]\s*=\s*[\'\"]\w*[\'\"]/i', // String comparisons
        ];

        foreach ($patterns as $pattern) {
            $input = preg_replace($pattern, '', $input);
        }

        return trim($input);
    }

    /**
     * Remove XSS patterns
     */
    public static function removeXSSPatterns(string $input): string
    {
        // Remove script tags and event handlers
        $patterns = [
            '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi',
            '/\bon\w+\s*=\s*[\'"][^\'\"]*[\'"]/i',
            '/javascript\s*:/i',
            '/vbscript\s*:/i',
            '/data\s*:/i',
            '/<iframe\b[^<]*(?:(?!<\/iframe>)<[^<]*)*<\/iframe>/mi',
            '/<object\b[^<]*(?:(?!<\/object>)<[^<]*)*<\/object>/mi',
            '/<embed\b[^>]*>/i',
            '/<link\b[^>]*>/i',
            '/<meta\b[^>]*>/i',
        ];

        foreach ($patterns as $pattern) {
            $input = preg_replace($pattern, '', $input);
        }

        return $input;
    }

    /**
     * Sanitize text input with comprehensive cleaning
     */
    public static function sanitizeText(?string $input, array $options = []): string
    {
        if ($input === null) {
            return '';
        }

        $maxLength = $options['max_length'] ?? 1000;
        $allowHTML = $options['allow_html'] ?? false;
        $removeSQL = $options['remove_sql'] ?? true;
        $removeXSS = $options['remove_xss'] ?? true;

        // Basic cleaning
        $input = trim($input);

        // Length limiting
        if (strlen($input) > $maxLength) {
            $input = substr($input, 0, $maxLength);
        }

        // Remove SQL patterns if requested
        if ($removeSQL) {
            $input = self::removeSQLInjectionPatterns($input);
        }

        // Remove XSS patterns if requested
        if ($removeXSS) {
            $input = self::removeXSSPatterns($input);
        }

        // Handle HTML
        return self::sanitizeString($input, $allowHTML);
    }

    /**
     * Sanitize array of inputs
     */
    public static function sanitizeArray(array $input, string $type = 'string'): array
    {
        $sanitized = [];

        foreach ($input as $key => $value) {
            $sanitizedKey = self::sanitizeString($key);

            if (is_array($value)) {
                $sanitized[$sanitizedKey] = self::sanitizeArray($value, $type);
            } else {
                switch ($type) {
                    case 'email':
                        $sanitized[$sanitizedKey] = self::sanitizeEmail($value);
                        break;
                    case 'username':
                        $sanitized[$sanitizedKey] = self::sanitizeUsername($value);
                        break;
                    case 'int':
                        $sanitized[$sanitizedKey] = self::sanitizeInt($value);
                        break;
                    case 'float':
                        $sanitized[$sanitizedKey] = self::sanitizeFloat($value);
                        break;
                    case 'url':
                        $sanitized[$sanitizedKey] = self::sanitizeUrl($value);
                        break;
                    default:
                        $sanitized[$sanitizedKey] = self::sanitizeString($value);
                }
            }
        }

        return $sanitized;
    }
}
