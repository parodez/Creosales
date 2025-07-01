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
    public function deleteByTypeAndId(string $product, int $id): array
    {
        $success = true;
        $message = 'Product Successfully Deleted';

        try {
            $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM tbl_' . $product . ' WHERE ' . $product . '_id=?');
            $stmt->execute([$id]);
            $count = $stmt->fetchColumn();

            if ($count > 0) throw new Exception('Product does not exist');
            else {
                $stmt = $this->pdo->prepare('DELETE FROM tbl_' . $product . ' WHERE ' . $product . '_id=?');
                $stmt->execute([$id]);
                $updateResult = $this->updateCache($product);
                if (!$updateResult['success']) throw new Exception('Product Deleted. Cache Update Unsuccessful: ' . $updateResult['message']);
            }
        } catch (Exception $e) {
            $success = false;
            $message = 'Error occurred' . $e;
        }
        return ['success' => $success, 'message' => $message];
    }
    public function updateCache(string $cacheKey): array
    {
        $success = false;
        $message = 'Cache Successfully Update';

        try {
            $stmt = $this->pdo->query('SELECT * FROM tbl_' . $cacheKey);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $this->cache->set($cacheKey, $data, 300);
        } catch (Exception $e) {
            $success = false;
            $message = 'Error occurred: ' . $e;
        }
        return ['success' => $success, 'message' => $message];
    }
    public function addRobot(string $item, string $desc, float $cost, float $srp): array
    {
        $success = true;
        $message = 'Robot Successfully Added';

        try {
            $stmt = $this->pdo->prepare('INSERT INTO tbl_robots (robots_item, robots_description, robots_cost, robots_srp) VALUES (?, ?, ?, ?)');
            $stmt->execute([$item, $desc, $cost, $srp]);
            $updateResult = $this->updateCache('robots');
            if (!$updateResult['success']) throw new Exception('Robot Added. Cache Update Unsuccessful: ' . $updateResult['message']);
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
            $updateResult = $this->updateCache('services');
            if (!$updateResult['success']) throw new Exception('Service Added. Cache Update Unsuccessful: '  . $updateResult['message']);
        } catch (Exception $e) {
            $success = false;
            $message = 'Error occurred: ' . $e;
        }

        return ['success' => $success, 'message' => $message];
    }
}