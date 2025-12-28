<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

if (!isset($_SESSION['hod'])) {
  header("Location: ../hodindex.html");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $staff_id = $_POST['staff_id'];
  $name = $_POST['name'];
  $email = $_POST['email'];

  $stmt = $db->prepare("UPDATE staff SET name = ?, email = ? WHERE staff_id = ?");
  $stmt->execute([$name, $email, $staff_id]);

  header("Location: edit_staffs.php");
  exit();
}
?>