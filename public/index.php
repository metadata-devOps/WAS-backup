<?php
declare(strict_types = 1);

ini_set('display_errors', '0');
error_reporting(E_ALL & ~E_DEPRECATED);

spl_autoload_register(function ($class) {
    $prefix = '';
    $base_dir = __DIR__ . '/src/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0)
        return;
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file))
        require $file;
});

use Models\User;
use Core\Auth;
use Services\ImageService;
use Core\Config;

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

if ($path === '/' || $path === '/login') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user = User::authenticate($_POST['username'] ?? '', md5($_POST['password']) ?? '');
        if ($user->isAuthenticated) {
            Auth::login($user);
            header("Location: /inspect");
            exit;
        }
        else {
            $error = "Invalid credentials";
        }
    }
    include 'templates/login.php';
    exit;
}

if ($path === '/inspect') {
    if (!Auth::check()) {
        header("Location: /login");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
        try {
            if ($_FILES['photo']['error'] !== UPLOAD_ERR_OK)
                throw new Exception("Upload error");

            $raw = file_get_contents($_FILES['photo']['tmp_name']);
            if (!$raw)
                throw new Exception("Read error");
            $result = ImageService::analyze($raw);

            header('Content-Type: application/json');
            echo json_encode(['ok' => true, 'result' => $result]);
            exit;
        }
        catch (Throwable $e) {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
            exit;
        }
    }
    include 'templates/inspect.php';
    exit;
}

if ($path === '/admin') {
    $flag = "";
    if (Auth::isAdmin()) {
        $flag = Config::getFlag();
    }

    if ($flag) {
        echo "<h1>FLAG: " . htmlspecialchars($flag) . "</h1>";
    }
    else {
        http_response_code(403);
        echo "Access Denied. Admins only.";
    }
    exit;
}

http_response_code(404);
echo "Not Found";
