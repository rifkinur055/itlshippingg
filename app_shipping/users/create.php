<?php
$page_title = 'Tambah User - ITL Shipping';
require_once __DIR__ . '/../includes/auth.php';
checkRole(['admin']);
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $nama = trim($_POST['nama_lengkap']);
    $email = trim($_POST['email']);
    $telepon = trim($_POST['no_telepon']);
    $level = $_POST['level'];

    // Check duplicate username
    $check = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $check->execute([$username]);
    if ($check->fetchColumn() > 0) {
        setFlash('error', 'Username sudah digunakan.');
        header('Location: /app_shipping/users/create.php');
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO users (username, password, nama_lengkap, email, no_telepon, level) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$username, $password, $nama, $email, $telepon, $level]);

    setFlash('success', 'User berhasil ditambahkan.');
    header('Location: /app_shipping/users/index.php');
    exit;
}

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="page-header">
    <div>
        <h2><i class="bi bi-person-plus"></i> Tambah User</h2>
        <span class="breadcrumb-custom"><a href="/app_shipping/index.php">Dashboard</a> / <a href="/app_shipping/users/index.php">Users</a> / Tambah</span>
    </div>
</div>

<div class="card-custom" style="max-width: 700px;">
    <div class="card-body-custom">
        <form method="POST" action="">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label-custom">Username <span class="text-danger">*</span></label>
                    <input type="text" name="username" class="form-control form-control-custom" required placeholder="Username login">
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control form-control-custom" required placeholder="Minimal 6 karakter" minlength="6">
                </div>
                <div class="col-12">
                    <label class="form-label-custom">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama_lengkap" class="form-control form-control-custom" required placeholder="Nama lengkap user">
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Email</label>
                    <input type="email" name="email" class="form-control form-control-custom" placeholder="email@example.com">
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">No. Telepon</label>
                    <input type="text" name="no_telepon" class="form-control form-control-custom" placeholder="08xxxxxxxxxx">
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Level <span class="text-danger">*</span></label>
                    <select name="level" class="form-select form-select-custom" required>
                        <option value="">-- Pilih Level --</option>
                        <option value="admin">Admin</option>
                        <option value="pimpinan">Pimpinan</option>
                        <option value="petugas">Petugas</option>
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary-custom">
                    <i class="bi bi-check-lg"></i> Simpan
                </button>
                <a href="/app_shipping/users/index.php" class="btn btn-secondary ms-2">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
