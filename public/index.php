<?php include __DIR__ . '/components/header.php'; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Trang chủ - Sản phẩm</title>
  <link rel="stylesheet" href="/Task1_VNPT_oktutay/public/assets/css/home.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
</head>
<body>
  <div class="container mt-4">
    <h2 class="text-center">Danh sách sản phẩm</h2>
<!--Nút mua hàng-->
<button id="buySelectedBtn" class="btn btn-success"> Mua các sản phẩm đã chọn</button>
<!-- Tạo sản phẩm mới-->
    <div class="container mt-4">
  <button id="toggleCreateForm" class="btn btn-primary mb-3">Thêm sản phẩm</button>

  <form id="createProductForm" style="display: none;">
    <div class="form-group">
      <label for="name">Tên sản phẩm</label>
      <input type="text" class="form-control" id="name" required>
    </div>
    <div class="form-group">
      <label for="price">Giá (VNĐ)</label>
      <input type="number" class="form-control" id="price" required>
    </div>
    <div class="form-group">
      <label for="description">Mô tả</label>
      <textarea class="form-control" id="description" rows="3"></textarea>
    </div>
    <button type="submit" class="btn btn-success">Tạo sản phẩm</button>
  </form>
    </div>
<!--Tìm kiếm sản phẩm-->
    <div class="container mt-4 mb-3">
     <input type="text" id="searchInput" class="form-control" placeholder="Tìm theo tên sản phẩm hoặc người đăng...">
    </div>
    <div id="productList" class="row mt-3"></div>
  </div>

<!-- Form chỉnh sửa sản phẩm (ẩn mặc định) -->
<form id="editProductForm" style="display: none;" class="mb-4 border p-3 rounded bg-light">
  <h5>Chỉnh sửa sản phẩm</h5>
  <input type="hidden" id="editId">

  <div class="form-group">
    <label for="editName">Tên sản phẩm</label>
    <input type="text" id="editName" class="form-control" required>
  </div>

  <div class="form-group">
    <label for="editPrice">Giá</label>
    <input type="number" id="editPrice" class="form-control" required>
  </div>

  <div class="form-group">
    <label for="editDescription">Mô tả</label>
    <textarea id="editDescription" class="form-control" rows="3" required></textarea>
  </div>

  <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
  <button type="button" class="btn btn-secondary" id="cancelEdit">Hủy</button>
</form>

  <script src="/Task1_VNPT_oktutay/public/assets/js/home.js"></script>
</body>
</html>