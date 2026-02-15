<?php
require_once __DIR__ . '/includes/auth.php';
checkLogin();
require_once __DIR__ . '/config/database.php';

header('Content-Type: application/json');

$type = $_GET['type'] ?? '';
$data = [];
$title = '';
$columns = [];

switch ($type) {
    case 'kapal_aktif':
        $title = 'Kapal Aktif';
        $columns = ['Kode', 'Nama Kapal', 'Jenis', 'Bendera', 'Kapasitas (Ton)', 'Pemilik'];
        $rows = $pdo->query("SELECT kode_kapal, nama_kapal, jenis_kapal, bendera, kapasitas_ton, pemilik FROM kapal WHERE status='aktif' ORDER BY nama_kapal")->fetchAll();
        foreach ($rows as $r) {
            $data[] = [
                htmlspecialchars($r['kode_kapal']),
                htmlspecialchars($r['nama_kapal']),
                htmlspecialchars($r['jenis_kapal'] ?? '-'),
                htmlspecialchars($r['bendera'] ?? '-'),
                number_format($r['kapasitas_ton']),
                htmlspecialchars($r['pemilik'] ?? '-')
            ];
        }
        break;

    case 'keberangkatan_hari_ini':
        $title = 'Keberangkatan Hari Ini';
        $columns = ['Kapal', 'Asal', 'Tujuan', 'Jam Berangkat', 'Muatan', 'Status'];
        $rows = $pdo->query("
            SELECT kp.nama_kapal, k.pelabuhan_asal, k.pelabuhan_tujuan, k.tanggal_berangkat, k.muatan, k.status
            FROM keberangkatan k JOIN kapal kp ON k.id_kapal = kp.id
            WHERE DATE(k.tanggal_berangkat) = CURDATE()
            ORDER BY k.tanggal_berangkat
        ")->fetchAll();
        foreach ($rows as $r) {
            $data[] = [
                htmlspecialchars($r['nama_kapal']),
                htmlspecialchars($r['pelabuhan_asal']),
                htmlspecialchars($r['pelabuhan_tujuan']),
                date('H:i', strtotime($r['tanggal_berangkat'])),
                htmlspecialchars($r['muatan'] ?? '-'),
                ucfirst($r['status'])
            ];
        }
        break;

    case 'kedatangan_hari_ini':
        $title = 'Kedatangan Hari Ini';
        $columns = ['Kapal', 'Asal', 'Pelabuhan Tiba', 'Jam Tiba', 'Aktual', 'Status'];
        $rows = $pdo->query("
            SELECT kp.nama_kapal, kd.pelabuhan_asal, kd.pelabuhan_tiba, kd.tanggal_tiba, kd.tanggal_aktual_tiba, kd.status
            FROM kedatangan kd JOIN kapal kp ON kd.id_kapal = kp.id
            WHERE DATE(kd.tanggal_tiba) = CURDATE()
            ORDER BY kd.tanggal_tiba
        ")->fetchAll();
        foreach ($rows as $r) {
            $data[] = [
                htmlspecialchars($r['nama_kapal']),
                htmlspecialchars($r['pelabuhan_asal']),
                htmlspecialchars($r['pelabuhan_tiba']),
                date('H:i', strtotime($r['tanggal_tiba'])),
                $r['tanggal_aktual_tiba'] ? date('H:i', strtotime($r['tanggal_aktual_tiba'])) : '-',
                ucfirst($r['status'])
            ];
        }
        break;

    case 'sedang_berlayar':
        $title = 'Kapal Sedang Berlayar';
        $columns = ['Kapal', 'Kode', 'Asal', 'Tujuan', 'Tgl Berangkat', 'Muatan'];
        $rows = $pdo->query("
            SELECT kp.nama_kapal, kp.kode_kapal, k.pelabuhan_asal, k.pelabuhan_tujuan, k.tanggal_berangkat, k.muatan
            FROM keberangkatan k JOIN kapal kp ON k.id_kapal = kp.id
            WHERE k.status = 'berlayar'
            ORDER BY k.tanggal_berangkat DESC
        ")->fetchAll();
        foreach ($rows as $r) {
            $data[] = [
                htmlspecialchars($r['nama_kapal']),
                htmlspecialchars($r['kode_kapal']),
                htmlspecialchars($r['pelabuhan_asal']),
                htmlspecialchars($r['pelabuhan_tujuan']),
                date('d/m/Y H:i', strtotime($r['tanggal_berangkat'])),
                htmlspecialchars($r['muatan'] ?? '-')
            ];
        }
        break;

    default:
        echo json_encode(['error' => 'Invalid type']);
        exit;
}

echo json_encode(['title' => $title, 'columns' => $columns, 'data' => $data]);
