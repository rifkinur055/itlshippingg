<?php
session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: /app_shipping/index.php');
    exit;
}

require_once __DIR__ . '/config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Username dan password harus diisi.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_username'] = $user['username'];
            $_SESSION['user_nama'] = $user['nama_lengkap'];
            $_SESSION['user_level'] = $user['level'];
            $_SESSION['user_foto'] = $user['foto'];

            header('Location: /app_shipping/index.php');
            exit;
        } else {
            $error = 'Username atau password salah.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ITL Shipping Agency</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="/app_shipping/assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="login-page">
        <div class="login-card">
            <div class="login-logo">
                <i class="bi bi-tsunami"></i>
                <h3>ITL Shipping Agency</h3>
                <p>PT. Internasional Total Service & Logistics</p>
            </div>

            <?php if ($error): ?>
                <div class="login-error">
                    <i class="bi bi-exclamation-circle me-1"></i><?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label-custom">Username</label>
                    <div class="input-group">
                        <span class="input-group-text" style="border-radius: 8px 0 0 8px; border: 1.5px solid #e2e8f0; border-right: none; background: #f8fafc;">
                            <i class="bi bi-person text-secondary"></i>
                        </span>
                        <input type="text" name="username" class="form-control form-control-custom" 
                               style="border-radius: 0 8px 8px 0;" 
                               placeholder="Masukkan username" 
                               value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" 
                               required autofocus>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label-custom">Password</label>
                    <div class="input-group">
                        <span class="input-group-text" style="border-radius: 8px 0 0 8px; border: 1.5px solid #e2e8f0; border-right: none; background: #f8fafc;">
                            <i class="bi bi-lock text-secondary"></i>
                        </span>
                        <input type="password" name="password" class="form-control form-control-custom" 
                               style="border-radius: 0 8px 8px 0;"
                               placeholder="Masukkan password" 
                               required>
                    </div>
                </div>
                <button type="submit" class="login-btn">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                </button>
            </form>

            <div class="text-center mt-4">
                <small style="color: #94a3b8;">&copy; <?= date('Y') ?> PT. ITL Shipping Agency</small>
            </div>
        </div>
    </div>
</body>
</html>
