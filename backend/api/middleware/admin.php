<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  http_response_code(403);
  header('Content-Type: application/json');
  echo json_encode(["message" => "Bạn không có quyền truy cập chức năng này"]);
  exit;
}
