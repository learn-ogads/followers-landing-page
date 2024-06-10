<?php

require_once "DatabaseHandler.php";

class Click extends DatabaseHandler {
    public function __construct()
    {
        $this->createTable();
    }

    public function create(int $offerId, int $sessionId): void
    {
        $statement = $this->connect()->prepare("INSERT INTO clicks (offer_id, session_id) VALUES (?, ?)");
        $statement->execute([
            $offerId,
            $sessionId
        ]);
    }

    public function getAll(int $sessionId): array
    {
        $q = $this->connect()->prepare("SELECT * FROM clicks WHERE session_id=? AND completed=1 ORDER BY completed_at DESC");
        $q->execute([$sessionId]);
       return $q->fetchAll();
    }

    public function update(int $completed, DateTime $completedAt, int $offerId, int $sessionId): void
    {
        $dateTime = $completedAt->format("Y-m-d H:i:s");
        $q = $this->connect()->prepare("UPDATE clicks SET completed=?, completed_at=? WHERE offer_id=? AND session_id=?");
        $q->execute([
            $completed,
            $dateTime,
            $offerId,
            $sessionId
        ]);
    }

    /*
     * deleteBySessionId will bulk delete all the clicks with the associated session ID
     * */
    public function deleteBySessionId(int $sessionId): void
    {
        $q = $this->connect()->prepare("DELETE FROM clicks WHERE session_id=?");
        $q->execute([
            $sessionId
        ]);
    }

    private function createTable(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS `clicks` (
    `id` INT AUTO_INCREMENT NOT NULL,
    `offer_id` INT,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `completed` BOOL DEFAULT false,
    `completed_at` DATETIME,
    `session_id` INT,
    PRIMARY KEY(id),
    FOREIGN KEY (session_id) REFERENCES sessions(id)
);";
        $conn = $this->connect();
        $conn->exec($sql);
    }
}
