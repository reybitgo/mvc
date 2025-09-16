<?php

// C:\laragon\www\mvc\src\models\Model.php

namespace Gawis\MVC\Models;

use PDO;
use PDOException;

class Model
{
    protected static $instance;
    protected $pdo;

    protected function __construct()
    {
        $config = require_once __DIR__ . '/../../config/database.php';

        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";

        try {
            $this->pdo = new PDO($dsn, $config['username'], $config['password']);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getPdo()
    {
        return $this->pdo;
    }

    /**
     * Execute a prepared statement with parameters
     */
    protected function execute($sql, $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log("Database query error: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Fetch a single record
     */
    protected function fetchOne($sql, $params = [])
    {
        $stmt = $this->execute($sql, $params);
        return $stmt->fetch();
    }

    /**
     * Fetch multiple records
     */
    protected function fetchAll($sql, $params = [])
    {
        $stmt = $this->execute($sql, $params);
        return $stmt->fetchAll();
    }

    /**
     * Get the last inserted ID
     */
    protected function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
}
