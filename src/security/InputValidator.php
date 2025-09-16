<?php

// C:\laragon\www\mvc\src\security\InputValidator.php

namespace Reybi\MVC\Security;

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
        // At least 8 characters, 1 uppercase, 1 lowercase, 1 number, 1 special character
        return strlen($password) >= 8 &&
            preg_match('/[A-Z]/', $password) &&
            preg_match('/[a-z]/', $password) &&
            preg_match('/[0-9]/', $password) &&
            preg_match('/[^A-Za-z0-9]/', $password);
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
                return "{$fieldName} must be at least 8 characters long and contain uppercase, lowercase, number, and special character.";
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
