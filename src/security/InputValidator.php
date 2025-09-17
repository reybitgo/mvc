<?php

// C:\laragon\www\mvc\src\security\InputValidator.php

namespace Gawis\MVC\Security;

class InputValidator
{
    private array $errors = [];
    private array $rules = [];
    private array $data = [];

    /**
     * Add validation rule
     */
    public function addRule(string $field, string $rule, $value = null, ?string $message = null): self
    {
        if (!isset($this->rules[$field])) {
            $this->rules[$field] = [];
        }

        $this->rules[$field][] = [
            'rule' => $rule,
            'value' => $value,
            'message' => $message
        ];

        return $this;
    }

    /**
     * Validate input data
     */
    public function validate(array $data): bool
    {
        $this->data = $data;
        $this->errors = [];

        foreach ($this->rules as $field => $fieldRules) {
            $fieldValue = $data[$field] ?? null;

            foreach ($fieldRules as $rule) {
                if (!$this->validateField($fieldValue, $rule['rule'], $rule['value'])) {
                    $this->addError($field, $rule['message'] ?? $this->getDefaultMessage($field, $rule['rule'], $rule['value']));
                }
            }
        }

        return empty($this->errors);
    }

    /**
     * Validate individual field
     */
    private function validateField($value, string $rule, $ruleValue): bool
    {
        switch ($rule) {
            case 'required':
                return !empty(trim($value ?? ''));

            case 'email':
                return empty($value) || filter_var($value, FILTER_VALIDATE_EMAIL) !== false;

            case 'min_length':
                return empty($value) || strlen(trim($value)) >= (int)$ruleValue;

            case 'max_length':
                return empty($value) || strlen(trim($value)) <= (int)$ruleValue;

            case 'alpha':
                return empty($value) || preg_match('/^[a-zA-Z]+$/', $value);

            case 'alpha_numeric':
                return empty($value) || preg_match('/^[a-zA-Z0-9]+$/', $value);

            case 'alpha_numeric_space':
                return empty($value) || preg_match('/^[a-zA-Z0-9\s]+$/', $value);

            case 'username':
                return empty($value) || preg_match('/^[a-zA-Z0-9_-]{3,20}$/', $value);

            case 'password_strength':
                return empty($value) || $this->validatePasswordStrength($value);

            case 'no_html':
                return empty($value) || $value === strip_tags($value);

            case 'no_sql_keywords':
                return empty($value) || !$this->containsSQLKeywords($value);

            case 'safe_filename':
                return empty($value) || preg_match('/^[a-zA-Z0-9._-]+$/', $value);

            case 'ip_address':
                return empty($value) || filter_var($value, FILTER_VALIDATE_IP) !== false;

            case 'url':
                return empty($value) || filter_var($value, FILTER_VALIDATE_URL) !== false;

            case 'numeric':
                return empty($value) || is_numeric($value);

            case 'integer':
                return empty($value) || filter_var($value, FILTER_VALIDATE_INT) !== false;

            case 'positive_integer':
                return empty($value) || (filter_var($value, FILTER_VALIDATE_INT) !== false && (int)$value > 0);

            default:
                return true;
        }
    }

    /**
     * Validate password strength
     */
    private function validatePasswordStrength(string $password): bool
    {
        // Enhanced password policy: 12+ characters, complexity requirements
        if (strlen($password) < 12) {
            return false;
        }

        // Check for uppercase, lowercase, number, and special character
        $hasUpper = preg_match('/[A-Z]/', $password);
        $hasLower = preg_match('/[a-z]/', $password);
        $hasNumber = preg_match('/[0-9]/', $password);
        $hasSpecial = preg_match('/[^A-Za-z0-9]/', $password);

        // Require at least 3 out of 4 character types
        $complexityCount = $hasUpper + $hasLower + $hasNumber + $hasSpecial;
        if ($complexityCount < 3) {
            return false;
        }

        // Check against common weak passwords
        $commonPasswords = [
            'password123', 'admin123456', 'qwerty123456', 'letmein123',
            '123456789012', 'password1234', 'admin1234567', 'welcome12345',
            'changeme123', 'default12345', 'guest1234567', 'temp12345678'
        ];

        $lowerPassword = strtolower($password);
        foreach ($commonPasswords as $common) {
            if ($lowerPassword === $common) {
                return false;
            }
        }

        // Check for repeated characters (more than 3 in a row)
        if (preg_match('/(.)\1{3,}/', $password)) {
            return false;
        }

        // Check for sequential characters (like 12345 or abcde)
        if (preg_match('/(?:012|123|234|345|456|567|678|789|890|abc|bcd|cde|def|efg|fgh|ghi|hij|ijk|jkl|klm|lmn|mno|nop|opq|pqr|qrs|rst|stu|tuv|uvw|vwx|wxy|xyz)/i', $password)) {
            return false;
        }

        return true;
    }

    /**
     * Check for SQL keywords that might indicate injection attempts
     */
    private function containsSQLKeywords(string $value): bool
    {
        $sqlKeywords = [
            'SELECT',
            'INSERT',
            'UPDATE',
            'DELETE',
            'DROP',
            'CREATE',
            'ALTER',
            'UNION',
            'OR',
            'AND',
            'WHERE',
            'FROM',
            'JOIN',
            'HAVING',
            'ORDER',
            'GROUP',
            'LIMIT',
            'OFFSET',
            'EXEC',
            'EXECUTE',
            'DECLARE',
            'CAST',
            'CONVERT',
            'SUBSTRING',
            'CHAR',
            'ASCII',
            'WAITFOR',
            'DELAY'
        ];

        $upperValue = strtoupper($value);
        foreach ($sqlKeywords as $keyword) {
            if (strpos($upperValue, $keyword) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get default error message
     */
    private function getDefaultMessage(string $field, string $rule, $value): string
    {
        $fieldName = ucfirst(str_replace('_', ' ', $field));

        switch ($rule) {
            case 'required':
                return "{$fieldName} is required.";
            case 'email':
                return "{$fieldName} must be a valid email address.";
            case 'min_length':
                return "{$fieldName} must be at least {$value} characters long.";
            case 'max_length':
                return "{$fieldName} must not exceed {$value} characters.";
            case 'username':
                return "{$fieldName} must be 3-20 characters long and contain only letters, numbers, hyphens, and underscores.";
            case 'password_strength':
                return "{$fieldName} must be at least 12 characters long with at least 3 of: uppercase, lowercase, number, special character. Avoid common passwords and patterns.";
            case 'no_html':
                return "{$fieldName} cannot contain HTML tags.";
            case 'no_sql_keywords':
                return "{$fieldName} contains invalid content.";
            default:
                return "{$fieldName} is invalid.";
        }
    }

    /**
     * Add error message
     */
    private function addError(string $field, string $message): void
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }
        $this->errors[$field][] = $message;
    }

    /**
     * Get validation errors
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Get errors for specific field
     */
    public function getFieldErrors(string $field): array
    {
        return $this->errors[$field] ?? [];
    }

    /**
     * Check if validation has errors
     */
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Get first error for a field
     */
    public function getFirstError(string $field): ?string
    {
        return $this->errors[$field][0] ?? null;
    }
}
