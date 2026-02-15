<?php
$page_title = 'Data Kedatangan - ITL Shipping';
require_once __DIR__ . '/../includes/auth.php';
checkRole(['admin', 'pimpinan', 'petugas']);
require_once __DIR__ . '/../config/database.php';

$data = $pdo->query("
    SELECT kd.*, kp.nama_kapal, kp.kode_kapal, u.nama_lengkap as created_by_name,
           kb.pelabuhan_asal as kb_asal, kb.pelabuhan_tujuan as kb_tujuan
    FROM kedatangan kd
    JOIN kapal kp ON kd.id_kapal = kp.id
    LEFT JOIN keberangkatan kb ON kd.id_keberangkatan = kb.id
    LEFT JOIN users u ON kd.created_by = u.id
    ORDER BY kd.tanggal_tiba DESC
")->fetchAll();

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="page-header">
    <div>
        <h2><i class="bi bi-box-arrow-in-left"></i> Data Kedatangan</h2>
        <span class="breadcrumb-custom"><a href="/app_shipping/index.php">Dashboard</a> / Kedatangan</span>
    </div>
    <div class="d-flex gap-2">
        <a href="/app_shipping/kedatangan/report.php" target="_blank" class="btn btn-secondary" style="border-radius:10px;font-weight:600;">
            <i class="bi bi-file-earmark-pdf"></i> Download Report
        </a>
        <?php if (isAdmin() || isPetugas()): ?>
        <a href="/app_shipping/kedatangan/create.php" class="btn btn-primary-custom">
            <i class="bi bi-plus-lg"></i> Tambah Kedatangan
        </a>
        <?php endif; ?>
    </div>
</div>

<div class="card-custom">
    <div class="card-body-custom">
        <div class="table-responsive">
            <table class="table table-custom datatable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kapal</th>
                        <th>Asal</th>
                        <th>Pelabuhan Tiba</th>
                        <th>Tgl Estimasi</th>
                        <th>Tgl Aktual</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $i => $d): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td>
                            <strong style="color: var(--primary); font-size: 0.8rem;"><?= htmlspecialchars($d['nama_kapal']) ?></strong><br>
                            <small class="text-muted"><?= htmlspecialchars($d['kode_kapal']) ?></small>
                        </td>
                        <td><?= htmlspecialchars($d['pelabuhan_asal']) ?></td>
                        <td><?= htmlspecialchars($d['pelabuhan_tiba']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($d['tanggal_tiba'])) ?></td>
                        <td>
                            <?php if ($d['tanggal_aktual_tiba']): ?>
                                <?= date('d/m/Y H:i', strtotime($d['tanggal_aktual_tiba'])) ?>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td><span class="badge-status badge-<?= $d['status'] ?>"><?= ucfirst($d['status']) ?></span></td>
                        <td>
                            <div class="action-btns">
                                <a href="/app_shipping/kedatangan/surat_jalan.php?id=<?= $d['id'] ?>" target="_blank" class="btn btn-info-custom" title="Surat Jalan" style="background:linear-gradient(135deg,#0891b2,#0e7490);color:#fff;border:none;">
                                    <i class="bi bi-file-earmark-text"></i>
                                </a>
                                <?php if (isAdmin() || isPetugas()): ?>
                                <a href="/app_shipping/kedatangan/edit.php?id=<?= $d['id'] ?>" class="btn btn-warning-custom" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="/app_shipping/kedatangan/delete.php?id=<?= $d['id'] ?>" class="btn btn-danger-custom btn-delete" data-name="<?= htmlspecialchars($d['nama_kapal']) ?>" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
