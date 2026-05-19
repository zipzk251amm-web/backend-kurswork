<div class="auth-form">
    <h2>Реєстрація</h2>
    <?php if(isset($error)): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST">
        <input type="text" name="name" placeholder="Ім'я" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Пароль" required>
        <button type="submit">Зареєструватися</button>
    </form>
    <p>Вже є акаунт? <a href="/shop/auth/login">Увійти</a></p>
</div>