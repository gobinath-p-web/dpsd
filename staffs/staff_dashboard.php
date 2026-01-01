<?php
session_start();
if (!isset($_SESSION['staff'])) {
    header("Location: staff_index.html");
    exit();
}
require_once "../includes/db.php";

// Fetch students
$stmt = $db->prepare("SELECT regno, name, email FROM student");
$stmt->execute();
$student = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Staff Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #007acc;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        a {
            color: #007acc;
            text-decoration: none;
            margin-right: 10px;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h2>Welcome, <?= htmlspecialchars($_SESSION['staff']['name']) ?>!</h2>
    <form method="get" style="margin-bottom: 20px;">
    <input type="text" name="search" placeholder="Search by name or email" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" />
    
    <select name="sort">
        <option value="">Sort by</option>
        <option value="name">Name</option>
        <option value="email">Email</option>
    </select>

    <select name="arrear_filter">
        <option value="">All arrears</option>
        <option value="0">No arrears</option>
        <option value="1">Has arrears</option>
    </select>

    <button type="submit">Apply</button>
</form>
    <table>
        <tr>
            <th>Name</th><th>Email</th><th>Actions</th>
        </tr>
        <?php foreach ($student as $student): ?>
        <tr>
            <td><?= htmlspecialchars($student['name']) ?></td>
            <td><?= htmlspecialchars($student['email']) ?></td>
            <td>
                <a href="edit_student.php?regno=<?= $student['regno'] ?>">Edit</a> |
                <a href="delete_student.php?regno=<?= $student['regno'] ?>" onclick="return confirm('Delete this student?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>