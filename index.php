<?php
require_once __DIR__ . '/config/database.php';
session_start();

$path = $_SERVER['REQUEST_URI'];
$path = str_replace('/shop', '', $path);
$path = strtok($path, '?');
$path = trim($path, '/');

// Головна сторінка
if ($path == '' || $path == 'index.php') {
    $stmt = $pdo->query("SELECT * FROM products LIMIT 6");
    $products = $stmt->fetchAll();
    $stmtNews = $pdo->query("SELECT * FROM news ORDER BY created_at DESC LIMIT 3");
    $news = $stmtNews->fetchAll();
    ob_start();
    require_once __DIR__ . '/views/home/index.php';
    $content = ob_get_clean();
    require_once __DIR__ . '/views/layouts/main.php';
}
// Вхід
elseif ($path == 'auth/login') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];
            header('Location: /shop/');
            exit;
        } else {
            $error = "Невірний email або пароль";
        }
    }
    ob_start();
    require_once __DIR__ . '/views/auth/login.php';
    $content = ob_get_clean();
    require_once __DIR__ . '/views/layouts/main.php';
}
// Реєстрація
elseif ($path == 'auth/register') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $check->execute([$email]);
        if ($check->fetch()) {
            $error = "Email вже зареєстрований";
        } else {
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            if ($stmt->execute([$name, $email, $password])) {
                header('Location: /shop/auth/login');
                exit;
            } else {
                $error = "Помилка реєстрації";
            }
        }
    }
    ob_start();
    require_once __DIR__ . '/views/auth/register.php';
    $content = ob_get_clean();
    require_once __DIR__ . '/views/layouts/main.php';
}
// Вихід
elseif ($path == 'auth/logout') {
    session_destroy();
    header('Location: /shop/');
    exit;
}
// Кошик додати (JSON)
elseif ($path == 'cart/add') {
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents('php://input'), true);
    $product_id = $data['product_id'];
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    }
    
    echo json_encode(['success' => true, 'count' => array_sum($_SESSION['cart'])]);
    exit;
}
// Кошик перегляд
elseif ($path == 'cart') {
    $cartItems = [];
    $total = 0;
    
    if (!empty($_SESSION['cart'])) {
        $ids = implode(',', array_keys($_SESSION['cart']));
        $stmt = $pdo->query("SELECT * FROM products WHERE id IN ($ids)");
        $products = $stmt->fetchAll();
        
        foreach ($products as $product) {
            $quantity = $_SESSION['cart'][$product['id']];
            $subtotal = $product['price'] * $quantity;
            $total += $subtotal;
            $cartItems[] = [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $subtotal
            ];
        }
    }
    
    ob_start();
    require_once __DIR__ . '/views/cart/index.php';
    $content = ob_get_clean();
    require_once __DIR__ . '/views/layouts/main.php';
}
// Оформлення замовлення
elseif ($path == 'cart/checkout') {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /shop/auth/login');
        exit;
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $total = $_POST['total'];
        
        if (!empty($_SESSION['cart'])) {
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, total, status) VALUES (?, ?, 'new')");
            $stmt->execute([$_SESSION['user_id'], $total]);
            $order_id = $pdo->lastInsertId();
            
            foreach ($_SESSION['cart'] as $product_id => $quantity) {
                $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
                $stmt->execute([$product_id]);
                $product = $stmt->fetch();
                if ($product) {
                    $price = $product['price'];
                    $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$order_id, $product_id, $quantity, $price]);
                }
            }
            
            unset($_SESSION['cart']);
            $_SESSION['success'] = "Замовлення №" . $order_id . " успішно оформлено!";
            header('Location: /shop/');
            exit;
        } else {
            header('Location: /shop/cart');
            exit;
        }
    }
}
// 404
else {
    http_response_code(404);
    echo "<h1>404 - Сторінку не знайдено</h1>";
    echo "<a href='/shop/'>На головну</a>";
}
?>