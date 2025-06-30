<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../middleware/admin.php';
require_once __DIR__ . '/../../config/db.php';

$status = $_GET['status'] ?? '';

if ($status) {
  $stmt = $pdo->prepare("SELECT id, firstName, lastName, email, role, status FROM users WHERE status = ?");
  $stmt->execute([$status]);
  $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
  $stmt = $pdo->query("SELECT id, firstName, lastName, email, role, status FROM users");
  $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

echo json_encode($users);
