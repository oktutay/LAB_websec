<?php
require_once __DIR__ . '/../middleware/auth.php';
require_once __DIR__ . '/../../config/db.php';

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'] ?? 0;

if (!$id) {
  http_response_code(400);
  echo json_encode(["message" => "Thiếu ID"]);
  exit;
}

$stmt = $pdo->prepare("DELETE FROM bills WHERE id = ?");
$success = $stmt->execute([$id]);

if ($success) {
  echo json_encode(["message" => "Đã xóa"]);
} else {
  http_response_code(500);
  echo json_encode(["message" => "Lỗi khi xóa"]);
}
