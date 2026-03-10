<?php

declare(strict_types=1);

session_start();

use App\Controllers\AuthController;
use App\Config\Database;

// Basic Autoload (since we're not using Composer)
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/../src/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Route handling
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Database instance
$db = new Database();

// Basic Router
switch ($uri) {
    case '/':
    case '/index':
        // If not logged in, redirect to login
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        // Dashboard
        require '../views/dashboard/index.php';
        break;

    case '/history':
        $controller = new \App\Controllers\DiagnosticController($db);
        $controller->history();
        break;

    case '/save-diagnostic':
        $controller = new \App\Controllers\DiagnosticController($db);
        $controller->save();
        break;

    case '/login':
        $controller = new AuthController($db);
        if ($method === 'POST') {
            $controller->login();
        } else {
            require '../views/auth/login.php';
        }
        break;

    case '/register':
        $controller = new AuthController($db);
        if ($method === 'POST') {
            $controller->register();
        } else {
            require '../views/auth/register.php';
        }
        break;

    case '/logout':
        session_destroy();
        header('Location: /login');
        exit;

    default:
        http_response_code(404);
        echo 'Página não encontrada.';
        break;
}
