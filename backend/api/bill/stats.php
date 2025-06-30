<?php
require_once __DIR__ . '/../middleware/admin.php';
require_once __DIR__ . '/../../config/db.php';

if ($_SESSION['user']['role'] !== 'admin') {
  http_response_code(403);
  echo json_encode(["message" => "Không có quyền"]);
  exit;
}

// Thống kê đơn giản
$stmt = $pdo->query("SELECT COUNT(*) AS totalBills, SUM(total) AS totalRevenue FROM bills");
$stats = $stmt->fetch();

echo json_encode([
  "totalBills" => (int)$stats['totalBills'],
  "totalRevenue" => (float)$stats['totalRevenue']
]);
