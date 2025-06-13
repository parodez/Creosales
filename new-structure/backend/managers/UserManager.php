<?php

require_once __DIR__ . '/../classes/User.php';

class UserManager {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllUsers() {
        $stmt = $this->pdo->query('SELECT user_id AS id, user_firstname AS fn, user_lastname AS ln, user_position AS pos, user_department AS dep FROM tbl_user');
        $users = [];

        while ($row = $stmt->fetch()) {
            $users[$row['id']] = new User(
                $row['id'],
                $row['fn'],
                $row['ln'],
                $row['pos'],
                $row['dep']
            );
        }

        return $users;
    }
}