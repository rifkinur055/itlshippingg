<?php
$page_title = 'Edit Kapal - ITL Shipping';
require_once __DIR__ . '/../includes/auth.php';
checkRole(['admin', 'petugas']);
require_once __DIR__ . '/../config/database.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM kapal WHERE id = ?");
$stmt->execute([$id]);
$kapal = $stmt->fetch();

if (!$kapal) {
    setFlash('error', 'Kapal tidak ditemukan.');
    header('Location: /app_shipping/kapal/index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama_kapal']);
    $jenis = trim($_POST['jenis_kapal']);
    $bendera = trim($_POST['bendera']);
    $kapasitas = (int)$_POST['kapasitas_ton'];
    $pemilik = trim($_POST['pemilik']);
    $keterangan = trim($_POST['keterangan']);
    $status = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE kapal SET nama_kapal=?, jenis_kapal=?, bendera=?, kapasitas_ton=?, pemilik=?, keterangan=?, status=? WHERE id=?");
    $stmt->execute([$nama, $jenis, $bendera, $kapasitas, $pemilik, $keterangan, $status, $id]);

    setFlash('success', 'Kapal berhasil diperbarui.');
    header('Location: /app_shipping/kapal/index.php');
    exit;
}

$jenis_list = ['Cargo','Container','Tanker','Bulk Carrier','General Cargo','Passenger'];

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="page-header">
    <div>
        <h2><i class="bi bi-pencil-square"></i> Edit Kapal</h2>
        <span class="breadcrumb-custom"><a href="/app_shipping/index.php">Dashboard</a> / <a href="/app_shipping/kapal/index.php">Kapal</a> / Edit</span>
    </div>
</div>

<div class="card-custom" style="max-width: 800px;">
    <div class="card-body-custom">
        <form method="POST" action="">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label-custom">Nama Kapal <span class="text-danger">*</span></label>
                    <input type="text" name="nama_kapal" class="form-control form-control-custom" required value="<?= htmlspecialchars($kapal['nama_kapal']) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Kode Kapal</label>
                    <input type="text" class="form-control form-control-custom" value="<?= htmlspecialchars($kapal['kode_kapal']) ?>" disabled>
                </div>
                <div class="col-md-4">
                    <label class="form-label-custom">Jenis Kapal</label>
                    <select name="jenis_kapal" class="form-select form-select-custom">
                        <option value="">-- Pilih Jenis --</option>
                        <?php foreach ($jenis_list as $j): ?>
                        <option value="<?= $j ?>" <?= $kapal['jenis_kapal'] === $j ? 'selected' : '' ?>><?= $j ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label-custom">Bendera</label>
                    <input type="text" name="bendera" class="form-control form-control-custom" value="<?= htmlspecialchars($kapal['bendera'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label-custom">Kapasitas (Ton)</label>
                    <input type="number" name="kapasitas_ton" class="form-control form-control-custom" value="<?= $kapal['kapasitas_ton'] ?>" min="0">
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Pemilik</label>
                    <input type="text" name="pemilik" class="form-control form-control-custom" value="<?= htmlspecialchars($kapal['pemilik'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Status</label>
                    <select name="status" class="form-select form-select-custom" required>
                        <option value="aktif" <?= $kapal['status'] === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                        <option value="nonaktif" <?= $kapal['status'] === 'nonaktif' ? 'selected' : '' ?>>Non-Aktif</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label-custom">Keterangan</label>
                    <textarea name="keterangan" class="form-control form-control-custom" rows="3"><?= htmlspecialchars($kapal['keterangan'] ?? '') ?></textarea>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary-custom"><i class="bi bi-check-lg"></i> Update</button>
                <a href="/app_shipping/kapal/index.php" class="btn btn-secondary ms-2">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
