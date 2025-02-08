<?php

class Database {
    private static $instance = null;
    private $connection;

    // Configuración de la base de datos
    private $host = 'localhost';
    private $dbname = 'jbgopera_agendamiento';
    private $username = 'root';
    private $password = '';
    private $charset = 'utf8mb4';

    // Constructor privado para evitar que se instancie directamente
    private function __construct() {
        try {
            // Se establece la conexión PDO con la base de datos específica
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('Error de conexión: ' . $e->getMessage());
        }
    }

    // Método para obtener la instancia de la conexión (Singleton)
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Obtener la conexión PDO
    public function getConnection() {
        return $this->connection;
    }

    // Evitar que se copie la instancia
    private function __clone() {}

    // Evitar que se destruyan otras instancias
    public function __wakeup() {}
}