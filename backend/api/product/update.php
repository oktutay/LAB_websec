<?php
require_once __DIR__ . '/../../config/db.php';
session_start();
header('Content-Type: application/json');

// Chỉ cho phép user đã login
if (!isset($_SESSION['user'])) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Chưa đăng nhập"]);
    exit;
}

// Đọc JSON từ body
$data = json_decode(file_get_contents("php://input"), true);

// Kiểm tra dữ liệu đầu vào
if (!isset($data['id'], $data['name'], $data['price'], $data['description'])) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Thiếu dữ liệu"]);
    exit;
}

$id = (int)$data['id'];
$name = trim($data['name']);
$price = (float)$data['price'];
$description = trim($data['description']);

// Chỉ cho phép sửa nếu là admin hoặc người tạo sản phẩm
$user = $_SESSION['user'];
if ($user['role'] !== 'admin') {
    $stmt = $pdo->prepare("SELECT creator_id FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product || $product['creator_id'] != $user['id']) {
        http_response_code(403);
        echo json_encode(["success" => false, "message" => "Bạn không có quyền chỉnh sửa sản phẩm này"]);
        exit;
    }
}

// Thực hiện cập nhật
$stmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, description = ? WHERE id = ?");
$success = $stmt->execute([$name, $price, $description, $id]);

if ($success) {
    echo json_encode(["success" => true, "message" => "Cập nhật thành công"]);
} else {
    echo json_encode(["success" => false, "message" => "Lỗi khi cập nhật"]);
}
