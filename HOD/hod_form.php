<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $hod_id = trim($_POST['hod_id']);
    $password = $_POST['password'];

    try {
        $stmt = $db->prepare("SELECT * FROM hod WHERE hod_id = ?");
        $stmt->execute([$hod_id]);
        $hod = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($hod && password_verify($password, $hod['password'])) {
            $_SESSION['hod'] = [
                'hod_id' => $hod['hod_id'],
                'name' => $hod['name']
            ];
            header("Location: hoddash.php");
            exit();
        } else {
            header("Location: hodindex.html?login_error=1");
            exit();
        }
    } catch (PDOException $e) {
        // Optional: log error to a file instead of showing it
        die("Login failed: " . htmlspecialchars($e->getMessage()));
    }
} else {
    // If accessed directly without POST
    header("Location: hodindex.html");
    exit();
}
?>