<div class="auth-form">
    <h2>Вхід</h2>
    <?php if(isset($error)): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Пароль" required>
        <button type="submit">Увійти</button>
    </form>
    <p>Немає акаунта? <a href="/shop/auth/register">Зареєструватися</a></p>
</div>