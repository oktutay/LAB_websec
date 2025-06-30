<?php
// 1. Bật phiên làm việc nếu chưa có
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Xác định URL điều hướng khi click logo
$homeUrl = '/Task1_VNPT_oktutay/public/index.php';

// 3. Xác định URL đến trang user hoặc admin dựa vào vai trò
$userPage = '';
if (isset($_SESSION['user'])) {
    if ($_SESSION['user']['role'] === 'admin') {
        $userPage = '/Task1_VNPT_oktutay/public/dashboard/admin.php';
    } else {
        $userPage = '/Task1_VNPT_oktutay/public/dashboard/user.php';
    }
}
?>
<!-- 3. Nhúng Bootstrap CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<!-- 4. Navbar chính -->
<nav class="navbar navbar-expand-lg navbar-light bg-light px-4">
  <a class="navbar-brand" href="<?= htmlspecialchars($homeUrl, ENT_QUOTES, 'UTF-8') ?>">
    TASK1_VNPT
  </a>

  <div class="ml-auto">
    <?php if (isset($_SESSION['user'])): ?>
      <span class="mr-3">
        <a href="<?= htmlspecialchars($userPage, ENT_QUOTES, 'UTF-8') ?>" class="text-decoration-none text-dark">
          <?= htmlspecialchars($_SESSION['user']['name'], ENT_QUOTES, 'UTF-8') ?>
          (<?= htmlspecialchars($_SESSION['user']['role'], ENT_QUOTES, 'UTF-8') ?>)
        </a>
      </span>
      <a href="/Task1_VNPT_oktutay/backend/api/auth/logout.php" class="btn btn-sm btn-danger">
        Logout
      </a>
    <?php else: ?>
      <a href="/Task1_VNPT_oktutay/public/login.php" class="btn btn-sm btn-primary">
        Login
      </a>
    <?php endif; ?>
  </div>
</nav>
