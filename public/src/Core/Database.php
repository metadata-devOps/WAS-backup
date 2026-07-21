<?php
namespace Core;

class Database
{
    private \SQLite3 $db;

    public function __construct()
    {
        $this->db = new \SQLite3('/tmp/chall.db');
        $this->init();
    }

    private function init()
    {
        $this->db->exec("CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL UNIQUE,
            password TEXT NOT NULL,
            role TEXT NOT NULL DEFAULT 'member'
        )");
    }

    public function getDb(): \SQLite3
    {
        return $this->db;
    }

    public function prepare(string $sql): \SQLite3Stmt|false
    {
        return $this->db->prepare($sql);
    }
}
