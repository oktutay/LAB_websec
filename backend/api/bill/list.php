<?php
require_once __DIR__ . '/../../config/db.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
  http_response_code(401);
  echo json_encode(["message" => "Chưa đăng nhập"]);
  exit;
}

$userId = $_SESSION['user']['id'];

// 1. Lấy tất cả hóa đơn của người dùng
$stmt = $pdo->prepare("SELECT id, date, total FROM bills WHERE userId = ?");
$stmt->execute([$userId]);
$invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 2. Với mỗi hóa đơn, lấy danh sách sản phẩm đi kèm
foreach ($invoices as &$invoice) {
    $stmtItems = $pdo->prepare("
        SELECT p.name, ii.unit_price, ii.quantity, ii.line_total, p.description
        FROM invoice_items ii
        JOIN products p ON ii.product_id = p.id
        WHERE ii.invoice_id = ?
    ");
    $stmtItems->execute([$invoice['id']]);
    $invoice['items'] = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
}


echo json_encode($invoices, JSON_UNESCAPED_UNICODE);

