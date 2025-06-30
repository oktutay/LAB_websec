<?php
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

// Validate đơn giản
if (
  empty($data['fName']) || empty($data['lName']) ||
  empty($data['email']) || empty($data['password'])
) {
  http_response_code(400);
  echo json_encode(["message" => "Vui lòng nhập đầy đủ thông tin"]);
  exit;
}

// Kiểm tra email đã tồn tại chưa
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$data['email']]);
if ($stmt->fetch()) {
  http_response_code(409);
  echo json_encode(["message" => "Email đã tồn tại"]);
  exit;
}

// Hash mật khẩu
$hash = password_hash($data['password'], PASSWORD_DEFAULT);

// Thêm user mới với status = 'pending'
$stmt = $pdo->prepare("
  INSERT INTO users (firstName, lastName, email, password, role, status) 
  VALUES (?, ?, ?, ?, ?, 'pending')
");

$success = $stmt->execute([
  $data['fName'],
  $data['lName'],
  $data['email'],
  $hash,
  $data['role'] ?? 'user'
]);

if ($success) {
  echo json_encode(["message" => "Đăng ký thành công. Vui lòng chờ duyệt tài khoản."]);
} else {
  http_response_code(500);
  echo json_encode(["message" => "Lỗi server"]);
}
