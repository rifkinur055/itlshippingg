<?php
$page_title = 'Tambah Keberangkatan - ITL Shipping';
require_once __DIR__ . '/../includes/auth.php';
checkRole(['admin', 'petugas']);
require_once __DIR__ . '/../config/database.php';

$kapal_list = $pdo->query("SELECT id, nama_kapal, kode_kapal FROM kapal WHERE status='aktif' ORDER BY nama_kapal")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_kapal = (int)$_POST['id_kapal'];
    $asal = trim($_POST['pelabuhan_asal']);
    $tujuan = trim($_POST['pelabuhan_tujuan']);
    $tanggal = $_POST['tanggal_berangkat'];
    $muatan = trim($_POST['muatan']);
    $berat = (int)$_POST['berat_muatan'];
    $status = $_POST['status'];
    $catatan = trim($_POST['catatan']);
    $created_by = getCurrentUserId();

    $stmt = $pdo->prepare("INSERT INTO keberangkatan (id_kapal, pelabuhan_asal, pelabuhan_tujuan, tanggal_berangkat, muatan, berat_muatan, status, catatan, created_by) VALUES (?,?,?,?,?,?,?,?,?)");
    $stmt->execute([$id_kapal, $asal, $tujuan, $tanggal, $muatan, $berat, $status, $catatan, $created_by]);

    setFlash('success', 'Jadwal keberangkatan berhasil ditambahkan.');
    header('Location: /app_shipping/keberangkatan/index.php');
    exit;
}

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="page-header">
    <div>
        <h2><i class="bi bi-plus-circle"></i> Tambah Jadwal Keberangkatan</h2>
        <span class="breadcrumb-custom"><a href="/app_shipping/index.php">Dashboard</a> / <a href="/app_shipping/keberangkatan/index.php">Keberangkatan</a> / Tambah</span>
    </div>
</div>

<div class="card-custom" style="max-width: 800px;">
    <div class="card-body-custom">
        <form method="POST" action="">
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label-custom">Kapal <span class="text-danger">*</span></label>
                    <select name="id_kapal" class="form-select form-select-custom" required>
                        <option value="">-- Pilih Kapal --</option>
                        <?php foreach ($kapal_list as $kp): ?>
                        <option value="<?= $kp['id'] ?>"><?= htmlspecialchars($kp['kode_kapal'] . ' - ' . $kp['nama_kapal']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Pelabuhan Asal <span class="text-danger">*</span></label>
                    <input type="text" name="pelabuhan_asal" class="form-control form-control-custom" required placeholder="Tanjung Priok, Jakarta">
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Pelabuhan Tujuan <span class="text-danger">*</span></label>
                    <input type="text" name="pelabuhan_tujuan" class="form-control form-control-custom" required placeholder="Tanjung Perak, Surabaya">
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Tanggal & Waktu Berangkat <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="tanggal_berangkat" class="form-control form-control-custom" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Status</label>
                    <select name="status" class="form-select form-select-custom" required>
                        <option value="dijadwalkan">Dijadwalkan</option>
                        <option value="berlayar">Berlayar</option>
                        <option value="selesai">Selesai</option>
                        <option value="batal">Batal</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Muatan</label>
                    <input type="text" name="muatan" class="form-control form-control-custom" placeholder="Batu bara, CPO, dll">
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Berat Muatan (Ton)</label>
                    <input type="number" name="berat_muatan" class="form-control form-control-custom" placeholder="0" min="0">
                </div>
                <div class="col-12">
                    <label class="form-label-custom">Catatan</label>
                    <textarea name="catatan" class="form-control form-control-custom" rows="3" placeholder="Catatan tambahan..."></textarea>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary-custom"><i class="bi bi-check-lg"></i> Simpan</button>
                <a href="/app_shipping/keberangkatan/index.php" class="btn btn-secondary ms-2">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
