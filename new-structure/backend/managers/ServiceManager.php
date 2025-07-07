<?php
require_once __DIR__ . '/CacheManager.php';

class ServiceManager
{
    // private PDO $pdo;

    public function __construct(
        private PDO $pdo
    ) {}

    public function getServices(): array
    {
        $success = true;
        $message = 'Products Retrieved Successfully';
        $data = [];

        try {
            $stmt = $this->pdo->query('SELECT * FROM tbl_services');
            if ($stmt->rowCount() < 1) throw new Exception('No Services Found');

            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }

        return ['success' => $success, 'message' => $message, 'data' => $data];
    }
}