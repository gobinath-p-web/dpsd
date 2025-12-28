<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
if (!isset($_SESSION['hod'])) {
  header("Location: hodindex.html");
  exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $staff_id = $_POST['staff_id'];
  $name = $_POST['name'];
  $email = $_POST['email'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  $stmt = $db->prepare("INSERT INTO staff (staff_id, name, email, password) VALUES (?, ?, ?, ?)");
  $stmt->execute([$staff_id, $name, $email, $password]);

  echo "Staff added successfully.";
}
?>
<form method="post">
  <input type="text" name="staff_id" placeholder="Staff ID" required />
  <input type="text" name="name" placeholder="Name" required />
  <input type="email" name="email" placeholder="Email" required />
  <input type="password" name="password" placeholder="Password" required />
  <input type="submit" value="Add Staff" />
</form>