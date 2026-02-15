<?php
$page_title = 'Data Keberangkatan - ITL Shipping';
require_once __DIR__ . '/../includes/auth.php';
checkRole(['admin', 'pimpinan', 'petugas']);
require_once __DIR__ . '/../config/database.php';

$data = $pdo->query("
    SELECT k.*, kp.nama_kapal, kp.kode_kapal, u.nama_lengkap as created_by_name
    FROM keberangkatan k
    JOIN kapal kp ON k.id_kapal = kp.id
    LEFT JOIN users u ON k.created_by = u.id
    ORDER BY k.tanggal_berangkat DESC
")->fetchAll();

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="page-header">
    <div>
        <h2><i class="bi bi-box-arrow-right"></i> Data Keberangkatan</h2>
        <span class="breadcrumb-custom"><a href="/app_shipping/index.php">Dashboard</a> / Keberangkatan</span>
    </div>
    <div class="d-flex gap-2">
        <a href="/app_shipping/keberangkatan/report.php" target="_blank" class="btn btn-secondary" style="border-radius:10px;font-weight:600;">
            <i class="bi bi-file-earmark-pdf"></i> Download Report
        </a>
        <?php if (isAdmin() || isPetugas()): ?>
        <a href="/app_shipping/keberangkatan/create.php" class="btn btn-primary-custom">
            <i class="bi bi-plus-lg"></i> Tambah Jadwal
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
                        <th>Tujuan</th>
                        <th>Tgl Berangkat</th>
                        <th>Muatan</th>
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
                        <td><?= htmlspecialchars($d['pelabuhan_tujuan']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($d['tanggal_berangkat'])) ?></td>
                        <td>
                            <?= htmlspecialchars($d['muatan'] ?? '-') ?>
                            <?php if ($d['berat_muatan']): ?>
                            <br><small class="text-muted"><?= number_format($d['berat_muatan']) ?> Ton</small>
                            <?php endif; ?>
                        </td>
                        <td><span class="badge-status badge-<?= $d['status'] ?>"><?= ucfirst($d['status']) ?></span></td>
                        <td>
                            <div class="action-btns">
                                <a href="/app_shipping/keberangkatan/surat_jalan.php?id=<?= $d['id'] ?>" target="_blank" class="btn btn-info-custom" title="Surat Jalan" style="background:linear-gradient(135deg,#0891b2,#0e7490);color:#fff;border:none;">
                                    <i class="bi bi-file-earmark-text"></i>
                                </a>
                                <?php if (isAdmin() || isPetugas()): ?>
                                <a href="/app_shipping/keberangkatan/edit.php?id=<?= $d['id'] ?>" class="btn btn-warning-custom" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="/app_shipping/keberangkatan/delete.php?id=<?= $d['id'] ?>" class="btn btn-danger-custom btn-delete" data-name="<?= htmlspecialchars($d['nama_kapal']) ?>" title="Hapus">
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
