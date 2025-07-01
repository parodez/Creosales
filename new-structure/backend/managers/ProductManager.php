<?php
require_once __DIR__ . '/CacheManager.php';

class ProductManager
{
    // private PDO $pdo;

    public function __construct(
        private PDO $pdo,
        private CacheManager $cache
    ) {}

    public function getByType($cacheKey): array
    {
        $cached = $this->cache->get($cacheKey);
        if ($cached != false) return $cached;

        $stmt = $this->pdo->query('SELECT * FROM tbl_' . $cacheKey);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->cache->set($cacheKey, $data, 300);
        return $data;
    }
    public function addRobot(string $item, string $desc, float $cost, float $srp): array
    {
        $success = true;
        $message = 'Robot Successfully Added';

        try {
            $stmt = $this->pdo->prepare('INSERT INTO tbl_robots (robots_item, robots_description, robots_cost, robots_srp) VALUES (?, ?, ?, ?)');
            $stmt->execute([$item, $desc, $cost, $srp]);
        } catch (Exception $e) {
            $success = false;
            $message = 'Error occurred: ' . $e;
        }

        return ['success' => $success, 'message' => $message];
    }
    public function addService(string $type, float $cost)
    {
        $success = true;
        $message = 'Service Successfully Added';

        try {
            $stmt = $this->pdo->prepare('INSERT INTO tbl_services (services_type, services_cost) VALUES (?, ?)');
            $stmt->execute([$type, $cost]);
        } catch (Exception $e) {
            $success = false;
            $message = 'Error occurred: ' . $e;
        }

        return ['success' => $success, 'message' => $message];
    }
    // public function getRobots(): array
    // {
    //     $cacheKey = 'robots';

    //     $cached = $this->cache->get($cacheKey);
    //     if ($cached != false) return $cached;

    //     $stmt = $this->pdo->query('SELECT * FROM tbl_robots');
    //     $robots = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //     $this->cache->set($cacheKey, $robots, 300);
    //     return $robots;
    // }
    // public function getServices(): array
    // {
    //     $cacheKey = 'services';

    //     $cached = $this->cache->get($cacheKey);
    //     if ($cached != false) return $cached;
    // }
}