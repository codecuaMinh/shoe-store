<?php
session_start();
require_once '../includes/db.php';

// Thống kê tổng quan
$total_orders   = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$total_revenue  = $pdo->query("SELECT SUM(total) FROM orders WHERE status = 'paid'")->fetchColumn() ?? 0;
$total_products = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$low_stock      = $pdo->query("SELECT COUNT(*) FROM products WHERE stock < 10")->fetchColumn();

// Đơn hàng gần nhất
$recent_orders = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

// Doanh thu theo ngày (7 ngày gần nhất)
$revenue_chart = $pdo->query("
    SELECT DATE(created_at) as date, SUM(total) as revenue 
    FROM orders 
    WHERE status = 'paid' AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
    GROUP BY DATE(created_at)
    ORDER BY date ASC
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="/shoe-store/assets/css/style.css">
    <link rel="stylesheet" href="/shoe-store/assets/css/admin.css">
</head>
<body>
<div class="admin-layout">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-logo">👟 Admin</div>
        <nav>
            <a href="/shoe-store/admin/" class="active">📊 Dashboard</a>
            <a href="/shoe-store/admin/products.php">👟 Sản phẩm</a>
            <a href="/shoe-store/admin/orders.php">📦 Đơn hàng</a>
            <a href="/shoe-store/" target="_blank">🌐 Xem shop</a>
        </nav>
    </aside>

    <!-- Main -->
    <main class="admin-main">
        <div class="admin-header">
            <h1>Dashboard</h1>
            <span><?= date('d/m/Y') ?></span>
        </div>

        <!-- Metric Cards -->
        <div class="metric-grid">
            <div class="metric-card">
                <div class="metric-label">Tổng đơn hàng</div>
                <div class="metric-value"><?= $total_orders ?></div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Doanh thu</div>
                <div class="metric-value"><?= number_format($total_revenue, 0, ',', '.') ?> đ</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Sản phẩm</div>
                <div class="metric-value"><?= $total_products ?></div>
            </div>
            <div class="metric-card" style="border-left: 3px solid #e74c3c;">
                <div class="metric-label">Sắp hết hàng</div>
                <div class="metric-value" style="color:#e74c3c;"><?= $low_stock ?></div>
            </div>
        </div>

        <!-- Đơn hàng gần nhất -->
        <div class="admin-section">
            <h2>Đơn hàng gần nhất</h2>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Khách hàng</th>
                        <th>SĐT</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Thời gian</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_orders as $o): ?>
                    <tr>
                        <td>#<?= $o['id'] ?></td>
                        <td><?= htmlspecialchars($o['customer_name']) ?></td>
                        <td><?= htmlspecialchars($o['phone']) ?></td>
                        <td><?= number_format($o['total'], 0, ',', '.') ?> đ</td>
                        <td>
                            <span class="badge badge-<?= $o['status'] ?>">
                                <?= ['pending'=>'Chờ xử lý','paid'=>'Đã thanh toán','shipping'=>'Đang giao','done'=>'Hoàn thành'][$o['status']] ?? $o['status'] ?>
                            </span>
                        </td>
                        <td><?= date('d/m/Y H:i', strtotime($o['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Tồn kho -->
        <div class="admin-section">
            <h2>Tình trạng tồn kho</h2>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Giá</th>
                        <th>Tồn kho</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $products = $pdo->query("SELECT * FROM products ORDER BY stock ASC")->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($products as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['name']) ?></td>
                        <td><?= htmlspecialchars($p['category']) ?></td>
                        <td><?= number_format($p['price'], 0, ',', '.') ?> đ</td>
                        <td><?= $p['stock'] ?></td>
                        <td>
                            <span class="badge <?= $p['stock'] < 10 ? 'badge-pending' : 'badge-paid' ?>">
                                <?= $p['stock'] < 10 ? 'Sắp hết' : 'Còn hàng' ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
</body>
</html>