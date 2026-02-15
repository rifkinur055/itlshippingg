<?php
require_once __DIR__ . '/../includes/auth.php';
checkRole(['admin']);
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/report_helper.php';

$data = $pdo->query("SELECT * FROM users ORDER BY level, nama_lengkap")->fetchAll();

echo getReportHeader('Laporan Data Pengguna', 'Rekap seluruh data pengguna sistem ITL Shipping Agency');
?>

<div class="total-info">Menampilkan <strong><?= count($data) ?></strong> data pengguna</div>

<table class="report-table">
    <thead>
        <tr>
            <th style="width:35px">No</th>
            <th>Username</th>
            <th>Nama Lengkap</th>
            <th>Email</th>
            <th>No. Telepon</th>
            <th style="width:80px">Level</th>
            <th>Tgl Registrasi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $i => $d): ?>
        <tr>
            <td style="text-align:center"><?= $i + 1 ?></td>
            <td><strong><?= htmlspecialchars($d['username']) ?></strong></td>
            <td><?= htmlspecialchars($d['nama_lengkap']) ?></td>
            <td><?= htmlspecialchars($d['email'] ?? '-') ?></td>
            <td><?= htmlspecialchars($d['no_telepon'] ?? '-') ?></td>
            <td><span class="badge badge-<?= $d['level'] ?>"><?= ucfirst($d['level']) ?></span></td>
            <td><?= date('d/m/Y', strtotime($d['created_at'])) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php echo getReportFooter(count($data)); ?>
