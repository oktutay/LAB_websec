<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../middleware/user.php';

header('Content-Type: application/json; charset=utf-8');

// 1. Lấy dữ liệu từ client
$input = json_decode(file_get_contents("php://input"), true);
$id = $input['id'] ?? null;

if (!$id) {
  http_response_code(400);
  echo json_encode(['success' => false, 'message' => 'Thiếu ID sản phẩm']);
  exit;
}

// 2. Lấy thông tin sản phẩm từ DB
$stmt = $pdo->prepare("SELECT creator_id FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
  http_response_code(404);
  echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại']);
  exit;
}

// 3. Kiểm tra quyền xóa
$currentUser = $_SESSION['user'];
if ($currentUser['role'] !== 'admin' && $currentUser['id'] != $product['creator_id']) {
  http_response_code(403);
  echo json_encode(['success' => false, 'message' => 'Bạn không có quyền xóa sản phẩm này']);
  exit;
}

// 4. Thực hiện xóa
try {
  $deleteStmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
  $deleteStmt->execute([$id]);

  echo json_encode(['success' => true, 'message' => 'Đã xóa sản phẩm']);
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(['success' => false, 'message' => 'Lỗi khi xóa']);
}
