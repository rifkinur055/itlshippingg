<?php
/**
 * Generate professional company letterhead HTML for PDF reports
 */
function getReportHeader($report_title, $report_subtitle = '') {
    $tanggal = date('d F Y');
    $html = '<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>' . htmlspecialchars($report_title) . ' - ITL Shipping Agency</title>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap");

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: "Inter", Arial, sans-serif; color: #1a1a1a; font-size: 11px; }

        @page {
            size: A4 landscape;
            margin: 15mm 15mm 20mm 15mm;
        }

        @media print {
            .no-print { display: none !important; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }

        /* Letterhead */
        .letterhead {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 15px;
            border-bottom: 3px solid #0a1628;
            margin-bottom: 5px;
        }
        .letterhead-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .logo-box {
            width: 65px;
            height: 65px;
            background: linear-gradient(135deg, #0a1628, #1a2d4a);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0891b2;
            font-size: 28px;
            font-weight: 800;
            flex-shrink: 0;
        }
        .company-info h1 {
            font-size: 18px;
            font-weight: 800;
            color: #0a1628;
            letter-spacing: 1px;
            line-height: 1.2;
        }
        .company-info .company-full {
            font-size: 10px;
            color: #475569;
            font-weight: 500;
            margin-top: 2px;
        }
        .company-info .company-tagline {
            font-size: 8.5px;
            color: #0891b2;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-top: 3px;
        }
        .letterhead-right {
            text-align: right;
            font-size: 8.5px;
            color: #475569;
            line-height: 1.6;
        }
        .letterhead-right strong {
            color: #0a1628;
        }

        .accent-line {
            height: 2px;
            background: linear-gradient(90deg, #0891b2, #f59e0b, transparent);
            margin-bottom: 20px;
        }

        /* Report Title */
        .report-title {
            text-align: center;
            margin-bottom: 20px;
        }
        .report-title h2 {
            font-size: 15px;
            font-weight: 700;
            color: #0a1628;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }
        .report-title .report-subtitle {
            font-size: 10px;
            color: #64748b;
            margin-top: 4px;
        }
        .report-title .report-date {
            font-size: 9px;
            color: #94a3b8;
            margin-top: 2px;
        }

        /* Table */
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .report-table thead th {
            background: #0a1628;
            color: #fff;
            padding: 8px 10px;
            font-size: 10px;
            font-weight: 600;
            text-align: left;
            letter-spacing: 0.3px;
            white-space: nowrap;
        }
        .report-table thead th:first-child { border-radius: 6px 0 0 0; }
        .report-table thead th:last-child { border-radius: 0 6px 0 0; }
        .report-table tbody td {
            padding: 7px 10px;
            font-size: 10px;
            border-bottom: 1px solid #e2e8f0;
            color: #334155;
        }
        .report-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }
        .report-table tbody tr:hover {
            background: #f1f5f9;
        }
        .report-table tbody tr:last-child td:first-child { border-radius: 0 0 0 6px; }
        .report-table tbody tr:last-child td:last-child { border-radius: 0 0 6px 0; }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: 600;
        }
        .badge-aktif, .badge-tiba, .badge-selesai { background: #dcfce7; color: #16a34a; }
        .badge-nonaktif, .badge-batal { background: #fee2e2; color: #dc2626; }
        .badge-dijadwalkan, .badge-menunggu { background: #dbeafe; color: #2563eb; }
        .badge-berlayar { background: #cffafe; color: #0891b2; }
        .badge-terlambat { background: #fef3c7; color: #d97706; }
        .badge-admin { background: #fee2e2; color: #dc2626; }
        .badge-pimpinan { background: #fef3c7; color: #d97706; }
        .badge-petugas { background: #dbeafe; color: #2563eb; }

        /* Footer */
        .report-footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            font-size: 8.5px;
            color: #94a3b8;
        }
        .report-footer .signature {
            text-align: center;
        }
        .report-footer .signature .sign-line {
            margin-top: 50px;
            width: 180px;
            border-top: 1px solid #1a1a1a;
            padding-top: 4px;
            color: #1a1a1a;
            font-weight: 600;
            font-size: 10px;
        }
        .report-footer .signature .sign-title {
            font-size: 8.5px;
            color: #64748b;
        }

        /* Print Button */
        .print-bar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: #0a1628;
            color: #fff;
            padding: 12px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 999;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }
        .print-bar span { font-weight: 600; font-size: 14px; }
        .btn-print {
            background: linear-gradient(135deg, #0891b2, #0e7490);
            color: #fff;
            border: none;
            padding: 8px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-print:hover { transform: translateY(-1px); box-shadow: 0 4px 15px rgba(8,145,178,0.4); }
        .btn-back {
            background: transparent;
            color: #94a3b8;
            border: 1px solid #334155;
            padding: 8px 18px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 13px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-back:hover { color: #fff; border-color: #64748b; }

        .page-body { margin-top: 70px; padding: 20px 30px; }
        @media print { .page-body { margin-top: 0; padding: 0; } }

        .total-info {
            font-size: 10px;
            color: #64748b;
            margin-bottom: 10px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="print-bar no-print">
        <div style="display:flex; align-items:center; gap:12px;">
            <a href="javascript:history.back()" class="btn-back">← Kembali</a>
            <span>📄 ' . htmlspecialchars($report_title) . '</span>
        </div>
        <button class="btn-print" onclick="window.print()">⬇ Download / Print PDF</button>
    </div>
    <div class="page-body">
        <div class="letterhead">
            <div class="letterhead-left">
                <div class="logo-box">ITL</div>
                <div class="company-info">
                    <h1>PT. ITL SHIPPING AGENCY</h1>
                    <div class="company-full">PT. Internasional Total Service & Logistics</div>
                    <div class="company-tagline">Shipping Agency &bull; Cargo Broking &bull; Forwarding</div>
                </div>
            </div>
            <div class="letterhead-right">
                <strong>PT.ITL SHIPPING AGENCY</strong><br>
                Jl. Karimata No.7 Rt/Rw 002/001<br>
                Kel. Antasan Besar Kec.Banjarmasin Tengah 70115<br>
            </div>
        </div>
        <div class="accent-line"></div>

        <div class="report-title">
            <h2>' . htmlspecialchars($report_title) . '</h2>';
    if ($report_subtitle) {
        $html .= '<div class="report-subtitle">' . htmlspecialchars($report_subtitle) . '</div>';
    }
    $html .= '<div class="report-date">Dicetak pada: ' . $tanggal . '</div>
        </div>';
    return $html;
}

function getReportFooter($total_data = 0) {
    $tanggal = date('d F Y');
    return '
        <div class="report-footer">
            <div>
                <div>Total Data: <strong>' . $total_data . '</strong></div>
                <div style="margin-top:3px;">Dokumen ini digenerate secara otomatis oleh sistem ITL Shipping Agency</div>
                <div>Member of Indonesian National Shipowner\'s Association (INSA)</div>
            </div>
            <div class="signature">
                <div>Banjarmasin, ' . $tanggal . '</div>
                <div class="sign-line">Administrator</div>
                <div class="sign-title">PT. ITL Shipping Agency</div>
            </div>
        </div>
    </div>
</body>
</html>';
}
