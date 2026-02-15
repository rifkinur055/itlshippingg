<?php
require_once __DIR__ . '/../includes/auth.php';
checkRole(['admin']);
require_once __DIR__ . '/../config/database.php';

$id = (int)($_GET['id'] ?? 0);

// Prevent self-deletion
if ($id == $_SESSION['user_id']) {
    setFlash('error', 'Tidak dapat menghapus akun sendiri.');
    header('Location: /app_shipping/users/index.php');
    exit;
}

$stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
$stmt->execute([$id]);

setFlash('success', 'User berhasil dihapus.');
header('Location: /app_shipping/users/index.php');
exit;
