<?php
require_once __DIR__ . '/../middleware/admin.php';
require_once __DIR__ . '/../../config/db.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'] ?? 0;

if (!$id) {
  http_response_code(400);
  echo json_encode(["message" => "Thiếu ID"]);
  exit;
}

$stmt = $pdo->prepare("UPDATE users SET status = 'approved' WHERE id = ?");
$ok = $stmt->execute([$id]);

if ($ok) {
  echo json_encode(["message" => "✔️ Đã duyệt tài khoản"]);
} else {
  http_response_code(500);
  echo json_encode(["message" => "Lỗi khi duyệt"]);
}
