<?php
$page_title = 'Laporan - ITL Shipping';
require_once __DIR__ . '/../includes/auth.php';
checkRole(['admin', 'pimpinan']);
require_once __DIR__ . '/../config/database.php';

// Filters
$filter_tipe = $_GET['tipe'] ?? 'keberangkatan';
$filter_kapal = $_GET['kapal'] ?? '';
$filter_status = $_GET['status'] ?? '';
$filter_dari = $_GET['dari'] ?? '';
$filter_sampai = $_GET['sampai'] ?? '';

$kapal_list = $pdo->query("SELECT id, nama_kapal, kode_kapal FROM kapal ORDER BY nama_kapal")->fetchAll();

// Build query based on filter type
if ($filter_tipe === 'kedatangan') {
    $sql = "SELECT kd.*, kp.nama_kapal, kp.kode_kapal, u.nama_lengkap as pencatat
            FROM kedatangan kd
            JOIN kapal kp ON kd.id_kapal = kp.id
            LEFT JOIN users u ON kd.created_by = u.id
            WHERE 1=1";
    $params = [];

    if ($filter_kapal) {
        $sql .= " AND kd.id_kapal = ?";
        $params[] = $filter_kapal;
    }
    if ($filter_status) {
        $sql .= " AND kd.status = ?";
        $params[] = $filter_status;
    }
    if ($filter_dari) {
        $sql .= " AND DATE(kd.tanggal_tiba) >= ?";
        $params[] = $filter_dari;
    }
    if ($filter_sampai) {
        $sql .= " AND DATE(kd.tanggal_tiba) <= ?";
        $params[] = $filter_sampai;
    }
    $sql .= " ORDER BY kd.tanggal_tiba DESC";

    $status_options = ['menunggu', 'tiba', 'terlambat', 'batal'];
} else {
    $sql = "SELECT k.*, kp.nama_kapal, kp.kode_kapal, u.nama_lengkap as pencatat
            FROM keberangkatan k
            JOIN kapal kp ON k.id_kapal = kp.id
            LEFT JOIN users u ON k.created_by = u.id
            WHERE 1=1";
    $params = [];

    if ($filter_kapal) {
        $sql .= " AND k.id_kapal = ?";
        $params[] = $filter_kapal;
    }
    if ($filter_status) {
        $sql .= " AND k.status = ?";
        $params[] = $filter_status;
    }
    if ($filter_dari) {
        $sql .= " AND DATE(k.tanggal_berangkat) >= ?";
        $params[] = $filter_dari;
    }
    if ($filter_sampai) {
        $sql .= " AND DATE(k.tanggal_berangkat) <= ?";
        $params[] = $filter_sampai;
    }
    $sql .= " ORDER BY k.tanggal_berangkat DESC";

    $status_options = ['dijadwalkan', 'berlayar', 'selesai', 'batal'];
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$data = $stmt->fetchAll();

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="page-header">
    <div>
        <h2><i class="bi bi-file-earmark-bar-graph"></i> Laporan</h2>
        <span class="breadcrumb-custom"><a href="/app_shipping/index.php">Dashboard</a> / Laporan</span>
    </div>
    <button onclick="window.print()" class="btn btn-primary-custom">
        <i class="bi bi-printer"></i> Cetak
    </button>
</div>

<!-- Filter Form -->
<div class="card-custom mb-3">
    <div class="card-body-custom">
        <form method="GET" action="" class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label-custom">Tipe Laporan</label>
                <select name="tipe" class="form-select form-select-custom" onchange="this.form.submit()">
                    <option value="keberangkatan" <?= $filter_tipe === 'keberangkatan' ? 'selected' : '' ?>>Keberangkatan</option>
                    <option value="kedatangan" <?= $filter_tipe === 'kedatangan' ? 'selected' : '' ?>>Kedatangan</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label-custom">Kapal</label>
                <select name="kapal" class="form-select form-select-custom">
                    <option value="">Semua</option>
                    <?php foreach ($kapal_list as $kp): ?>
                    <option value="<?= $kp['id'] ?>" <?= $filter_kapal == $kp['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($kp['kode_kapal']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label-custom">Status</label>
                <select name="status" class="form-select form-select-custom">
                    <option value="">Semua</option>
                    <?php foreach ($status_options as $s): ?>
                    <option value="<?= $s ?>" <?= $filter_status === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label-custom">Dari Tanggal</label>
                <input type="date" name="dari" class="form-control form-control-custom" value="<?= $filter_dari ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label-custom">Sampai Tanggal</label>
                <input type="date" name="sampai" class="form-control form-control-custom" value="<?= $filter_sampai ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary-custom w-100"><i class="bi bi-funnel"></i> Filter</button>
            </div>
        </form>
    </div>
</div>

<!-- Report Print Header (hidden on screen) -->
<div class="d-none d-print-block text-center mb-4">
    <h3>PT. ITL SHIPPING AGENCY</h3>
    <p>PT. Internasional Total Service & Logistics</p>
    <hr>
    <h5>Laporan <?= ucfirst($filter_tipe) ?></h5>
    <?php if ($filter_dari || $filter_sampai): ?>
    <p>Periode: <?= $filter_dari ? date('d/m/Y', strtotime($filter_dari)) : '...' ?> s/d <?= $filter_sampai ? date('d/m/Y', strtotime($filter_sampai)) : '...' ?></p>
    <?php endif; ?>
</div>

<!-- Data Table -->
<div class="card-custom">
    <div class="card-header-custom">
        <i class="bi bi-table"></i> Laporan <?= ucfirst($filter_tipe) ?>
        <span class="ms-auto text-muted" style="font-size: 0.8rem;">Total: <?= count($data) ?> data</span>
    </div>
    <div class="card-body-custom p-0">
        <div class="table-responsive">
            <table class="table table-custom datatable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kapal</th>
                        <?php if ($filter_tipe === 'keberangkatan'): ?>
                        <th>Asal</th>
                        <th>Tujuan</th>
                        <th>Tgl Berangkat</th>
                        <th>Muatan</th>
                        <?php else: ?>
                        <th>Asal</th>
                        <th>Pelabuhan Tiba</th>
                        <th>Tgl Estimasi</th>
                        <th>Tgl Aktual</th>
                        <?php endif; ?>
                        <th>Status</th>
                        <th>Pencatat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $i => $d): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td>
                            <strong><?= htmlspecialchars($d['nama_kapal']) ?></strong><br>
                            <small class="text-muted"><?= htmlspecialchars($d['kode_kapal']) ?></small>
                        </td>
                        <?php if ($filter_tipe === 'keberangkatan'): ?>
                        <td><?= htmlspecialchars($d['pelabuhan_asal']) ?></td>
                        <td><?= htmlspecialchars($d['pelabuhan_tujuan']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($d['tanggal_berangkat'])) ?></td>
                        <td><?= htmlspecialchars($d['muatan'] ?? '-') ?></td>
                        <?php else: ?>
                        <td><?= htmlspecialchars($d['pelabuhan_asal']) ?></td>
                        <td><?= htmlspecialchars($d['pelabuhan_tiba']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($d['tanggal_tiba'])) ?></td>
                        <td><?= $d['tanggal_aktual_tiba'] ? date('d/m/Y H:i', strtotime($d['tanggal_aktual_tiba'])) : '-' ?></td>
                        <?php endif; ?>
                        <td><span class="badge-status badge-<?= $d['status'] ?>"><?= ucfirst($d['status']) ?></span></td>
                        <td style="font-size: 0.8rem;"><?= htmlspecialchars($d['pencatat'] ?? '-') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
