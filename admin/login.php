<?php
session_start();

// Nếu đã đăng nhập thì vào dashboard luôn
if (isset($_SESSION['admin_logged_in'])) {
    header("Location: /shoe-store/admin/");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Tài khoản admin mặc định
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['admin_logged_in'] = true;
        header("Location: /shoe-store/admin/");
        exit;
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login – Shoe Store</title>
    <link rel="stylesheet" href="/shoe-store/assets/css/style.css">
    <link rel="stylesheet" href="/shoe-store/assets/css/admin.css">
</head>
<body style="background:#f5f5f5;display:flex;align-items:center;justify-content:center;min-height:100vh;">
    <div style="background:#fff;border-radius:16px;padding:40px;width:360px;box-shadow:0 4px 24px rgba(0,0,0,0.08);">
        <div style="text-align:center;margin-bottom:28px;">
            <div style="font-size:40px;margin-bottom:8px;">👟</div>
            <h1 style="font-size:20px;font-weight:600;">Shoe Store Admin</h1>
            <p style="color:#888;font-size:14px;margin-top:4px;">Sign in to continue</p>
        </div>

        <?php if ($error): ?>
        <div style="background:#fee;border:1px solid #fcc;color:#c00;padding:10px 14px;border-radius:8px;font-size:13px;margin-bottom:16px;">
            ⚠ <?= $error ?>
        </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label style="font-size:13px;color:#888;display:block;margin-bottom:6px;">Username</label>
                <input type="text" name="username" class="form-input" placeholder="admin" required autofocus>
            </div>
            <div class="form-group">
                <label style="font-size:13px;color:#888;display:block;margin-bottom:6px;">Password</label>
                <input type="password" name="password" class="form-input" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn" style="width:100%;padding:12px;font-size:15px;margin-top:8px;">
                Login →
            </button>
        </form>
    </div>
</body>
</html>