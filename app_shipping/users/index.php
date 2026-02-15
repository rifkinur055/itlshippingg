<?php
$page_title = 'Data Users - ITL Shipping';
require_once __DIR__ . '/../includes/auth.php';
checkRole(['admin', 'pimpinan']);
require_once __DIR__ . '/../config/database.php';

$users = $pdo->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="page-header">
    <div>
        <h2><i class="bi bi-people"></i> Data Users</h2>
        <span class="breadcrumb-custom"><a href="/app_shipping/index.php">Dashboard</a> / Data Users</span>
    </div>
    <div class="d-flex gap-2">
        <a href="/app_shipping/users/report.php" target="_blank" class="btn btn-secondary" style="border-radius:10px;font-weight:600;">
            <i class="bi bi-file-earmark-pdf"></i> Download Report
        </a>
        <?php if (isAdmin()): ?>
        <a href="/app_shipping/users/create.php" class="btn btn-primary-custom">
            <i class="bi bi-plus-lg"></i> Tambah User
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
                        <th>Username</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>No. Telepon</th>
                        <th>Level</th>
                        <?php if (isAdmin()): ?><th>Aksi</th><?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $i => $u): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><strong><?= htmlspecialchars($u['username']) ?></strong></td>
                        <td><?= htmlspecialchars($u['nama_lengkap']) ?></td>
                        <td><?= htmlspecialchars($u['email'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($u['no_telepon'] ?? '-') ?></td>
                        <td><span class="badge-role badge-<?= $u['level'] ?>"><?= ucfirst($u['level']) ?></span></td>
                        <?php if (isAdmin()): ?>
                        <td>
                            <div class="action-btns">
                                <a href="/app_shipping/users/edit.php?id=<?= $u['id'] ?>" class="btn btn-warning-custom" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <?php if ($u['id'] != $_SESSION['user_id']): ?>
                                <a href="/app_shipping/users/delete.php?id=<?= $u['id'] ?>" class="btn btn-danger-custom btn-delete" data-name="<?= htmlspecialchars($u['nama_lengkap']) ?>" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </a>
                                <?php endif; ?>
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
