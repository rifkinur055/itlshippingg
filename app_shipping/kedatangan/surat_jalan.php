<?php
require_once __DIR__ . '/../includes/auth.php';
checkLogin();
require_once __DIR__ . '/../config/database.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("
    SELECT kd.*, kp.nama_kapal, kp.kode_kapal, kp.jenis_kapal, kp.bendera, kp.kapasitas_ton, kp.pemilik,
           u.nama_lengkap as pencatat,
           kb.pelabuhan_asal as kb_asal, kb.pelabuhan_tujuan as kb_tujuan, kb.tanggal_berangkat, kb.muatan, kb.berat_muatan
    FROM kedatangan kd
    JOIN kapal kp ON kd.id_kapal = kp.id
    LEFT JOIN users u ON kd.created_by = u.id
    LEFT JOIN keberangkatan kb ON kd.id_keberangkatan = kb.id
    WHERE kd.id = ?
");
$stmt->execute([$id]);
$d = $stmt->fetch();

if (!$d) { echo 'Data tidak ditemukan'; exit; }

$no_surat = str_pad($d['id'], 3, '0', STR_PAD_LEFT) . '/' . date('m', strtotime($d['tanggal_tiba'])) . '/1TL-SA/BJM/' . date('Y', strtotime($d['tanggal_tiba']));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Jalan - <?= $no_surat ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', Arial, sans-serif; color: #1a1a1a; font-size: 11px; background: #f1f5f9; }

        @page { size: A4 portrait; margin: 12mm; }
        @media print {
            .no-print { display: none !important; }
            body { background: #fff; }
            .surat-container { box-shadow: none !important; margin: 0 !important; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }

        .print-bar {
            position: fixed; top: 0; left: 0; right: 0;
            background: #0a1628; color: #fff;
            padding: 12px 30px; display: flex; align-items: center; justify-content: space-between;
            z-index: 999; box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }
        .print-bar span { font-weight: 600; font-size: 14px; }
        .btn-action {
            border: none; padding: 8px 24px; border-radius: 8px;
            font-weight: 600; font-size: 13px; cursor: pointer; text-decoration: none;
            transition: all 0.2s;
        }
        .btn-print { background: linear-gradient(135deg, #0891b2, #0e7490); color: #fff; }
        .btn-print:hover { transform: translateY(-1px); box-shadow: 0 4px 15px rgba(8,145,178,0.4); }
        .btn-back { background: transparent; color: #94a3b8; border: 1px solid #334155 !important; }
        .btn-back:hover { color: #fff; border-color: #64748b !important; }

        .surat-container {
            max-width: 210mm; margin: 80px auto 30px; background: #fff;
            box-shadow: 0 10px 50px rgba(0,0,0,0.1); padding: 40px;
        }
        @media print { .surat-container { margin: 0 auto; } }

        .kop-surat {
            display: flex; align-items: flex-start; justify-content: space-between;
            padding-bottom: 14px; border-bottom: 3px solid #0a1628; margin-bottom: 4px;
        }
        .kop-left { display: flex; align-items: center; gap: 14px; }
        .kop-logo {
            width: 60px; height: 60px;
            background: linear-gradient(135deg, #0a1628, #1a2d4a);
            border-radius: 10px; display: flex; align-items: center; justify-content: center;
            color: #0891b2; font-size: 22px; font-weight: 800; flex-shrink: 0;
        }
        .kop-info h1 { font-size: 16px; font-weight: 800; color: #0a1628; letter-spacing: 0.5px; }
        .kop-info .sub { font-size: 9px; color: #64748b; font-weight: 500; margin-top: 1px; }
        .kop-info .tagline { font-size: 7.5px; color: #0891b2; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px; }
        .kop-right { text-align: right; font-size: 8px; color: #64748b; line-height: 1.6; }
        .kop-right strong { color: #0a1628; }
        .accent-line { height: 2px; background: linear-gradient(90deg, #0891b2, #f59e0b, transparent); margin-bottom: 22px; }

        .surat-title { text-align: center; margin-bottom: 22px; }
        .surat-title h2 {
            font-size: 16px; font-weight: 800; color: #0a1628;
            text-transform: uppercase; letter-spacing: 3px;
            border-bottom: 2px solid #0a1628; display: inline-block; padding-bottom: 4px;
        }
        .surat-title .nomor { font-size: 11px; color: #475569; margin-top: 6px; font-weight: 500; }

        .info-section { margin-bottom: 18px; }
        .info-section .section-title {
            font-size: 10px; font-weight: 700; color: #0891b2; text-transform: uppercase;
            letter-spacing: 1px; margin-bottom: 8px; padding-bottom: 4px; border-bottom: 1px solid #e2e8f0;
        }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0; }
        .info-row { display: flex; padding: 5px 0; font-size: 11px; }
        .info-label { width: 140px; color: #64748b; font-weight: 500; flex-shrink: 0; }
        .info-value { color: #1a1a1a; font-weight: 600; }
        .info-value.highlight { color: #0891b2; font-size: 12px; }

        .route-box {
            background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px;
            padding: 18px; margin-bottom: 18px; display: flex; align-items: center; justify-content: center; gap: 30px;
        }
        .route-point { text-align: center; }
        .route-point .port-label { font-size: 8px; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; }
        .route-point .port-name { font-size: 14px; color: #0a1628; font-weight: 700; margin-top: 3px; }
        .route-arrow { font-size: 28px; color: #0891b2; font-weight: 300; }

        .timeline-box {
            display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px; margin-bottom: 18px;
        }
        .timeline-card {
            border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px; text-align: center;
        }
        .timeline-card.departed { background: #f0fdfa; border-color: #99f6e4; }
        .timeline-card.arrival { background: #eff6ff; border-color: #bfdbfe; }
        .timeline-card.actual { background: #dcfce7; border-color: #86efac; }
        .timeline-card .tl-label { font-size: 8px; color: #64748b; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; }
        .timeline-card .tl-date { font-size: 13px; color: #0a1628; font-weight: 700; margin-top: 4px; }
        .timeline-card .tl-time { font-size: 10px; color: #64748b; margin-top: 2px; }

        .status-badge {
            display: inline-block; padding: 3px 12px; border-radius: 6px;
            font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;
        }
        .status-menunggu { background: #dbeafe; color: #2563eb; }
        .status-tiba { background: #dcfce7; color: #16a34a; }
        .status-terlambat { background: #fef3c7; color: #d97706; }
        .status-batal { background: #fee2e2; color: #dc2626; }

        .catatan-box {
            background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px;
            padding: 12px 16px; margin-bottom: 20px; font-size: 10px; color: #92400e;
        }
        .catatan-box strong { color: #78350f; }

        .signatures {
            display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;
            margin-top: 30px; text-align: center;
        }
        .sig-block .sig-title { font-size: 9px; color: #64748b; font-weight: 500; }
        .sig-block .sig-space { height: 60px; }
        .sig-block .sig-line { border-top: 1px solid #1a1a1a; padding-top: 4px; margin: 0 auto;
            width: 150px; font-weight: 700; font-size: 10px; color: #0a1628; }
        .sig-block .sig-role { font-size: 8px; color: #64748b; margin-top: 2px; }

        .footer-note {
            margin-top: 25px; padding-top: 12px; border-top: 1px solid #e2e8f0;
            font-size: 7.5px; color: #94a3b8; text-align: center; line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="print-bar no-print">
        <div style="display:flex;align-items:center;gap:12px">
            <a href="javascript:history.back()" class="btn-action btn-back">← Kembali</a>
            <span>📄 Surat Jalan Kedatangan</span>
        </div>
        <button class="btn-action btn-print" onclick="window.print()">⬇ Download / Print PDF</button>
    </div>

    <div class="surat-container">
        <!-- KOP SURAT -->
        <div class="kop-surat">
            <div class="kop-left">
                <div class="kop-logo">ITL</div>
                <div class="kop-info">
                    <h1>PT. ITL SHIPPING AGENCY</h1>
                    <div class="sub">PT. Internasional Total Service & Logistics</div>
                    <div class="tagline">Shipping Agency • Cargo Broking • Forwarding • Logistics</div>
                </div>
            </div>
            <div class="kop-right">
                <strong>PT.ITL SHIPPING AGENCY</strong><br>
                Jl. Karimata No.7 Rt/Rw 002/001<br>
                Kel. Antasan Besar Kec.Banjarmasin Tengah 70115<br>
            </div>
        </div>
        <div class="accent-line"></div>

        <!-- JUDUL SURAT -->
        <div class="surat-title">
            <h2>Surat Jalan Kedatangan</h2>
            <div class="nomor">No: <strong><?= $no_surat ?></strong></div>
        </div>

        <!-- RUTE -->
        <div class="route-box">
            <div class="route-point">
                <div class="port-label">Pelabuhan Asal</div>
                <div class="port-name"><?= htmlspecialchars($d['pelabuhan_asal']) ?></div>
            </div>
            <div class="route-arrow">⟶</div>
            <div class="route-point">
                <div class="port-label">Pelabuhan Tiba</div>
                <div class="port-name"><?= htmlspecialchars($d['pelabuhan_tiba']) ?></div>
            </div>
        </div>

        <!-- TIMELINE TANGGAL -->
        <div class="timeline-box">
            <?php if ($d['tanggal_berangkat']): ?>
            <div class="timeline-card departed">
                <div class="tl-label">Tanggal Berangkat</div>
                <div class="tl-date"><?= date('d F Y', strtotime($d['tanggal_berangkat'])) ?></div>
                <div class="tl-time"><?= date('H:i', strtotime($d['tanggal_berangkat'])) ?> WIB</div>
            </div>
            <?php endif; ?>
            <div class="timeline-card arrival">
                <div class="tl-label">Estimasi Tiba</div>
                <div class="tl-date"><?= date('d F Y', strtotime($d['tanggal_tiba'])) ?></div>
                <div class="tl-time"><?= date('H:i', strtotime($d['tanggal_tiba'])) ?> WIB</div>
            </div>
            <?php if ($d['tanggal_aktual_tiba']): ?>
            <div class="timeline-card actual">
                <div class="tl-label">Aktual Tiba</div>
                <div class="tl-date"><?= date('d F Y', strtotime($d['tanggal_aktual_tiba'])) ?></div>
                <div class="tl-time"><?= date('H:i', strtotime($d['tanggal_aktual_tiba'])) ?> WIB</div>
            </div>
            <?php endif; ?>
        </div>

        <!-- INFO KAPAL -->
        <div class="info-section">
            <div class="section-title">Informasi Kapal</div>
            <div class="info-grid">
                <div>
                    <div class="info-row">
                        <span class="info-label">Nama Kapal</span>
                        <span class="info-value highlight"><?= htmlspecialchars($d['nama_kapal']) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Kode Kapal</span>
                        <span class="info-value"><?= htmlspecialchars($d['kode_kapal']) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Jenis Kapal</span>
                        <span class="info-value"><?= htmlspecialchars($d['jenis_kapal'] ?? '-') ?></span>
                    </div>
                </div>
                <div>
                    <div class="info-row">
                        <span class="info-label">Bendera</span>
                        <span class="info-value"><?= htmlspecialchars($d['bendera'] ?? '-') ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Kapasitas</span>
                        <span class="info-value"><?= number_format($d['kapasitas_ton']) ?> Ton</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Pemilik</span>
                        <span class="info-value"><?= htmlspecialchars($d['pemilik'] ?? '-') ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- DETAIL KEDATANGAN -->
        <div class="info-section">
            <div class="section-title">Detail Kedatangan</div>
            <div class="info-grid">
                <div>
                    <div class="info-row">
                        <span class="info-label">Status</span>
                        <span class="info-value">
                            <span class="status-badge status-<?= $d['status'] ?>"><?= ucfirst($d['status']) ?></span>
                        </span>
                    </div>
                    <?php if ($d['muatan']): ?>
                    <div class="info-row">
                        <span class="info-label">Muatan</span>
                        <span class="info-value"><?= htmlspecialchars($d['muatan']) ?></span>
                    </div>
                    <?php endif; ?>
                </div>
                <div>
                    <div class="info-row">
                        <span class="info-label">Dicatat Oleh</span>
                        <span class="info-value"><?= htmlspecialchars($d['pencatat'] ?? '-') ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tgl Pencatatan</span>
                        <span class="info-value"><?= date('d/m/Y H:i', strtotime($d['created_at'])) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($d['catatan']): ?>
        <div class="catatan-box">
            <strong>Catatan:</strong> <?= htmlspecialchars($d['catatan']) ?>
        </div>
        <?php endif; ?>

        <!-- TANDA TANGAN -->
        <div class="signatures">
            <div class="sig-block">
                <div class="sig-title">Mengetahui,</div>
                <div class="sig-space"></div>
                <div class="sig-line">Manager Operasional</div>
                <div class="sig-role">PT. ITL Shipping Agency</div>
            </div>
            <div class="sig-block">
                <div class="sig-title">Petugas Pencatat,</div>
                <div class="sig-space"></div>
                <div class="sig-line"><?= htmlspecialchars($d['pencatat'] ?? '_______________') ?></div>
                <div class="sig-role">Petugas Operasional</div>
            </div>
            <div class="sig-block">
                <div class="sig-title">Nahkoda Kapal,</div>
                <div class="sig-space"></div>
                <div class="sig-line">_______________</div>
                <div class="sig-role">Nahkoda <?= htmlspecialchars($d['nama_kapal']) ?></div>
            </div>
        </div>

        <div class="footer-note">
            Dokumen ini diterbitkan oleh PT. ITL Shipping Agency dan bersifat resmi. Surat jalan ini berlaku sebagai bukti kedatangan kapal.<br>
            Member of Indonesian National Shipowner's Association (INSA) • Registered with Directorate General of Sea Transportation<br>
            © <?= date('Y') ?> PT. Internasional Total Service & Logistics. All Rights Reserved.
        </div>
    </div>
</body>
</html>
