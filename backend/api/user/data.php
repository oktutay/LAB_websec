<?php
require_once __DIR__ . '/../../config/db.php';
session_start();
$conn = new mysqli("localhost", "root", "", "task1_vnpt");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); //kiểm tra kết nối
}

// lấy đầu vào từ form POST từ index.php
$search = $_POST['SearchInput'] ?? '';

// câu truy vấn không được lọc
$sql = "SELECT * FROM employees WHERE email = '$search'";
echo "<p><b>Query:</b> $sql</p>"; // hiển thị ra câu truy vấn

// Thực thi truy vấn
$result = $conn->query($sql);

// Nếu lỗi → hiện chi tiết để khai thác SQLi kiểu error-based
if (!$result) {
    die("<b>MySQL Error:</b> <pre>" . $conn->error . "</pre>");
}
$f = 0;
// Hiển thị dữ liệu{
    while ($r = mysqli_fetch_assoc($result)){ //lấy từng hàng của câu truy vấn
    echo "<tr>";
    echo "<td>Full name: " . $r['fullname'] . "</td>"."<br>";
    echo "<td>Phone: " . $r['phone'] . "</td>"."<br>";
    echo "<td>Position: " . $r['position'] . "</td>"."<br>";
    echo "</tr>";
    $f = 1;
    }
if(!$f){
    echo "Không có thông tin để hiển thị";
}
else{
    echo "Dữ liệu thành công";
}
?>
