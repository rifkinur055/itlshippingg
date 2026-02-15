<?php
// =============================================
// Authentication & Authorization Helper
// =============================================

session_start();

/**
 * Check if user is logged in, redirect to login if not
 */
function checkLogin()
{
    if (!isset($_SESSION['user_id'])) {
        header('Location: /app_shipping/login.php');
        exit;
    }
}

/**
 * Check if current user has required role
 * @param array $allowed_roles Array of allowed roles
 */
function checkRole($allowed_roles)
{
    checkLogin();
    if (!in_array($_SESSION['user_level'], $allowed_roles)) {
        header('HTTP/1.1 403 Forbidden');
        echo '<div style="text-align:center;padding:50px;font-family:sans-serif;">
            <h2>⛔ Akses Ditolak</h2>
            <p>Anda tidak memiliki hak akses untuk halaman ini.</p>
            <a href="/app_shipping/index.php" style="color:#0891b2;">← Kembali ke Dashboard</a>
        </div>';
        exit;
    }
}

/**
 * Helper functions for role checking
 */
function isAdmin()
{
    return isset($_SESSION['user_level']) && $_SESSION['user_level'] === 'admin';
}

function isPimpinan()
{
    return isset($_SESSION['user_level']) && $_SESSION['user_level'] === 'pimpinan';
}

function isPetugas()
{
    return isset($_SESSION['user_level']) && $_SESSION['user_level'] === 'petugas';
}

/**
 * Get current user ID
 */
function getCurrentUserId()
{
    return $_SESSION['user_id'] ?? null;
}

/**
 * Get current user name
 */
function getCurrentUserName()
{
    return $_SESSION['user_nama'] ?? 'Unknown';
}

/**
 * Get current user level
 */
function getCurrentUserLevel()
{
    return $_SESSION['user_level'] ?? '';
}

/**
 * Set flash message
 */
function setFlash($type, $message)
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

/**
 * Get and clear flash message
 */
function getFlash()
{
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}
