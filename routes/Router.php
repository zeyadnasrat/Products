<?php

namespace Routes;

use App\Controllers\ProductController;

require 'vendor/autoload.php';

class Router
{
    private $routes;
    private $baseDir;
    private $requestUri;

    public function __construct()
    {
        $this->routes = [
            'GET' => [
                '/' => [ProductController::class, 'showProductList'],
                '/add-product' => [ProductController::class, 'showAddProductForm'],
            ],
            'POST' => [
                '/save-product' => [ProductController::class, 'saveProduct'],
                '/delete-products' => [ProductController::class, 'delete'],
                '/check-sku' => [ProductController::class, 'checkSku'],
            ]
        ];
        $this->baseDir = __DIR__ . '/../';
        $this->requestUri = strtok($_SERVER['REQUEST_URI'], '?');
    }

    public function handleRequest()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($this->isStaticFileRequest()) {
            $this->serveStaticFile();
            return;
        }
        
        if (isset($this->routes[$method][$this->requestUri])) {
            $this->dispatch($this->routes[$method][$this->requestUri]);
            return;
        } else {
            echo "Route not found";
        }
    }

    private function dispatch($route)
    {
        [$controller, $method] = $route;
        $controllerInstance = new $controller();

        // For POST requests, pass post data to the controller method
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $postData = $this->getPostData();
            if($this->requestUri === '/check-sku') {
                echo $controllerInstance->$method($postData['sku']);
                return;
            } else {
                echo $controllerInstance->$method($postData);
                return;
            }
        } else {
            echo $controllerInstance->$method($this->requestUri);
            return;
        }
    }

    private function getPostData()
    {
        $postData = [];
        if (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
            $input = file_get_contents('php://input');
            $postData = json_decode($input, true);
        } else {
            $postData = $_POST;
        }
        return $postData;
    }

    private function isStaticFileRequest()
    {
        $staticFileExtensions = ['css', 'js', 'html'];
        $path = realpath($this->baseDir . $this->requestUri);
        $fileExtension = pathinfo($path, PATHINFO_EXTENSION);

        // Check if the request is for a static file and not a dynamic route
        if ($path && is_file($path) && in_array($fileExtension, $staticFileExtensions)) {
            return true;
        }

        return false;
    }

    private function serveStaticFile()
    {
        $path = realpath($this->baseDir . $this->requestUri);
        $fileExtension = pathinfo($path, PATHINFO_EXTENSION);
        $mimeTypes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'html' => 'text/html'
        ];

        if (isset($mimeTypes[$fileExtension])) {
            header('Content-Type: ' . $mimeTypes[$fileExtension]);
            readfile($path);
        }
    }
}

$router = new Router();
$router->handleRequest();
