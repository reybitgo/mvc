# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a PHP MVC (Model-View-Controller) authentication system with security features. The application uses PSR-4 autoloading with the namespace `Gawis\MVC` mapped to the `src/` directory.

## Architecture

### Directory Structure
- `src/controllers/` - Controller classes that handle HTTP requests and responses
- `src/models/` - Model classes for database interactions
- `src/views/` - PHP view templates for rendering HTML
- `src/security/` - Security-related classes (CSRF, input validation, rate limiting, sanitization)
- `config/` - Configuration files (database settings)
- `public/` - Web-accessible entry point with front controller pattern
- `logs/` - Application logs (protected by .htaccess)

### Key Components

**Front Controller**: `public/index.php` serves as the single entry point. All requests are routed through this file via `.htaccess` URL rewriting.

**Base Classes**:
- `Controller` - Base controller with view rendering and redirect methods
- `Model` - Base model class for database operations

**Authentication Flow**:
- User registration/login handled by `UserController`
- Profile management handled by `ProfileController`
- Session-based authentication with secure password hashing
- User data stored in MySQL `users` table with email verification

**Security Features**:
- CSRF protection via `CSRFProtection` class
- Input validation via `InputValidator` class
- Input sanitization via `InputSanitizer` class
- Rate limiting via `RateLimiter` class
- Security logging to `logs/security.log`

### Database

The application uses MySQL with configuration in `config/database.php`. Database schema is defined in `db.sql` with a `users` table containing authentication fields.

## Development Commands

### Setup
```bash
# Install dependencies
composer install

# Setup logs directory (run once)
php setup_logs.php

# Import database schema
mysql -u root -p mvc_db < db.sql
```

### Testing
```bash
# Run PHPUnit tests
vendor/bin/phpunit
```

### Development Server
Access the application at the document root (e.g., `http://localhost/mvc` if using Laragon/XAMPP).

## Routing

Simple array-based routing in `public/index.php`:
- `/` or `/home` - Homepage
- `/register` - User registration (GET/POST)
- `/login` - User login (GET/POST)
- `/dashboard` - User dashboard (requires auth)
- `/profile` - User profile (requires auth)
- `/profile/update` - Profile update (POST)
- `/logout` - User logout

## Security Considerations

- All user inputs are validated and sanitized
- Passwords are hashed using PHP's `password_hash()`
- CSRF tokens protect against cross-site request forgery
- Rate limiting prevents brute force attacks
- Sensitive files are protected by .htaccess rules
- Security events are logged for monitoring