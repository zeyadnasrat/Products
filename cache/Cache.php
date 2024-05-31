<?php

namespace App\Cache;

class Cache
{
    private $cacheDir;
    private $cacheTime;

    public function __construct()
    {
        $this->cacheDir = __DIR__ . '/../cache';
        $this->cacheTime = 300;

        if (!file_exists($this->cacheDir)) {
            mkdir($this->cacheDir, 0777, true);
        }
    }

    public function get($key)
    {
        $cacheFile = $this->cacheDir . '/' . $key . '.json';
        if (file_exists($cacheFile) && (filemtime($cacheFile) + $this->cacheTime > time())) {
            $data = file_get_contents($cacheFile);
            return json_decode($data, true);
        }
        return null;
    }

    public function getProduct($key)
    {
        $cacheFile = $this->cacheDir . '/' . $key . '.object';
        if (file_exists($cacheFile) && (filemtime($cacheFile) + $this->cacheTime > time())) {
            return unserialize(file_get_contents($cacheFile));
        }
        return null;
    }

    public function set($key, $data)
    {
        $cacheFile = $this->cacheDir . '/' . $key . '.json';
        file_put_contents($cacheFile, json_encode($data));
    }

    public function setProduct($key, $product)
    {
        $cacheFile = $this->cacheDir . '/' . $key . '.object';
        file_put_contents($cacheFile, serialize($product));
    }

    public function invalidateCache($key = null)
    {
        if ($key) {
            $cacheFile = $this->cacheDir . '/' . $key . '.json';
            if (file_exists($cacheFile)) {
                unlink($cacheFile);
            }
            $cacheFile = $this->cacheDir . '/' . $key . '.object';
            if (file_exists($cacheFile)) {
                unlink($cacheFile);
            }
        } else {
            // Invalidate all cache if no specific key is provided
            $files = glob($this->cacheDir . '/*.{json,object}', GLOB_BRACE);
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
        }
    }
}
