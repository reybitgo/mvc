<?php

// C:\laragon\www\mvc\src\models\User.php

namespace Gawis\MVC\Models;

class User extends Model
{
    // Remove the $pdo property declaration - it's inherited from Model class

    public static function create($username, $email, $password)
    {
        try {
            $pdo = self::getInstance()->getPdo();

            // Check if username or email already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);

            if ($stmt->rowCount() > 0) {
                return false; // User already exists
            }

            // Hash the password
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user (default role is 'member')
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, role, is_active, created_at) VALUES (?, ?, ?, 'member', 1, NOW())");
            return $stmt->execute([$username, $email, $passwordHash]);
        } catch (\PDOException $e) {
            error_log("User creation error: " . $e->getMessage());
            return false;
        }
    }

    public static function authenticate($username, $password)
    {
        try {
            $pdo = self::getInstance()->getPdo();

            $stmt = $pdo->prepare("SELECT id, username, email, password_hash, role FROM users WHERE (username = ? OR email = ?) AND is_active = 1");
            $stmt->execute([$username, $username]);

            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                return $user;
            }

            return false;
        } catch (\PDOException $e) {
            error_log("Authentication error: " . $e->getMessage());
            return false;
        }
    }

    public static function findById($id)
    {
        try {
            $pdo = self::getInstance()->getPdo();

            $stmt = $pdo->prepare("SELECT id, username, email, role, created_at FROM users WHERE id = ? AND is_active = 1");
            $stmt->execute([$id]);

            return $stmt->fetch();
        } catch (\PDOException $e) {
            error_log("Find user error: " . $e->getMessage());
            return false;
        }
    }

    public static function findByUsername($username)
    {
        try {
            $pdo = self::getInstance()->getPdo();

            $stmt = $pdo->prepare("SELECT id, username, email, role, created_at FROM users WHERE username = ? AND is_active = 1");
            $stmt->execute([$username]);

            return $stmt->fetch();
        } catch (\PDOException $e) {
            error_log("Find user error: " . $e->getMessage());
            return false;
        }
    }

    public static function updateProfile($id, $email)
    {
        try {
            $pdo = self::getInstance()->getPdo();

            $stmt = $pdo->prepare("UPDATE users SET email = ? WHERE id = ?");
            return $stmt->execute([$email, $id]);
        } catch (\PDOException $e) {
            error_log("Update profile error: " . $e->getMessage());
            return false;
        }
    }

    public static function changePassword($id, $newPassword)
    {
        try {
            $pdo = self::getInstance()->getPdo();

            $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
            return $stmt->execute([$passwordHash, $id]);
        } catch (\PDOException $e) {
            error_log("Change password error: " . $e->getMessage());
            return false;
        }
    }

    public static function updateEmail($id, $email)
    {
        try {
            $pdo = self::getInstance()->getPdo();

            // Check if email already exists for another user
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
            $stmt->execute([$email, $id]);

            if ($stmt->rowCount() > 0) {
                return false; // Email already in use
            }

            // Update email
            $stmt = $pdo->prepare("UPDATE users SET email = ? WHERE id = ?");
            return $stmt->execute([$email, $id]);
        } catch (\PDOException $e) {
            error_log("Update email error: " . $e->getMessage());
            return false;
        }
    }

    public static function verifyCurrentPassword($id, $password)
    {
        try {
            $pdo = self::getInstance()->getPdo();

            $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE id = ? AND is_active = 1");
            $stmt->execute([$id]);

            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                return true;
            }

            return false;
        } catch (\PDOException $e) {
            error_log("Verify password error: " . $e->getMessage());
            return false;
        }
    }

    public static function getProfileData($id)
    {
        try {
            $pdo = self::getInstance()->getPdo();

            $stmt = $pdo->prepare("SELECT id, username, email, role, created_at FROM users WHERE id = ? AND is_active = 1");
            $stmt->execute([$id]);

            $user = $stmt->fetch();

            if ($user) {
                // Add additional profile information
                $user['member_since'] = date('F Y', strtotime($user['created_at']));
                $user['days_active'] = floor((time() - strtotime($user['created_at'])) / 86400);
                $user['role_display'] = ucfirst($user['role']);
            }

            return $user;
        } catch (\PDOException $e) {
            error_log("Get profile data error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if user has specific role
     */
    public static function hasRole($userId, $role)
    {
        try {
            $pdo = self::getInstance()->getPdo();

            $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ? AND is_active = 1");
            $stmt->execute([$userId]);

            $user = $stmt->fetch();
            return $user && $user['role'] === $role;
        } catch (\PDOException $e) {
            error_log("Check role error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if user is admin
     */
    public static function isAdmin($userId)
    {
        return self::hasRole($userId, 'admin');
    }

    /**
     * Get user role
     */
    public static function getRole($userId)
    {
        try {
            $pdo = self::getInstance()->getPdo();

            $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ? AND is_active = 1");
            $stmt->execute([$userId]);

            $user = $stmt->fetch();
            return $user ? $user['role'] : null;
        } catch (\PDOException $e) {
            error_log("Get role error: " . $e->getMessage());
            return null;
        }
    }
}
