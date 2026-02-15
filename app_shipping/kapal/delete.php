<?php
require_once __DIR__ . '/../includes/auth.php';
checkRole(['admin', 'petugas']);
require_once __DIR__ . '/../config/database.php';

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("DELETE FROM kapal WHERE id = ?");
$stmt->execute([$id]);

setFlash('success', 'Kapal berhasil dihapus.');
header('Location: /app_shipping/kapal/index.php');
exit;
