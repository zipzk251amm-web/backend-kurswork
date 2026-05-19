// Асинхронне додавання в кошик (JSON)
document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', async function() {
        const productId = this.dataset.id;
        
        const response = await fetch('/shop/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ product_id: productId })
        });
        
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('cart-count').innerText = data.count;
            alert('Товар додано в кошик!');
        }
    });
});