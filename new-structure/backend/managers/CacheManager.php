<?php

class CacheManager {
    private string $cacheDir;
    private int $defaultTtl;

    public function __construct (string $cacheDir = __DIR__ . '/../cache/', int $defaultTtl = 300) {
        $this->cacheDir = rtrim($cacheDir, '/') . '/';
        $this->defaultTtl = $defaultTtl;

        if (!file_exists($this->cacheDir)) {
            mkdir($this->cacheDir, 0775, true);
        }
    }

    public function get(string $key) {
        $file = $this->cacheDir . $key . '.cache';

        if (!file_exists($file)) return false;

        $payload = unserialize(file_get_contents($file));
        $isExpired = (time() - $payload['timestamp']) > $payload['ttl'];

        return $isExpired ? false : $payload['data'];
    }

    public function set(string $key, $data, int $ttl): void {
        $ttl = $ttl ?? $this->defaultTtl;
        $file = $this->cacheDir . $key . '.cache';
        $payload = [
            'timestamp' => time(),
            'ttl' => $ttl,
            'data' => $data
        ];
        file_put_contents($file, serialize($payload));
    }

    public function getOrSet(string $key, callable $callback, int $ttl) {
        $data = $this->get($key);
        if ($data == false) {
            $data = $callback();
            $this->set($key, $data, $ttl);
        }
        return $data;
    }
}