<?php
$page_title = 'Edit Kedatangan - ITL Shipping';
require_once __DIR__ . '/../includes/auth.php';
checkRole(['admin', 'petugas']);
require_once __DIR__ . '/../config/database.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM kedatangan WHERE id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch();

if (!$data) {
    setFlash('error', 'Data kedatangan tidak ditemukan.');
    header('Location: /app_shipping/kedatangan/index.php');
    exit;
}

$kapal_list = $pdo->query("SELECT id, nama_kapal, kode_kapal FROM kapal WHERE status='aktif' ORDER BY nama_kapal")->fetchAll();
$keberangkatan_list = $pdo->query("
    SELECT k.id, k.pelabuhan_asal, k.pelabuhan_tujuan, k.tanggal_berangkat, kp.nama_kapal, kp.kode_kapal
    FROM keberangkatan k
    JOIN kapal kp ON k.id_kapal = kp.id
    ORDER BY k.tanggal_berangkat DESC
")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_kapal = (int)$_POST['id_kapal'];
    $id_keberangkatan = !empty($_POST['id_keberangkatan']) ? (int)$_POST['id_keberangkatan'] : null;
    $asal = trim($_POST['pelabuhan_asal']);
    $tiba = trim($_POST['pelabuhan_tiba']);
    $tanggal = $_POST['tanggal_tiba'];
    $tanggal_aktual = !empty($_POST['tanggal_aktual_tiba']) ? $_POST['tanggal_aktual_tiba'] : null;
    $status = $_POST['status'];
    $catatan = trim($_POST['catatan']);

    $stmt = $pdo->prepare("UPDATE kedatangan SET id_kapal=?, id_keberangkatan=?, pelabuhan_asal=?, pelabuhan_tiba=?, tanggal_tiba=?, tanggal_aktual_tiba=?, status=?, catatan=? WHERE id=?");
    $stmt->execute([$id_kapal, $id_keberangkatan, $asal, $tiba, $tanggal, $tanggal_aktual, $status, $catatan, $id]);

    setFlash('success', 'Data kedatangan berhasil diperbarui.');
    header('Location: /app_shipping/kedatangan/index.php');
    exit;
}

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="page-header">
    <div>
        <h2><i class="bi bi-pencil-square"></i> Edit Kedatangan</h2>
        <span class="breadcrumb-custom"><a href="/app_shipping/index.php">Dashboard</a> / <a href="/app_shipping/kedatangan/index.php">Kedatangan</a> / Edit</span>
    </div>
</div>

<div class="card-custom" style="max-width: 800px;">
    <div class="card-body-custom">
        <form method="POST" action="">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label-custom">Kapal <span class="text-danger">*</span></label>
                    <select name="id_kapal" class="form-select form-select-custom" required>
                        <option value="">-- Pilih Kapal --</option>
                        <?php foreach ($kapal_list as $kp): ?>
                        <option value="<?= $kp['id'] ?>" <?= $data['id_kapal'] == $kp['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($kp['kode_kapal'] . ' - ' . $kp['nama_kapal']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Terkait Keberangkatan</label>
                    <select name="id_keberangkatan" class="form-select form-select-custom">
                        <option value="">-- Tidak Terkait --</option>
                        <?php foreach ($keberangkatan_list as $kb): ?>
                        <option value="<?= $kb['id'] ?>" <?= $data['id_keberangkatan'] == $kb['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($kb['kode_kapal'] . ' | ' . $kb['pelabuhan_asal'] . ' → ' . $kb['pelabuhan_tujuan']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Pelabuhan Asal <span class="text-danger">*</span></label>
                    <input type="text" name="pelabuhan_asal" class="form-control form-control-custom" required value="<?= htmlspecialchars($data['pelabuhan_asal']) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Pelabuhan Tiba <span class="text-danger">*</span></label>
                    <input type="text" name="pelabuhan_tiba" class="form-control form-control-custom" required value="<?= htmlspecialchars($data['pelabuhan_tiba']) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Estimasi Tanggal Tiba <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="tanggal_tiba" class="form-control form-control-custom" required value="<?= date('Y-m-d\TH:i', strtotime($data['tanggal_tiba'])) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Tanggal Aktual Tiba</label>
                    <input type="datetime-local" name="tanggal_aktual_tiba" class="form-control form-control-custom" value="<?= $data['tanggal_aktual_tiba'] ? date('Y-m-d\TH:i', strtotime($data['tanggal_aktual_tiba'])) : '' ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Status</label>
                    <select name="status" class="form-select form-select-custom" required>
                        <option value="menunggu" <?= $data['status'] === 'menunggu' ? 'selected' : '' ?>>Menunggu</option>
                        <option value="tiba" <?= $data['status'] === 'tiba' ? 'selected' : '' ?>>Tiba</option>
                        <option value="terlambat" <?= $data['status'] === 'terlambat' ? 'selected' : '' ?>>Terlambat</option>
                        <option value="batal" <?= $data['status'] === 'batal' ? 'selected' : '' ?>>Batal</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label-custom">Catatan</label>
                    <textarea name="catatan" class="form-control form-control-custom" rows="3"><?= htmlspecialchars($data['catatan'] ?? '') ?></textarea>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary-custom"><i class="bi bi-check-lg"></i> Update</button>
                <a href="/app_shipping/kedatangan/index.php" class="btn btn-secondary ms-2">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
