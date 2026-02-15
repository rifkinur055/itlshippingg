<?php
$page_title = 'Edit User - ITL Shipping';
require_once __DIR__ . '/../includes/auth.php';
checkRole(['admin']);
require_once __DIR__ . '/../config/database.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    setFlash('error', 'User tidak ditemukan.');
    header('Location: /app_shipping/users/index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama_lengkap']);
    $email = trim($_POST['email']);
    $telepon = trim($_POST['no_telepon']);
    $level = $_POST['level'];

    // Update with or without password
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("UPDATE users SET nama_lengkap=?, email=?, no_telepon=?, level=?, password=? WHERE id=?");
        $stmt->execute([$nama, $email, $telepon, $level, $password, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET nama_lengkap=?, email=?, no_telepon=?, level=? WHERE id=?");
        $stmt->execute([$nama, $email, $telepon, $level, $id]);
    }

    setFlash('success', 'User berhasil diperbarui.');
    header('Location: /app_shipping/users/index.php');
    exit;
}

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="page-header">
    <div>
        <h2><i class="bi bi-pencil-square"></i> Edit User</h2>
        <span class="breadcrumb-custom"><a href="/app_shipping/index.php">Dashboard</a> / <a href="/app_shipping/users/index.php">Users</a> / Edit</span>
    </div>
</div>

<div class="card-custom" style="max-width: 700px;">
    <div class="card-body-custom">
        <form method="POST" action="">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label-custom">Username</label>
                    <input type="text" class="form-control form-control-custom" value="<?= htmlspecialchars($user['username']) ?>" disabled>
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Password Baru <small class="text-muted">(kosongkan jika tidak diubah)</small></label>
                    <input type="password" name="password" class="form-control form-control-custom" placeholder="Password baru" minlength="6">
                </div>
                <div class="col-12">
                    <label class="form-label-custom">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama_lengkap" class="form-control form-control-custom" required value="<?= htmlspecialchars($user['nama_lengkap']) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Email</label>
                    <input type="email" name="email" class="form-control form-control-custom" value="<?= htmlspecialchars($user['email'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">No. Telepon</label>
                    <input type="text" name="no_telepon" class="form-control form-control-custom" value="<?= htmlspecialchars($user['no_telepon'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Level <span class="text-danger">*</span></label>
                    <select name="level" class="form-select form-select-custom" required>
                        <option value="admin" <?= $user['level'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="pimpinan" <?= $user['level'] === 'pimpinan' ? 'selected' : '' ?>>Pimpinan</option>
                        <option value="petugas" <?= $user['level'] === 'petugas' ? 'selected' : '' ?>>Petugas</option>
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary-custom">
                    <i class="bi bi-check-lg"></i> Update
                </button>
                <a href="/app_shipping/users/index.php" class="btn btn-secondary ms-2">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
