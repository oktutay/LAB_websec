
# MÔ TẢ CHỨC NĂNG DỰ ÁN

## 1. Chức năng người dùng (User)

- **Đăng ký tài khoản**
  - Nhập email, tên, mật khẩu để tạo tài khoản.
  - Tài khoản mặc định `approved = 0`, cần admin duyệt mới đăng nhập được.
  - API: `user/create.php`

- **Đăng nhập**
  - Đăng nhập với tài khoản đã được duyệt.
  - Nếu chưa duyệt: hiển thị thông báo từ backend.
  - API: `user/login.php`

- **Cập nhật thông tin cá nhân**
  - Cho phép đổi tên hiển thị.
  - Không cho phép đổi email hoặc quyền hạn.
  - API: `user/update.php`

- **Tạo sản phẩm**
  - Nhập tên, giá, mô tả.
  - Gắn sản phẩm với người tạo (`creator_id`).
  - API: `product/create.php`

- **Xem và tìm kiếm sản phẩm**
  - Hiển thị toàn bộ sản phẩm trên hệ thống.
  - Có chức năng lọc theo tên hoặc người tạo.
  - JS xử lý bằng `searchInput.addEventListener(...)`
  - API: `product/list.php`

- **Sửa / xoá sản phẩm**
  - Người dùng chỉ được sửa/xoá sản phẩm của chính mình.
  - API: `product/edit.php`, `product/delete.php`

- **Mua sản phẩm**
  - Chọn nhiều sản phẩm và thực hiện "mua hàng".
  - Tạo `bill` và `invoice_items` tương ứng.
  - API: `bill/buy.php`

- **Xem hóa đơn**
  - Hiển thị các hóa đơn đã mua của người dùng hiện tại.
  - Bao gồm cả chi tiết từng sản phẩm trong hóa đơn.
  - API: `bill/list_by_user.php`

## 2. Chức năng quản trị (Admin)

- **Duyệt tài khoản**
  - Xem danh sách user có `approved = 0`.
  - Duyệt tài khoản để kích hoạt quyền đăng nhập.
  - API: `user/pending_list.php`, `user/approve.php`

- **Phân quyền người dùng**
  - Chuyển đổi role giữa `user` và `admin`.
  - API: `user/update_role.php`

- **Xem danh sách user**
  - Hiển thị toàn bộ user đã được duyệt.
  - Có thể lọc theo vai trò.
  - API: `user/list.php`

- **Quản lý toàn bộ sản phẩm**
  - Admin có quyền sửa / xoá tất cả sản phẩm.
  - Không bị giới hạn bởi `creator_id`.

- **Export dữ liệu CSV**
  - Xuất danh sách sản phẩm hoặc hóa đơn thành file `.csv`.
  - Dùng để tải về hoặc phân tích.
  - API: `export/products.php`, `export/bills.php`

## 3. Tổ chức mã nguồn

```
Task1_VNPT_oktutay/
├── frontend/
│   ├── login.html
│   ├── register.html
│   ├── dashboard.html
│   └── dashboard.js
├── backend/
│   ├── api/
│   │   ├── user/
│   │   ├── product/
│   │   ├── bill/
│   ├── middleware/
│   ├── config/
├── public/
│   └── index.php
├── export/
│   └── products.php, bills.php
```

## 4. Ghi chú kỹ thuật

- **Session-based auth:** PHP session để xác thực trạng thái đăng nhập.
- **Chống SQLi:** Mọi truy vấn dùng JSON, PDO + prepared statement.
- **Chống XSS:** Escape toàn bộ dữ liệu hiển thị ra HTML bằng JS (`escapeHtml()`).
- **Tách FE/BE:** Giao diện HTML/JS fetch dữ liệu từ API PHP, không trộn lẫn.
- **Phân quyền rõ ràng:** Kiểm tra role ở backend, không chỉ kiểm tra ở frontend.
