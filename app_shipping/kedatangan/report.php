<?php
require_once __DIR__ . '/../includes/auth.php';
checkLogin();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/report_helper.php';

$data = $pdo->query("
    SELECT kd.*, kp.nama_kapal, kp.kode_kapal, u.nama_lengkap as pencatat
    FROM kedatangan kd
    JOIN kapal kp ON kd.id_kapal = kp.id
    LEFT JOIN users u ON kd.created_by = u.id
    ORDER BY kd.tanggal_tiba DESC
")->fetchAll();

echo getReportHeader('Laporan Data Kedatangan', 'Rekap seluruh jadwal kedatangan kapal PT. ITL Shipping Agency');
?>

<div class="total-info">Menampilkan <strong><?= count($data) ?></strong> data kedatangan</div>

<table class="report-table">
    <thead>
        <tr>
            <th style="width:35px">No</th>
            <th>Kode</th>
            <th>Nama Kapal</th>
            <th>Pelabuhan Asal</th>
            <th>Pelabuhan Tiba</th>
            <th>Estimasi Tiba</th>
            <th>Aktual Tiba</th>
            <th style="width:75px">Status</th>
            <th>Catatan</th>
            <th>Pencatat</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $i => $d): ?>
        <tr>
            <td style="text-align:center"><?= $i + 1 ?></td>
            <td><strong><?= htmlspecialchars($d['kode_kapal']) ?></strong></td>
            <td><?= htmlspecialchars($d['nama_kapal']) ?></td>
            <td><?= htmlspecialchars($d['pelabuhan_asal']) ?></td>
            <td><?= htmlspecialchars($d['pelabuhan_tiba']) ?></td>
            <td><?= date('d/m/Y H:i', strtotime($d['tanggal_tiba'])) ?></td>
            <td><?= $d['tanggal_aktual_tiba'] ? date('d/m/Y H:i', strtotime($d['tanggal_aktual_tiba'])) : '-' ?></td>
            <td><span class="badge badge-<?= $d['status'] ?>"><?= ucfirst($d['status']) ?></span></td>
            <td style="font-size:9px; max-width:120px"><?= htmlspecialchars($d['catatan'] ?? '-') ?></td>
            <td style="font-size:9px"><?= htmlspecialchars($d['pencatat'] ?? '-') ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php echo getReportFooter(count($data)); ?>
