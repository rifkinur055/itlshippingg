<?php
require_once __DIR__ . '/../includes/auth.php';
checkLogin();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/report_helper.php';

$data = $pdo->query("
    SELECT k.*, kp.nama_kapal, kp.kode_kapal, u.nama_lengkap as pencatat
    FROM keberangkatan k
    JOIN kapal kp ON k.id_kapal = kp.id
    LEFT JOIN users u ON k.created_by = u.id
    ORDER BY k.tanggal_berangkat DESC
")->fetchAll();

echo getReportHeader('Laporan Data Keberangkatan', 'Rekap seluruh jadwal keberangkatan kapal PT. ITL Shipping Agency');
?>

<div class="total-info">Menampilkan <strong><?= count($data) ?></strong> data keberangkatan</div>

<table class="report-table">
    <thead>
        <tr>
            <th style="width:35px">No</th>
            <th>Kode</th>
            <th>Nama Kapal</th>
            <th>Pelabuhan Asal</th>
            <th>Pelabuhan Tujuan</th>
            <th>Tgl Berangkat</th>
            <th>Muatan</th>
            <th style="text-align:right">Berat (Ton)</th>
            <th style="width:75px">Status</th>
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
            <td><?= htmlspecialchars($d['pelabuhan_tujuan']) ?></td>
            <td><?= date('d/m/Y H:i', strtotime($d['tanggal_berangkat'])) ?></td>
            <td><?= htmlspecialchars($d['muatan'] ?? '-') ?></td>
            <td style="text-align:right"><?= $d['berat_muatan'] ? number_format($d['berat_muatan']) : '-' ?></td>
            <td><span class="badge badge-<?= $d['status'] ?>"><?= ucfirst($d['status']) ?></span></td>
            <td style="font-size:9px"><?= htmlspecialchars($d['pencatat'] ?? '-') ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php echo getReportFooter(count($data)); ?>
