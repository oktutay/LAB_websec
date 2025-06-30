<?php
session_start();
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);//lấy trực tiếp thông tin từ người dùng nhập vào và decode qua json
$email = trim($data['email'] ?? '');//lấy dữ liệu từ biến $data ở trên hoặc bằng Null
$password = trim($data['password'] ?? '');

//dùng PDO(PHP data object) để gửi dữ liệu
//chuẩn bị thông qua prepare(câu lệnh SQL mẫu) sau đó excute email để lấy thông tin email gán vào $user và sử dụng ở dưới
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
//chống SQL vì không trực tiếp sử dụng câu lệnh SQL hay cụ thể ở đây là biến $email mà thay bằng ? -> unnamed placeholder
//lấy thông tin user dựa vào email
$stmt->execute([$email]);//thực hiện hàm với biến $email vào ? ở trên -> Như là đưa tham số vào hàm
$user = $stmt->fetch(PDO::FETCH_ASSOC);
//thiết lập kiểu dữ liệu trả về_hay kiểu fetch
//trả về dữ liệu dạng mảng với key là tên của column (column của các table trong database)

// Kiểm tra thông tin đăng nhập
if (!$user || !password_verify($password, $user['password'])) {
  http_response_code(401);
  echo json_encode(["message" => "Email hoặc mật khẩu không đúng"]);
  exit;
}

// Kiểm tra trạng thái duyệt tài khoản
if ($user['status'] !== 'approved') {
  http_response_code(403);
  echo json_encode(["message" => "Tài khoản chưa được duyệt bởi quản trị viên"]);
  exit;
}

// Lưu session nếu hợp lệ
$_SESSION['user'] = [
  "id" => $user['id'],
  "name" => $user['firstName'] . ' ' . $user['lastName'],
  "role" => $user['role'],
  "email" => $user['email']
]; 

echo json_encode(["message" => "Đăng nhập thành công", "user" => $_SESSION['user']]);
