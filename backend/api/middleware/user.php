<?php
session_start();

if (!isset($_SESSION['user']) && !isset($_SESSION['admin'])) {
  http_response_code(401);
  header('Content-Type: application/json');
  echo json_encode(['message'=>'Chưa đăng nhập']);
  exit;
}
