<?php
$page_title = 'Tambah Kedatangan - ITL Shipping';
require_once __DIR__ . '/../includes/auth.php';
checkRole(['admin', 'petugas']);
require_once __DIR__ . '/../config/database.php';

$kapal_list = $pdo->query("SELECT id, nama_kapal, kode_kapal FROM kapal WHERE status='aktif' ORDER BY nama_kapal")->fetchAll();
$keberangkatan_list = $pdo->query("
    SELECT k.id, k.pelabuhan_asal, k.pelabuhan_tujuan, k.tanggal_berangkat, kp.nama_kapal, kp.kode_kapal
    FROM keberangkatan k
    JOIN kapal kp ON k.id_kapal = kp.id
    WHERE k.status IN ('berlayar','dijadwalkan')
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
    $created_by = getCurrentUserId();

    $stmt = $pdo->prepare("INSERT INTO kedatangan (id_kapal, id_keberangkatan, pelabuhan_asal, pelabuhan_tiba, tanggal_tiba, tanggal_aktual_tiba, status, catatan, created_by) VALUES (?,?,?,?,?,?,?,?,?)");
    $stmt->execute([$id_kapal, $id_keberangkatan, $asal, $tiba, $tanggal, $tanggal_aktual, $status, $catatan, $created_by]);

    setFlash('success', 'Data kedatangan berhasil ditambahkan.');
    header('Location: /app_shipping/kedatangan/index.php');
    exit;
}

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="page-header">
    <div>
        <h2><i class="bi bi-plus-circle"></i> Tambah Kedatangan</h2>
        <span class="breadcrumb-custom"><a href="/app_shipping/index.php">Dashboard</a> / <a href="/app_shipping/kedatangan/index.php">Kedatangan</a> / Tambah</span>
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
                        <option value="<?= $kp['id'] ?>"><?= htmlspecialchars($kp['kode_kapal'] . ' - ' . $kp['nama_kapal']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Terkait Keberangkatan <small class="text-muted">(opsional)</small></label>
                    <select name="id_keberangkatan" class="form-select form-select-custom">
                        <option value="">-- Tidak Terkait --</option>
                        <?php foreach ($keberangkatan_list as $kb): ?>
                        <option value="<?= $kb['id'] ?>">
                            <?= htmlspecialchars($kb['kode_kapal'] . ' | ' . $kb['pelabuhan_asal'] . ' → ' . $kb['pelabuhan_tujuan'] . ' (' . date('d/m/Y', strtotime($kb['tanggal_berangkat'])) . ')') ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Pelabuhan Asal <span class="text-danger">*</span></label>
                    <input type="text" name="pelabuhan_asal" class="form-control form-control-custom" required placeholder="Pelabuhan asal kapal">
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Pelabuhan Tiba <span class="text-danger">*</span></label>
                    <input type="text" name="pelabuhan_tiba" class="form-control form-control-custom" required placeholder="Pelabuhan tujuan tiba">
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Estimasi Tanggal Tiba <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="tanggal_tiba" class="form-control form-control-custom" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Tanggal Aktual Tiba <small class="text-muted">(jika sudah tiba)</small></label>
                    <input type="datetime-local" name="tanggal_aktual_tiba" class="form-control form-control-custom">
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Status</label>
                    <select name="status" class="form-select form-select-custom" required>
                        <option value="menunggu">Menunggu</option>
                        <option value="tiba">Tiba</option>
                        <option value="terlambat">Terlambat</option>
                        <option value="batal">Batal</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label-custom">Catatan</label>
                    <textarea name="catatan" class="form-control form-control-custom" rows="3" placeholder="Catatan tambahan..."></textarea>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary-custom"><i class="bi bi-check-lg"></i> Simpan</button>
                <a href="/app_shipping/kedatangan/index.php" class="btn btn-secondary ms-2">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
