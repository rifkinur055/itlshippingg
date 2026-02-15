<?php
$page_title = 'Data Kapal - ITL Shipping';
require_once __DIR__ . '/../includes/auth.php';
checkRole(['admin', 'pimpinan', 'petugas']);
require_once __DIR__ . '/../config/database.php';

$kapal = $pdo->query("SELECT * FROM kapal ORDER BY created_at DESC")->fetchAll();

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="page-header">
    <div>
        <h2><i class="bi bi-ship"></i> Data Kapal</h2>
        <span class="breadcrumb-custom"><a href="/app_shipping/index.php">Dashboard</a> / Data Kapal</span>
    </div>
    <div class="d-flex gap-2">
        <a href="/app_shipping/kapal/report.php" target="_blank" class="btn btn-secondary" style="border-radius:10px;font-weight:600;">
            <i class="bi bi-file-earmark-pdf"></i> Download Report
        </a>
        <?php if (isAdmin() || isPetugas()): ?>
        <a href="/app_shipping/kapal/create.php" class="btn btn-primary-custom">
            <i class="bi bi-plus-lg"></i> Tambah Kapal
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
                        <th>Kode</th>
                        <th>Nama Kapal</th>
                        <th>Jenis</th>
                        <th>Bendera</th>
                        <th>Kapasitas (Ton)</th>
                        <th>Pemilik</th>
                        <th>Status</th>
                        <?php if (isAdmin() || isPetugas()): ?><th>Aksi</th><?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($kapal as $i => $k): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><strong style="color: var(--secondary);"><?= htmlspecialchars($k['kode_kapal']) ?></strong></td>
                        <td><?= htmlspecialchars($k['nama_kapal']) ?></td>
                        <td><?= htmlspecialchars($k['jenis_kapal'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($k['bendera'] ?? '-') ?></td>
                        <td><?= number_format($k['kapasitas_ton']) ?></td>
                        <td><?= htmlspecialchars($k['pemilik'] ?? '-') ?></td>
                        <td><span class="badge-status badge-<?= $k['status'] ?>"><?= ucfirst($k['status']) ?></span></td>
                        <?php if (isAdmin() || isPetugas()): ?>
                        <td>
                            <div class="action-btns">
                                <a href="/app_shipping/kapal/edit.php?id=<?= $k['id'] ?>" class="btn btn-warning-custom" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="/app_shipping/kapal/delete.php?id=<?= $k['id'] ?>" class="btn btn-danger-custom btn-delete" data-name="<?= htmlspecialchars($k['nama_kapal']) ?>" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
