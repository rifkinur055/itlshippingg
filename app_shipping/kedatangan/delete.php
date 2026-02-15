<?php
require_once __DIR__ . '/../includes/auth.php';
checkRole(['admin', 'petugas']);
require_once __DIR__ . '/../config/database.php';

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("DELETE FROM kedatangan WHERE id = ?");
$stmt->execute([$id]);

setFlash('success', 'Data kedatangan berhasil dihapus.');
header('Location: /app_shipping/kedatangan/index.php');
exit;
