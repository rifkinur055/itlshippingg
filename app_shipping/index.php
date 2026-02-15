<?php
$page_title = 'Dashboard - ITL Shipping Agency';
require_once __DIR__ . '/includes/auth.php';
checkLogin();
require_once __DIR__ . '/config/database.php';

// === Statistics ===
$total_kapal = $pdo->query("SELECT COUNT(*) FROM kapal WHERE status='aktif'")->fetchColumn();
$total_keberangkatan = $pdo->query("SELECT COUNT(*) FROM keberangkatan WHERE DATE(tanggal_berangkat) = CURDATE()")->fetchColumn();
$total_kedatangan = $pdo->query("SELECT COUNT(*) FROM kedatangan WHERE DATE(tanggal_tiba) = CURDATE()")->fetchColumn();
$total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

// Keberangkatan berlayar (aktif)
$kapal_berlayar = $pdo->query("SELECT COUNT(*) FROM keberangkatan WHERE status='berlayar'")->fetchColumn();

// Rasio penggunaan kapal (keberangkatan + kedatangan)
$chart_data = $pdo->query("
    SELECT kp.nama_kapal, kp.kode_kapal,
           IFNULL(kb.total_berangkat, 0) AS total_berangkat,
           IFNULL(kd.total_datang, 0) AS total_datang
    FROM kapal kp
    LEFT JOIN (SELECT id_kapal, COUNT(*) AS total_berangkat FROM keberangkatan GROUP BY id_kapal) kb ON kb.id_kapal = kp.id
    LEFT JOIN (SELECT id_kapal, COUNT(*) AS total_datang FROM kedatangan GROUP BY id_kapal) kd ON kd.id_kapal = kp.id
    WHERE IFNULL(kb.total_berangkat, 0) + IFNULL(kd.total_datang, 0) > 0
    ORDER BY (IFNULL(kb.total_berangkat, 0) + IFNULL(kd.total_datang, 0)) DESC
")->fetchAll();

$chart_labels = [];
$chart_berangkat = [];
$chart_datang = [];
foreach ($chart_data as $row) {
    $chart_labels[] = $row['kode_kapal'];
    $chart_berangkat[] = (int)$row['total_berangkat'];
    $chart_datang[] = (int)$row['total_datang'];
}

// Jadwal terbaru
$jadwal_terbaru = $pdo->query("
    SELECT k.*, kp.nama_kapal, kp.kode_kapal
    FROM keberangkatan k
    JOIN kapal kp ON k.id_kapal = kp.id
    ORDER BY k.created_at DESC
    LIMIT 5
")->fetchAll();

$kedatangan_terbaru = $pdo->query("
    SELECT kd.*, kp.nama_kapal, kp.kode_kapal
    FROM kedatangan kd
    JOIN kapal kp ON kd.id_kapal = kp.id
    ORDER BY kd.created_at DESC
    LIMIT 5
")->fetchAll();

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';
?>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h2><i class="bi bi-speedometer2"></i> Dashboard</h2>
        <span class="breadcrumb-custom">Selamat datang, <?= htmlspecialchars($_SESSION['user_nama']) ?>!</span>
    </div>
    <span class="breadcrumb-custom">
        <i class="bi bi-calendar3 me-1"></i><?= date('l, d F Y') ?>
    </span>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card card-ships" data-type="kapal_aktif" style="cursor:pointer" onclick="showCardDetail(this)">
            <div class="stat-icon"><i class="bi bi-ship"></i></div>
            <div class="stat-value"><?= $total_kapal ?></div>
            <div class="stat-label">Kapal Aktif</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card card-departures" data-type="keberangkatan_hari_ini" style="cursor:pointer" onclick="showCardDetail(this)">
            <div class="stat-icon"><i class="bi bi-box-arrow-right"></i></div>
            <div class="stat-value"><?= $total_keberangkatan ?></div>
            <div class="stat-label">Keberangkatan Hari Ini</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card card-arrivals" data-type="kedatangan_hari_ini" style="cursor:pointer" onclick="showCardDetail(this)">
            <div class="stat-icon"><i class="bi bi-box-arrow-in-left"></i></div>
            <div class="stat-value"><?= $total_kedatangan ?></div>
            <div class="stat-label">Kedatangan Hari Ini</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card card-users" data-type="sedang_berlayar" style="cursor:pointer" onclick="showCardDetail(this)">
            <div class="stat-icon"><i class="bi bi-activity"></i></div>
            <div class="stat-value"><?= $kapal_berlayar ?></div>
            <div class="stat-label">Sedang Berlayar</div>
        </div>
    </div>
</div>

<!-- Charts & Recent -->
<div class="row g-3 mb-4">
    <!-- Chart -->
    <div class="col-lg-7">
        <div class="card-custom">
            <div class="card-header-custom">
                <i class="bi bi-bar-chart-line"></i> Rasio Penggunaan Kapal
            </div>
            <div class="card-body-custom">
                <div class="chart-container">
                    <canvas id="chartKapal"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!-- Kapal Berlayar -->
    <div class="col-lg-5">
        <div class="card-custom">
            <div class="card-header-custom">
                <i class="bi bi-clock-history"></i> Keberangkatan Terbaru
            </div>
            <div class="card-body-custom p-0">
                <div class="table-responsive">
                    <table class="table table-custom mb-0">
                        <thead>
                            <tr>
                                <th>Kapal</th>
                                <th>Tujuan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($jadwal_terbaru)): ?>
                            <tr><td colspan="3" class="text-center py-3 text-muted">Belum ada data</td></tr>
                            <?php else: ?>
                            <?php foreach ($jadwal_terbaru as $j): ?>
                            <tr>
                                <td>
                                    <strong style="color: var(--primary); font-size: 0.8rem;"><?= htmlspecialchars($j['nama_kapal']) ?></strong><br>
                                    <small class="text-muted"><?= htmlspecialchars($j['kode_kapal']) ?></small>
                                </td>
                                <td style="font-size: 0.8rem;"><?= htmlspecialchars($j['pelabuhan_tujuan']) ?></td>
                                <td><span class="badge-status badge-<?= $j['status'] ?>"><?= ucfirst($j['status']) ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Kedatangan Terbaru -->
<div class="row g-3">
    <div class="col-12">
        <div class="card-custom">
            <div class="card-header-custom">
                <i class="bi bi-box-arrow-in-left"></i> Kedatangan Terbaru
            </div>
            <div class="card-body-custom p-0">
                <div class="table-responsive">
                    <table class="table table-custom mb-0">
                        <thead>
                            <tr>
                                <th>Kapal</th>
                                <th>Asal</th>
                                <th>Pelabuhan Tiba</th>
                                <th>Tanggal Tiba</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($kedatangan_terbaru)): ?>
                            <tr><td colspan="5" class="text-center py-3 text-muted">Belum ada data</td></tr>
                            <?php else: ?>
                            <?php foreach ($kedatangan_terbaru as $kd): ?>
                            <tr>
                                <td>
                                    <strong style="color: var(--primary); font-size: 0.8rem;"><?= htmlspecialchars($kd['nama_kapal']) ?></strong><br>
                                    <small class="text-muted"><?= htmlspecialchars($kd['kode_kapal']) ?></small>
                                </td>
                                <td style="font-size: 0.85rem;"><?= htmlspecialchars($kd['pelabuhan_asal']) ?></td>
                                <td style="font-size: 0.85rem;"><?= htmlspecialchars($kd['pelabuhan_tiba']) ?></td>
                                <td style="font-size: 0.85rem;"><?= date('d/m/Y H:i', strtotime($kd['tanggal_tiba'])) ?></td>
                                <td><span class="badge-status badge-<?= $kd['status'] ?>"><?= ucfirst($kd['status']) ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Card -->
<div class="modal fade" id="modalCardDetail" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border:none; border-radius:16px; overflow:hidden;">
            <div class="modal-header" style="background: var(--primary); color:#fff; border:none; padding: 1rem 1.5rem;">
                <h5 class="modal-title" id="modalCardTitle"><i class="bi bi-table me-2"></i>Detail</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding: 0;">
                <div id="modalCardLoading" class="text-center py-4" style="display:none;">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 text-muted">Memuat data...</p>
                </div>
                <div id="modalCardEmpty" class="text-center py-4" style="display:none;">
                    <i class="bi bi-inbox fs-1 text-muted"></i>
                    <p class="mt-2 text-muted">Tidak ada data</p>
                </div>
                <div class="table-responsive" id="modalCardTableWrap" style="display:none;">
                    <table class="table table-custom mb-0" id="modalCardTable">
                        <thead id="modalCardThead"></thead>
                        <tbody id="modalCardTbody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script>
function showCardDetail(el) {
    const type = el.getAttribute('data-type');
    const modal = new bootstrap.Modal(document.getElementById('modalCardDetail'));
    const loading = document.getElementById('modalCardLoading');
    const empty = document.getElementById('modalCardEmpty');
    const tableWrap = document.getElementById('modalCardTableWrap');

    // Reset
    loading.style.display = 'block';
    empty.style.display = 'none';
    tableWrap.style.display = 'none';
    document.getElementById('modalCardTitle').innerHTML = '<i class="bi bi-table me-2"></i>Memuat...';
    modal.show();

    fetch('/app_shipping/api_card_data.php?type=' + type)
        .then(res => res.json())
        .then(result => {
            loading.style.display = 'none';
            document.getElementById('modalCardTitle').innerHTML = '<i class="bi bi-table me-2"></i>' + result.title;

            if (!result.data || result.data.length === 0) {
                empty.style.display = 'block';
                return;
            }

            // Build header
            let thead = '<tr>';
            result.columns.forEach(col => { thead += '<th>' + col + '</th>'; });
            thead += '</tr>';
            document.getElementById('modalCardThead').innerHTML = thead;

            // Build body
            let tbody = '';
            result.data.forEach(row => {
                tbody += '<tr>';
                row.forEach(cell => { tbody += '<td style="font-size:0.85rem">' + cell + '</td>'; });
                tbody += '</tr>';
            });
            document.getElementById('modalCardTbody').innerHTML = tbody;
            tableWrap.style.display = 'block';
        })
        .catch(() => {
            loading.style.display = 'none';
            empty.style.display = 'block';
        });
}

document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('chartKapal');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($chart_labels) ?>,
                datasets: [
                    {
                        label: 'Keberangkatan',
                        data: <?= json_encode($chart_berangkat) ?>,
                        backgroundColor: 'rgba(16, 185, 129, 0.6)',
                        borderColor: '#10b981',
                        borderWidth: 1,
                        borderRadius: 4
                    },
                    {
                        label: 'Kedatangan',
                        data: <?= json_encode($chart_datang) ?>,
                        backgroundColor: 'rgba(59, 130, 246, 0.6)',
                        borderColor: '#3b82f6',
                        borderWidth: 1,
                        borderRadius: 4
                    }
                ]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: { font: { family: 'Inter', size: 11 }, usePointStyle: true, padding: 15 }
                    },
                    tooltip: {
                        backgroundColor: '#0a1628',
                        titleFont: { family: 'Inter' },
                        bodyFont: { family: 'Inter' },
                        cornerRadius: 8,
                        padding: 12
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        stacked: true,
                        ticks: { stepSize: 1, font: { family: 'Inter', size: 11 } },
                        grid: { color: 'rgba(0,0,0,0.04)' }
                    },
                    y: {
                        stacked: true,
                        ticks: { font: { family: 'Inter', size: 11 } },
                        grid: { display: false }
                    }
                }
            }
        });
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
