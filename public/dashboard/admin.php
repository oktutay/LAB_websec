<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header("Location: /public/index.php");
  exit;
}
include '../components/header.php';
include '../../backend/api/middleware/admin.php';
?>

<div class="container mt-4">
  <h2 class="text-center mb-4">Quản lý người dùng & Thống kê</h2>

  <!-- Thống kê -->
  <div id="billStats" class="mb-5">
    <h4>Thống kê hóa đơn</h4>
    <ul>
      <li><strong>Tổng số hóa đơn:</strong> <span id="totalBills">...</span></li>
      <li><strong>Tổng doanh thu:</strong> <span id="totalRevenue">...</span> VNĐ</li>
    </ul>
  </div>

  <!-- Danh sách người dùng -->
  <div id="userList"></div>
</div>

<script>
window.CURRENT_USER = <?php echo json_encode($_SESSION['user'] ?? null); ?>;
</script>
<?php include __DIR__ . '/../components/footer.php'; ?>
<script src="/Task1_VNPT_oktutay/public/assets/js/dashboard.js"></script>
<link rel="stylesheet" href="/Task1_VNPT_oktutay/public/assets/css/dashboard.css">
