<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Інтернет-магазин електроніки</title>
    <link rel="stylesheet" href="/shop/css/style.css">
</head>
<body>
    <header>
        <h1>🖥️ Інтернет-магазин електроніки</h1>
        <nav>
            <a href="/shop/">Головна</a>
            <a href="/shop/cart">Кошик (<span id="cart-count">0</span>)</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <span>Вітаю, <?php echo $_SESSION['user_name']; ?></span>
                <?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin'): ?>
                    <a href="/shop/admin/products">Адмінка</a>
                <?php endif; ?>
                <a href="/shop/auth/logout">Вихід</a>
            <?php else: ?>
                <a href="/shop/auth/login">Вхід</a>
                <a href="/shop/auth/register">Реєстрація</a>
            <?php endif; ?>
        </nav>
    </header>
    <main>
        <?php echo $content; ?>
    </main>
    <footer>
        <p>© 2026 Інтернет-магазин електроніки. Курсова робота</p>
    </footer>
<script src="/shop/js/app.js"></script>
</body>
</html>