<?php
require_once __DIR__ . '/../../config/db.php';
session_start();
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'];
$date = $data['date'];
$total = $data['total'];

$stmt = $pdo->prepare("UPDATE bills SET date = ?, total = ? WHERE id = ?");
$success = $stmt->execute([$date, $total, $id]);

echo json_encode(["message" => $success ? "OK" : "Cập nhật lỗi"]);
