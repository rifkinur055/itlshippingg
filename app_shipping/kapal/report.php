<?php
require_once __DIR__ . '/../includes/auth.php';
checkLogin();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/report_helper.php';

$data = $pdo->query("SELECT * FROM kapal ORDER BY kode_kapal")->fetchAll();

echo getReportHeader('Laporan Data Kapal', 'Rekap seluruh data kapal yang terdaftar di PT. ITL Shipping Agency');
?>

<div class="total-info">Menampilkan <strong><?= count($data) ?></strong> data kapal</div>

<table class="report-table">
    <thead>
        <tr>
            <th style="width:35px">No</th>
            <th>Kode</th>
            <th>Nama Kapal</th>
            <th>Jenis</th>
            <th>Bendera</th>
            <th style="text-align:right">Kapasitas (Ton)</th>
            <th>Pemilik</th>
            <th style="width:70px">Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $i => $d): ?>
        <tr>
            <td style="text-align:center"><?= $i + 1 ?></td>
            <td><strong><?= htmlspecialchars($d['kode_kapal']) ?></strong></td>
            <td><?= htmlspecialchars($d['nama_kapal']) ?></td>
            <td><?= htmlspecialchars($d['jenis_kapal'] ?? '-') ?></td>
            <td><?= htmlspecialchars($d['bendera'] ?? '-') ?></td>
            <td style="text-align:right"><?= number_format($d['kapasitas_ton']) ?></td>
            <td><?= htmlspecialchars($d['pemilik'] ?? '-') ?></td>
            <td><span class="badge badge-<?= $d['status'] ?>"><?= ucfirst($d['status']) ?></span></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php echo getReportFooter(count($data)); ?>
