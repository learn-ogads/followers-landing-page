<?php
require_once "../services/DotEnvService.php";
(new DotEnvService(__DIR__ . "/../.env"))->load();

class DatabaseHandler
{
    private string $serverName;
    private string $username;
    private string $password;
    private string $dbName;
    private string $charset;

    private function createVars(): void
    {
        $this->serverName = getenv("DB_SERVER_NAME");
        $this->username = getenv("DB_USERNAME");
        $this->password = getenv("DB_PASSWORD");
        $this->dbName = getenv("DB_NAME");
        $this->charset = getenv("DB_CHARSET");
    }

    public function connect(): PDO
    {
        $this->createVars();

        $dsn = "mysql:host=".$this->serverName.";charset=".$this->charset.";";
        $pdo = new PDO($dsn, $this->username, $this->password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create database if it doesn't exist
        $dbname = "`".str_replace("`","``",$this->dbName)."`";
        $pdo->query("CREATE DATABASE IF NOT EXISTS $dbname");
        $pdo->query("use $dbname");

        return $pdo;
    }
}
