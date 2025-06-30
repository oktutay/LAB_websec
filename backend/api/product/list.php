<?php
declare(strict_types=1);

// 1. Kiểm tra xác thực user
require_once __DIR__ . '/../middleware/user.php';

// 2. Kết nối database
require_once __DIR__ . '/../../config/db.php';

// 3. Thiết lập header chung
header('Content-Type: application/json; charset=utf-8');

// 4. Truy vấn và trả về JSON có cấu trúc
try {
    // Sử dụng prepare để tránh SQL injection, dù với SELECT đơn giản
    $stmt = $pdo->prepare("
    SELECT 
        p.id,
        p.name,
        p.price,
        p.description,
        p.creator_id AS creatorId,
        u.firstName, u.lastName,
        CONCAT(u.firstName, ' ', u.lastName) AS creatorName,
        p.created_at AS createdAt
    FROM products p
    JOIN users u ON p.creator_id = u.id
    ORDER BY p.created_at DESC
");
    $stmt->execute();

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Trả về theo chuẩn { success: bool, data: [...] }
    echo json_encode([
        'success' => true,
        'data'    => $products,
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (PDOException $e) {
    // Nếu lỗi database, trả về 500 kèm thông báo (debug chỉ nên bật trong dev)
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error'   => 'Database error',
        'message' => $e->getMessage(),
    ], JSON_UNESCAPED_UNICODE);
}
