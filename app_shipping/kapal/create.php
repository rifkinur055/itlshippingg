<?php
$page_title = 'Tambah Kapal - ITL Shipping';
require_once __DIR__ . '/../includes/auth.php';
checkRole(['admin', 'petugas']);
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama_kapal']);
    $kode = trim($_POST['kode_kapal']);
    $jenis = trim($_POST['jenis_kapal']);
    $bendera = trim($_POST['bendera']);
    $kapasitas = (int)$_POST['kapasitas_ton'];
    $pemilik = trim($_POST['pemilik']);
    $keterangan = trim($_POST['keterangan']);
    $status = $_POST['status'];

    // Check duplicate kode
    $check = $pdo->prepare("SELECT COUNT(*) FROM kapal WHERE kode_kapal = ?");
    $check->execute([$kode]);
    if ($check->fetchColumn() > 0) {
        setFlash('error', 'Kode kapal sudah digunakan.');
        header('Location: /app_shipping/kapal/create.php');
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO kapal (nama_kapal, kode_kapal, jenis_kapal, bendera, kapasitas_ton, pemilik, keterangan, status) VALUES (?,?,?,?,?,?,?,?)");
    $stmt->execute([$nama, $kode, $jenis, $bendera, $kapasitas, $pemilik, $keterangan, $status]);

    setFlash('success', 'Kapal berhasil ditambahkan.');
    header('Location: /app_shipping/kapal/index.php');
    exit;
}

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="page-header">
    <div>
        <h2><i class="bi bi-plus-circle"></i> Tambah Kapal</h2>
        <span class="breadcrumb-custom"><a href="/app_shipping/index.php">Dashboard</a> / <a href="/app_shipping/kapal/index.php">Kapal</a> / Tambah</span>
    </div>
</div>

<div class="card-custom" style="max-width: 800px;">
    <div class="card-body-custom">
        <form method="POST" action="">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label-custom">Nama Kapal <span class="text-danger">*</span></label>
                    <input type="text" name="nama_kapal" class="form-control form-control-custom" required placeholder="MV Nusantara Jaya">
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Kode Kapal <span class="text-danger">*</span></label>
                    <input type="text" name="kode_kapal" class="form-control form-control-custom" required placeholder="ITL-001">
                </div>
                <div class="col-md-4">
                    <label class="form-label-custom">Jenis Kapal</label>
                    <select name="jenis_kapal" class="form-select form-select-custom">
                        <option value="">-- Pilih Jenis --</option>
                        <option value="Cargo">Cargo</option>
                        <option value="Container">Container</option>
                        <option value="Tanker">Tanker</option>
                        <option value="Bulk Carrier">Bulk Carrier</option>
                        <option value="General Cargo">General Cargo</option>
                        <option value="Passenger">Passenger</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label-custom">Bendera</label>
                    <input type="text" name="bendera" class="form-control form-control-custom" placeholder="Indonesia">
                </div>
                <div class="col-md-4">
                    <label class="form-label-custom">Kapasitas (Ton)</label>
                    <input type="number" name="kapasitas_ton" class="form-control form-control-custom" placeholder="0" min="0">
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Pemilik</label>
                    <input type="text" name="pemilik" class="form-control form-control-custom" placeholder="PT. ITL Shipping Agency">
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Status</label>
                    <select name="status" class="form-select form-select-custom" required>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Non-Aktif</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label-custom">Keterangan</label>
                    <textarea name="keterangan" class="form-control form-control-custom" rows="3" placeholder="Keterangan tambahan..."></textarea>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary-custom"><i class="bi bi-check-lg"></i> Simpan</button>
                <a href="/app_shipping/kapal/index.php" class="btn btn-secondary ms-2">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
