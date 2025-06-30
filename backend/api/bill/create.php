<?php
require_once __DIR__ . '/../../config/db.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
  http_response_code(401);
  echo json_encode(["message" => "Chưa đăng nhập"]);
  exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$date = $data['date'] ?? null;
$total = $data['total'] ?? null;

if (!$date || !$total) {
  http_response_code(400);
  echo json_encode(["message" => "Thiếu dữ liệu"]);
  exit;
}

$stmt = $pdo->prepare("INSERT INTO bills (userId, date, total) VALUES (?, ?, ?)");
$success = $stmt->execute([$_SESSION['user']['id'], $date, $total]);

echo json_encode(["message" => $success ? "OK" : "Thất bại"]);
