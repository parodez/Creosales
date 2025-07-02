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

            if ($count < 0) throw new Exception('Product does not exist');
            else {
                $stmt = $this->pdo->prepare('DELETE FROM tbl_' . $product . ' WHERE ' . $product . '_id=?');
                $stmt->execute([$id]);
                $updateResult = $this->updateCache($product);
                if (!$updateResult['success']) throw new Exception('Product Deleted. Cache Update Unsuccessful: ' . $updateResult['message']);
            }
        } catch (Exception $e) {
            $success = false;
            $message = $e;
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
            $message = $e;
        }

        return ['success' => $success, 'message' => $message];
    }
    public function editRobot(int $id, string $robots_item, string $robots_description, float $robots_cost, float $robots_srp): array
    {
        $success = true;
        $message = 'Robot Successfully Edited';

        try {
            $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM tbl_robots WHERE robots_id=?');
            $stmt->execute([$id]);
            $count = $stmt->fetchColumn();

            if ($count < 1) throw new Exception('Robot does not exist');
            else {
                $stmt = $this->pdo->prepare('UPDATE tbl_robots SET robots_item=?,robots_description=?, robots_cost=?, robots_srp=? WHERE robots_id=?');
                $stmt->execute([$robots_item, $robots_description, $robots_cost, $robots_srp, $id]);
                $data = $this->updateCache('robots');
                if (!$data['success']) throw new Exception($data['message']);
            }
        } catch (Exception $e) {
            $success = false;
            $message = $e;
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
            $message = $e;
        }

        return ['success' => $success, 'message' => $message];
    }
    public function editService(int $id, string $type, float $cost): array
    {
        $success = true;
        $message = 'Service Successfully Edited';

        try {
            $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM tbl_services WHERE services_id=?');
            $stmt->execute([$id]);
            $count = $stmt->fetchColumn();
            if ($count < 1) throw new Exception('Service does not exist');
            else {
                $stmt = $this->pdo->prepare('UPDATE tbl_servies SET services_type=?, services_cost=? WHERE services_id=?');
                $stmt->execute([$type, $cost, $id]);
                $data = $this->updateCache('services');
                if (!$data['success']) throw new Exception($data['message']);
            }
        } catch (Exception $e) {
            $success = false;
            $message = $e;
        }

        return ['success' => $success, 'message' => $message];
    }
    public function updateCache(string $cacheKey): array
    {
        $success = true;
        $message = 'Cache Successfully Updated';

        try {
            $stmt = $this->pdo->query('SELECT * FROM tbl_' . $cacheKey);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $this->cache->set($cacheKey, $data, 300);
        } catch (Exception $e) {
            $success = false;
            $message = $e;
        }
        return ['success' => $success, 'message' => $message];
    }
}