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
$firstName = $data['firstName'] ?? '';
$lastName = $data['lastName'] ?? '';
$email = $data['email'] ?? '';
$role = $data['role'] ?? 'user';

if (!$id || !$email) {
  http_response_code(400);
  echo json_encode(["message" => "Thiếu dữ liệu"]);
  exit;
}

$stmt = $pdo->prepare("UPDATE users SET firstName=?, lastName=?, email=?, role=? WHERE id=?");
$ok = $stmt->execute([$firstName, $lastName, $email, $role, $id]);

echo json_encode(["message" => $ok ? "OK" : "Lỗi cập nhật"]);
