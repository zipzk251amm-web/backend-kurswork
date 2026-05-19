<div class="hero">
    <?php if(isset($_SESSION['success'])): ?>
        <div style="background: #28a745; color: white; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    
    <h2>Ласкаво просимо до нашого магазину!</h2>
    <p>Найкраща електроніка за найкращими цінами</p>
</div>

<div class="products">
    <h3>Популярні товари</h3>
    <div class="product-grid">
        <?php foreach($products as $product): ?>
        <div class="product-card">
            <h4><?php echo htmlspecialchars($product['name']); ?></h4>
            <p><?php echo number_format($product['price'], 2); ?> грн</p>
            <button class="add-to-cart" data-id="<?php echo $product['id']; ?>">🛒 Купити</button>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="news">
    <h3>Новини та акції</h3>
    <?php foreach($news as $item): ?>
    <div class="news-item">
        <h4><?php echo htmlspecialchars($item['title']); ?></h4>
        <p><?php echo htmlspecialchars($item['content']); ?></p>
    </div>
    <?php endforeach; ?>
</div>