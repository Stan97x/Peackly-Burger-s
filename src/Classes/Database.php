<?php

namespace App\Classes;

class Database {
    private static $pdo = null;

    public static function getConnection() {
        if (self::$pdo === null) {
            // Charger la configuration
            require __DIR__ . '/../../config/config.php';

            try {
                // Connexion PDO
                self::$pdo = new \PDO(
                    "mysql:host={$config['db_host']};dbname={$config['db_name']}",
                    $config['db_user'],
                    $config['db_pass']
                );
                self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            } catch (\PDOException $e) {
                die("Erreur de connexion : " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}

