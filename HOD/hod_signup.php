<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

if (isset($_POST['signup'])) {
  $hod_id = trim($_POST['hod_id']);
  $name = trim($_POST['name']);
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  // Check if any HOD already exists
  $stmt = $db->query("SELECT COUNT(*) FROM hod");
  $hodCount = $stmt->fetchColumn();

  if ($hodCount > 0) {
    // HOD already exists — block signup
    header("Location: hodindex.html?signup_error=exists");
    exit();
  }

  // Insert new HOD
  $stmt = $db->prepare("INSERT INTO hod (hod_id, name, password) VALUES (?, ?, ?)");
  $stmt->execute([$hod_id, $name, $password]);

  header("Location: hodindex.html?signup_success=1");
  exit();
}
?>