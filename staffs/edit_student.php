<?php
session_start();
if (!isset($_SESSION['staff'])) {
    header("Location: staff_index.html");
    exit();
}

require_once "../includes/db.php";

// Get regno from URL
if (!isset($_GET['regno'])) {
    die("Missing student regno.");
}
$regno = $_GET['regno'];

// Fetch student data
$stmt = $db->prepare("SELECT arrear, S1percentage, S2percentage, S3percentage, S4percentage, S5percentage, S6percentage FROM student WHERE regno = ?");
$stmt->execute([$regno]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    die("Student not found.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $arrear = $_POST['arrear'] ?? 0;
    $s1 = $_POST['S1percentage'] ?? 0;
    $s2 = $_POST['S2percentage'] ?? 0;
    $s3 = $_POST['S3percentage'] ?? 0;
    $s4 = $_POST['S4percentage'] ?? 0;
    $s5 = $_POST['S5percentage'] ?? 0;
    $s6 = $_POST['S6percentage'] ?? 0;

    $update = $db->prepare("UPDATE student SET arrear = ?, S1percentage = ?, S2percentage = ?, S3percentage = ?, S4percentage = ?, S5percentage = ?, S6percentage = ? WHERE regno = ?");
    $update->execute([$arrear, $s1, $s2, $s3, $s4, $s5, $s6, $regno]);

    header("Location: staff_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Semester Data</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f8;
            padding: 40px;
            color: #333;
        }
        form {
            max-width: 500px;
            margin: auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            margin-top: 20px;
            background-color: #007acc;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #005fa3;
        }
    </style>
</head>
<body>
    <h2>Edit Semester Percentages & Arrears</h2>
    <form method="post">
        <label>Arrear</label>
        <input type="number" name="arrear" value="<?= htmlspecialchars($student['arrear']) ?>" required>

        <?php for ($i = 1; $i <= 6; $i++): ?>
            <label>Semester <?= $i ?> Percentage</label>
            <input type="number" step="0.01" name="S<?= $i ?>percentage" value="<?= htmlspecialchars($student["S{$i}percentage"]) ?>" required>
        <?php endfor; ?>

        <button type="submit">Update</button>
    </form>
</body>
</html>