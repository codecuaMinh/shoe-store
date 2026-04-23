<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoe Store</title>
    <link rel="stylesheet" href="/shoe-store/assets/css/style.css">
</head>
<body>
<nav class="navbar">
    <a href="/shoe-store/" class="logo">👟 Shoe Store</a>
    <div class="nav-links">
        <a href="/shoe-store/">Trang chủ</a>
        <a href="/shoe-store/cart.php">
            Giỏ hàng
            <span class="cart-count">
                <?php echo isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; ?>
            </span>
        </a>
    </div>
</nav>