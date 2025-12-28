<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

if (isset($_POST['login'])) {
  $staff_id = trim($_POST['staff_id']);
  $password = $_POST['password'];

  $stmt = $db->prepare("SELECT * FROM staff WHERE staff_id = ?");
  $stmt->execute([$staff_id]);
  $staff = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($staff && password_verify($password, $staff['password'])) {
    $_SESSION['staff'] = [
      'staff_id' => $staff['staff_id'],
      'name' => $staff['name'],
      'email' => $staff['email']
    ];
    header("Location: staff_dashboard.php");
    exit();
  } else {
    header("Location: staff_login.html?error=1");
    exit();
  }
}
?>