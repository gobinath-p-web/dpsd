<?php
require_once '../includes/db.php'; // Adjust path if needed

if (isset($_GET['regno'])) {
    $id = $_GET['regno'];

    $stmt = $db->prepare("DELETE FROM student WHERE regno = ?");
    $stmt->execute([$id]);

    header("Location: staff_dashboard.php"); // Redirect after deletion
    exit();
} else {
    echo "No student ID provided.";
}
?>