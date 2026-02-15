<?php
$current_page = basename($_SERVER['PHP_SELF'], '.php');
$current_dir = basename(dirname($_SERVER['PHP_SELF']));

function isActive($dir, $page = '') {
    global $current_dir, $current_page;
    if ($page) return ($current_dir === $dir && $current_page === $page) ? 'active' : '';
    return ($current_dir === $dir || $current_page === $dir) ? 'active' : '';
}
?>
<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <i class="fa-solid fa-anchor"></i>
        </div>
        <div class="sidebar-brand">
            <h5>ITL Shipping</h5>
            <small>Agency Management</small>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">
            <span class="nav-section-title">MENU UTAMA</span>
        </div>

        <a href="/app_shipping/index.php" class="nav-item <?= isActive('app_shipping') && $current_page === 'index' ? 'active' : '' ?>">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>

        <?php if (isAdmin() || isPimpinan()): ?>
        <div class="nav-section">
            <span class="nav-section-title">MANAJEMEN</span>
        </div>
        <a href="/app_shipping/users/index.php" class="nav-item <?= isActive('users') ?>">
            <i class="bi bi-people"></i>
            <span>Data Users</span>
        </a>
        <?php endif; ?>

        <div class="nav-section">
            <span class="nav-section-title">DATA OPERASIONAL</span>
        </div>

        <a href="/app_shipping/kapal/index.php" class="nav-item <?= isActive('kapal') ?>">
            <i class="fa-solid fa-ship"></i>
            <span>Data Kapal</span>
        </a>

        <a href="/app_shipping/keberangkatan/index.php" class="nav-item <?= isActive('keberangkatan') ?>">
            <i class="bi bi-box-arrow-right"></i>
            <span>Keberangkatan</span>
        </a>

        <a href="/app_shipping/kedatangan/index.php" class="nav-item <?= isActive('kedatangan') ?>">
            <i class="bi bi-box-arrow-in-left"></i>
            <span>Kedatangan</span>
        </a>

        <?php if (isAdmin() || isPimpinan()): ?>
        <div class="nav-section">
            <span class="nav-section-title">LAPORAN</span>
        </div>
        <a href="/app_shipping/laporan/index.php" class="nav-item <?= isActive('laporan') ?>">
            <i class="bi bi-file-earmark-bar-graph"></i>
            <span>Laporan</span>
        </a>
        <?php endif; ?>
    </nav>

    <div class="sidebar-footer">
        <small>&copy; <?= date('Y') ?> PT. ITL Shipping</small>
    </div>
</aside>

<!-- Main Content Wrapper -->
<main class="main-content" id="mainContent">
    <div class="content-wrapper">
        <?php
        $flash = getFlash();
        if ($flash):
        ?>
        <div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : ($flash['type'] === 'error' ? 'danger' : 'warning') ?> alert-dismissible fade show alert-custom" role="alert">
            <i class="bi bi-<?= $flash['type'] === 'success' ? 'check-circle' : ($flash['type'] === 'error' ? 'exclamation-circle' : 'info-circle') ?> me-2"></i>
            <?= htmlspecialchars($flash['message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
