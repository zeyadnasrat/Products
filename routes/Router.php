<?php

namespace App;

require_once __DIR__ . '/../src/App/Controllers/ProductController.php';
require_once __DIR__ . '/../src/App/Services/SaveProductService.php';

use App\Controllers\ProductController;
use App\Services\SaveProductService;

class Router
{
    private $routes;
    private $baseDir;
    private $requestUri;
    private $queryString;
    private $queryParams;
    private $action;

    public function __construct()
    {
        $this->routes = [
            '/' => __DIR__ . '/../public/Index.php',
            '/add-product' => __DIR__ . '/../public/AddProduct.php',
        ];
        $this->baseDir = __DIR__ . '/../';
        $this->requestUri = strtok($_SERVER['REQUEST_URI'], '?');
        $this->queryString = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY) ?? '';
        parse_str($this->queryString, $this->queryParams);
        $this->action = $this->queryParams['action'] ?? '';
    }

    public function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->handleGetRequest();
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePostRequest();
        }
    }

    private function handleGetRequest()
    {
        if ($this->isStaticFileRequest()) {
            $this->serveStaticFile();
            return;
        } 

        if (isset($this->routes[$this->requestUri])) {
            require_once $this->routes[$this->requestUri];
            return;
        }

        if (!empty($this->action) && isset($this->routes[$this->action])) {
            require_once $this->routes[$this->action];
            return;
        }

        echo "Route not found";
    }

    private function handlePostRequest()
    {
        $postData = [];

        // Check if the Content-Type is application/json
        if (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
            // Decode the JSON input
            $input = file_get_contents('php://input');
            $postData = json_decode($input, true);
        } else {
            $postData = $_POST;
        }

        if (isset($postData['sku'])) {
            $this->validateSku($postData['sku']);
        }
        if (strpos($this->requestUri, '/src/App/Services/SaveProductService.php') !== false) {
            $this->saveProduct($postData);
        }
        if (isset($postData['action']) && $postData['action'] === 'delete_products') {
            $this->deleteProducts($postData);
        }
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

    private function validateSku($sku)
    {
        $productController = new ProductController();
        $productController->checkSku($sku);
    }

    private function saveProduct($postData)
    {
        $service = new SaveProductService();
        $service->saveProduct($postData);
    }

    private function deleteProducts($postData)
    {
        require_once __DIR__ . '/../src/App/Controllers/ProductController.php';
        $productController = new ProductController();
        $productController->delete($postData['selectedProducts']);
    }
}

$router = new Router();
$router->handleRequest();
?>
