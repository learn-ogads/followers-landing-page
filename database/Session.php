<?php

require_once "DatabaseHandler.php";

class Session extends DatabaseHandler {
    public function __construct()
    {
        $this->createTable();
    }

    public function create(string $ipAddress, string $affSub4, string $username, string $platform, int $followers): void
    {
        $statement = $this->connect()->prepare("INSERT INTO sessions (ip_address, aff_sub4, username, platform, followers) VALUES (?, ?, ?, ?, ?)");
        $statement->execute([
            $ipAddress,
            $affSub4,
            $username,
            $platform,
            $followers
        ]);
    }

    public function get(string $ipAddress, string $affSub4)
    {
        $q = $this->connect()->prepare("SELECT * FROM sessions WHERE ip_address=? AND aff_sub4=?");
        $q->execute([$ipAddress, $affSub4]);
        return $q->fetch(PDO::FETCH_ASSOC);
    }

    public function update(string $ipAddress, string $affSub4, string $username, string $platform, int $followers): void
    {
        $q = $this->connect()->prepare("UPDATE sessions SET username=?, platform=?, followers=? WHERE ip_address=? AND aff_sub4=?");
        $q->execute([
            $username,
            $platform,
            $followers,
            $ipAddress,
            $affSub4
        ]);
    }

    public function delete(string $ipAddress, string $affSub4): void
    {
        $q = $this->connect()->prepare("DELETE FROM sessions WHERE ip_address=? AND aff_sub4=?");
        $q->execute([$ipAddress, $affSub4]);
    }

    private function createTable(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS `sessions` (
    `id` INT AUTO_INCREMENT NOT NULL,
    `aff_sub4` VARCHAR(255),
    `username` VARCHAR(255),
    `platform` VARCHAR(255),
    `followers` INT,
    `ip_address` VARCHAR(255) NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(id)
);";
        $conn = $this->connect();
        $conn->exec($sql);
    }
}
