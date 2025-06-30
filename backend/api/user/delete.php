<?php
require_once __DIR__ . '/../middleware/admin.php';
require_once __DIR__ . '/../../config/db.php';

if ($_SESSION['user']['role'] !== 'admin') {
  http_response_code(403);
  echo json_encode(["message" => "Không có quyền"]);
  exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'] ?? 0;

if (!$id) {
  http_response_code(400);
  echo json_encode(["message" => "Thiếu ID"]);
  exit;
}

$stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
$ok = $stmt->execute([$id]);

echo json_encode(["message" => $ok ? "Đã xóa" : "Xóa lỗi"]);
