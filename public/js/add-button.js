document.addEventListener('DOMContentLoaded', () => {
    document.body.addEventListener('click', (event) => {
        if (event.target.matches('#add-product-btn')) {
            window.location.href = '/add-product';
        }
    });
});
