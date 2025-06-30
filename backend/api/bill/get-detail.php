<?php
require_once __DIR__ . '/../../config/db.php';
session_start();
header('Content-Type: application/json');

$billId = $_GET['billId'] ?? null;
if (!$billId) {
  http_response_code(400);
  echo json_encode(["success" => false, "message" => "Thiếu billId"]);
  exit;
}

// Lấy thông tin hóa đơn và người mua
$stmt = $pdo->prepare("
  SELECT b.id, b.date, b.total, u.firstName, u.lastName
  FROM bills b
  JOIN users u ON b.userId = u.id
  WHERE b.id = ?
");
$stmt->execute([$billId]);
$bill = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$bill) {
  http_response_code(404);
  echo json_encode(["success" => false, "message" => "Không tìm thấy hóa đơn"]);
  exit;
}

// Lấy sản phẩm trong hóa đơn
// Lấy sản phẩm từ chính bảng invoice_items
$stmt = $pdo->prepare("
  SELECT 
    product_name AS name,
    description,
    seller_first AS sellerFirst,
    seller_last AS sellerLast,
    quantity,
    unit_price,
    quantity * unit_price AS line_total
  FROM invoice_items
  WHERE invoice_id = ?
");
$stmt->execute([$billId]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Trả kết quả
echo json_encode([
  "success" => true,
  "bill" => $bill,
  "items" => $items
]);
