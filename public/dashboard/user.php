<?php
// 1. Bật “hộp bí mật” ($_SESSION) để dùng thông tin người dùng
session_start();

// 2. Kiểm tra xem đã login và có đúng quyền “user” chưa
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    // Nếu chưa, chuyển về trang login
    header("Location: /public/login.php");
    exit;
}

// 3. Gọi phần header chung (nav bar, Bootstrap, v.v.)
include __DIR__ . '/../components/header.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Hóa đơn của bạn</title>
  <!-- Nếu có CSS riêng cho dashboard thì import ở đây -->
</head>
<body>
  <!-- 4. Tiêu đề trang -->
  <h2 class="text-center mt-4">Danh sách hóa đơn của bạn</h2>

  <!-- 5.1 Nơi JS sẽ đổ danh sách hóa đơn -->
  <div class="container mt-3" id="invoiceList"></div>
  <!-- 5.2 Nơi JS sẽ đổ danh sách Sản phẩm -->
  <div class="container mt-3" id="productList"></div>

  <!-- 6. Bơm thông tin user từ PHP vào JS để dashboard.js biết ai đang xem -->
  <script>
    window.CURRENT_USER = <?php echo json_encode($_SESSION['user'], JSON_UNESCAPED_UNICODE); ?>;
  </script>
<div id="productList"></div>
  <?php include __DIR__ . '/../components/footer.php'; ?>
  <!-- 7. Load script để fetch và hiển thị hóa đơn -->
  <script src="/Task1_VNPT_oktutay/public/assets/js/dashboard.js"></script>
</body>
</html>
