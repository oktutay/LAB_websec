<?php
require_once __DIR__ . '/../../config/db.php';
session_start();

// Chỉ cho người đã đăng nhập tạo sản phẩm
if (!isset($_SESSION['user'])) {
    http_response_code(401);
    echo json_encode(['message' => 'Chưa đăng nhập']);
    exit;
}

header('Content-Type: application/json; charset=utf-8');

$data = json_decode(file_get_contents("php://input"), true);

$name = trim($data['name'] ?? '');
$price = floatval($data['price'] ?? 0);
$description = trim($data['description'] ?? '');

if ($name === '' || $price <= 0) {
    http_response_code(400);
    echo json_encode(['message' => 'Dữ liệu không hợp lệ']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO products (name, price, description, creator_id) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $price, $description, $_SESSION['user']['id']]);

    echo json_encode(['success' => true, 'message' => 'Tạo sản phẩm thành công']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Lỗi khi tạo sản phẩm']);
}
