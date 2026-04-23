<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/header.php';

$stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <h2 style="margin-bottom:24px;font-size:24px;">Bộ sưu tập giày</h2>
    <div class="product-grid">
        <?php foreach ($products as $p): ?>
        <div class="product-card" onclick="location.href='product.php?id=<?= $p['id'] ?>'">
            <img src="<?= htmlspecialchars($p['image_url']) ?>" 
                 alt="<?= htmlspecialchars($p['name']) ?>"
                 onerror="this.src='assets/images/no-image.png'">
            <div class="product-info">
                <div class="product-name"><?= htmlspecialchars($p['name']) ?></div>
                <div class="product-category"><?= htmlspecialchars($p['category']) ?></div>
                <div class="product-price">
                    <?= number_format($p['price'], 0, ',', '.') ?> đ
                </div>
                <div class="product-stock">Còn <?= $p['stock'] ?> đôi</div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>