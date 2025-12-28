<?php
session_start();
if (!isset($_SESSION['staff'])) {
  header("Location: staff_index.html");
  exit();
}
$staff = $_SESSION['staff'];
?>
<h2>Welcome, <?php echo htmlspecialchars($staff['name']); ?>!</h2>
<p>This is your staff dashboard.</p>
<a href="staff_logout.php">Logout</a>