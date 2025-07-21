<?php
require_once __DIR__ . '/CacheManager.php';

class ProductManager
{
    private CacheManager $cache;

    public function __construct(
        private PDO $pdo
    ) {
        $this->cache = new CacheManager();
    }

    public function getProducts(): array
    {
        $success = true;
        $message = 'Products Retrieved Successfully';
        $data = [];

        try {
            $stmt = $this->pdo->query('SELECT COUNT(*) FROM tbl_products');
            if ($stmt->fetchColumn() < 1) throw new Exception('No Products Found');

            $stmt = $this->pdo->query('SELECT products_id, products_item, products_description, products_cost, products_srp, services_type, tbl_products.services_id FROM tbl_products LEFT JOIN tbl_services ON tbl_products.services_id = tbl_services.services_id');
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }

        return ['success' => $success, 'message' => $message, 'data' => $data];
    }
    public function deleteById(array $data): array
    {
        $success = true;
        $message = 'Product Deleted Successfully';

        try {
            $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM tbl_products WHERE products_id=?');
            $stmt->execute([$data['id']]);
            if ($stmt->fetchColumn() < 0) throw new Exception('Product does not exist');

            $stmt = $this->pdo->prepare('DELETE FROM tbl_products WHERE products_id=?');
            $stmt->execute([$data['id']]);

            $updateResult = $this->updateCache('products');
            if (!$updateResult['success']) throw new Exception('Product Deleted. Cache Update Unsuccessful: ' . $updateResult['message']);
        } catch (Exception $e) {
            $success = false;
            $message = $e;
        }
        return ['success' => $success, 'message' => $message];
    }
    public function addProduct($data): array
    {
        $success = true;
        $message = 'Product Added Successfully';

        try {
            if (!isset($data['item'], $data['desc'], $data['cost'], $data['srp'], $data['service'])) throw new Exception('Missing Fields');

            $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM tbl_products WHERE products_id=?');
            $stmt->execute([$data['item']]);
            if ($stmt->fetchColumn() > 0) throw new Exception('Product Already Exists');

            $stmt = $this->pdo->prepare('INSERT INTO tbl_products (products_item, products_description, products_cost, products_srp, services_id) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$data['item'], $data['desc'], $data['cost'], $data['srp'], $data['service']]);

            $updateResult = $this->updateCache('products');
            if (!$updateResult['success']) throw new Exception('Product Added. Cache Update Unsuccessful: ' . $updateResult['message']);
        } catch (Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }

        return ['success' => $success, 'message' => $message];
    }
    public function editProduct(array $data): array
    {
        $success = true;
        $message = 'Product Edited Successfully';

        try {
            if (!isset($data['products_id'])) throw new Exception('Missing Field');

            $keys = [];
            $values = [];
            foreach ($data as $key => $value) {
                if ($key != 'products_id') {
                    $keys[] = "$key =?";
                    $values[] = $value;
                }
            }
            $values[] = $data['products_id'];

            $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM tbl_products WHERE products_id=?');
            $stmt->execute([$data['products_id']]);
            if ($stmt->fetchColumn() < 1) throw new Exception('Product does not exist');

            $stmt = $this->pdo->prepare('UPDATE tbl_products SET ' . implode(', ', $keys) . ' WHERE products_id=?');
            $stmt->execute($values);

            $data = $this->updateCache('products');
            if (!$data['success']) throw new Exception($data['message']);
        } catch (Exception $e) {
            $success = false;
            $message = $e->getMessage();
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
            $message = $e->getMessage();
        }
        return ['success' => $success, 'message' => $message];
    }
}