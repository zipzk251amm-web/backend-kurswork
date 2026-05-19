<div class="cart">
    <h2>Мій кошик</h2>
    
    <?php if(empty($cartItems)): ?>
        <p>Кошик порожній</p>
        <a href="/shop/">Продовжити покупки</a>
    <?php else: ?>
        <table>
            <tr>
                <th>Товар</th>
                <th>Ціна</th>
                <th>Кількість</th>
                <th>Сума</th>
            </tr>
            <?php foreach($cartItems as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['product']['name']); ?></td>
                <td><?php echo number_format($item['product']['price'], 2); ?> грн</td>
                <td><?php echo $item['quantity']; ?></td>
                <td><?php echo number_format($item['subtotal'], 2); ?> грн</td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3"><strong>Всього:</strong></td>
                <td><strong><?php echo number_format($total, 2); ?> грн</strong></td>
            </tr>
        </table>
        
        <form method="POST" action="/shop/cart/checkout">
            <input type="hidden" name="total" value="<?php echo $total; ?>">
            <button type="submit">Оформити замовлення</button>
        </form>
    <?php endif; ?>
</div>