<?php
require_once __DIR__ . '/../../config/db.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
  http_response_code(401);
  echo json_encode(["success" => false, "message" => "Chưa đăng nhập"]);
  exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$productIds = $data['productIds'] ?? [];

if (!is_array($productIds) || count($productIds) === 0) {
  http_response_code(400);
  echo json_encode(["success" => false, "message" => "Danh sách sản phẩm không hợp lệ"]);
  exit;
}

try {
  $pdo->beginTransaction();

  // Lấy thông tin từng sản phẩm
  $inClause = implode(",", array_fill(0, count($productIds), "?"));
  $stmt = $pdo->prepare("SELECT id, name, description, price, creator_id FROM products WHERE id IN ($inClause)");
  $stmt->execute($productIds);
  $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $total = array_sum(array_column($products, "price"));

  // Tạo hóa đơn
  $stmt = $pdo->prepare("INSERT INTO bills (userId, date, total) VALUES (?, NOW(), ?)");
  $stmt->execute([$_SESSION['user']['id'], $total]);
  $billId = $pdo->lastInsertId();

  // Ghi vào invoice_items: quantity mặc định là 1
$stmt = $pdo->prepare("
  INSERT INTO invoice_items (
    invoice_id, product_id, quantity, unit_price,
    product_name, description, seller_first, seller_last
  ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");

foreach ($products as $p) {
  $productId = $p['id'];
  $quantity = 1;
  $unitPrice = $p['price'];

  // Lấy thêm mô tả, người bán
  $pStmt = $pdo->prepare("SELECT p.name, p.description, u.firstName, u.lastName
                          FROM products p JOIN users u ON p.creator_id = u.id
                          WHERE p.id = ?");
  $pStmt->execute([$productId]);
  $meta = $pStmt->fetch(PDO::FETCH_ASSOC);

  $stmt->execute([
    $billId,
    $productId,
    $quantity,
    $unitPrice,
    $meta['name'],
    $meta['description'],
    $meta['firstName'],
    $meta['lastName']
  ]);
}
  $pdo->commit();
  echo json_encode(["success" => true, "message" => "Đã mua hàng thành công!"]);
} catch (PDOException $e) {
  $pdo->rollBack();
  http_response_code(500);
  echo json_encode(["success" => false, "message" => "Lỗi database", "error" => $e->getMessage()]);
}
