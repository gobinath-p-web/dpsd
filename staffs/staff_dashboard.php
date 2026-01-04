<?php
session_start();
if (!isset($_SESSION['staff'])) {
    header("Location: staff_index.html");
    exit();
}
require_once "../includes/db.php";

// Build dynamic query
$query = "SELECT regno, name, email FROM student WHERE 1=1";
$params = [];

if (!empty($_GET['blood_group'])) {
    $query .= " AND blood_group = :blood_group";
    $params[':blood_group'] = $_GET['blood_group'];
}

if (!empty($_GET['gender'])) {
    $query .= " AND gender = :gender";
    $params[':gender'] = $_GET['gender'];
}

if (isset($_GET['arrear_filter']) && $_GET['arrear_filter'] !== '') {
    $query .= " AND arrear " . ($_GET['arrear_filter'] == '0' ? "= 0" : "> 0");
}

$sort = $_GET['sort_by'] ?? '';
switch ($sort) {
    case 'name_asc':
        $query .= " ORDER BY name ASC";
        break;
    case 'name_desc':
        $query .= " ORDER BY name DESC";
        break;
    case 'email_asc':
        $query .= " ORDER BY email ASC";
        break;
    case 'email_desc':
        $query .= " ORDER BY email DESC";
        break;
}

$stmt = $db->prepare($query);
$stmt->execute($params);
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
            vertical-align: middle;
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

        #filterOptions {
            display: none;
            margin-bottom: 20px;
        }

        #filterToggle {
            margin-bottom: 10px;
            padding: 8px 12px;
            background-color: #007acc;
            color: white;
            border: none;
            cursor: pointer;
        }

        #filterToggle:hover {
            background-color: #005fa3;
        }

        select, button[type="submit"] {
            margin-right: 10px;
            padding: 6px;
        }

        .profile-pic {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #007acc;
        }
    </style>
</head>
<body>
    <h2>Welcome, <?= htmlspecialchars($_SESSION['staff']['name']) ?>!</h2>

    <button id="filterToggle" onclick="toggleFilters()">Filter</button>

    <div id="filterOptions">
        <form method="get">
            <select name="blood_group">
                <option value="">All Blood Groups</option>
                <?php
                $groups = ["A+", "A-", "B+", "B-", "O+", "O-", "AB+", "AB-"];
                foreach ($groups as $group) {
                    $selected = ($_GET['blood_group'] ?? '') === $group ? 'selected' : '';
                    echo "<option value=\"$group\" $selected>$group</option>";
                }
                ?>
            </select>

            <select name="gender">
                <option value="">All Genders</option>
                <?php
                $genders = ["Male", "Female", "Other"];
                foreach ($genders as $gender) {
                    $selected = ($_GET['gender'] ?? '') === $gender ? 'selected' : '';
                    echo "<option value=\"$gender\" $selected>$gender</option>";
                }
                ?>
            </select>

            <select name="arrear_filter">
                <option value="">All Arrears</option>
                <option value="0" <?= ($_GET['arrear_filter'] ?? '') === '0' ? 'selected' : '' ?>>No arrears</option>
                <option value="1" <?= ($_GET['arrear_filter'] ?? '') === '1' ? 'selected' : '' ?>>Has arrears</option>
            </select>

            <select name="sort_by">
                <option value="">Sort By</option>
                <option value="name_asc" <?= ($_GET['sort_by'] ?? '') === 'name_asc' ? 'selected' : '' ?>>Name A-Z</option>
                <option value="name_desc" <?= ($_GET['sort_by'] ?? '') === 'name_desc' ? 'selected' : '' ?>>Name Z-A</option>
                <option value="email_asc" <?= ($_GET['sort_by'] ?? '') === 'email_asc' ? 'selected' : '' ?>>Email A-Z</option>
                <option value="email_desc" <?= ($_GET['sort_by'] ?? '') === 'email_desc' ? 'selected' : '' ?>>Email Z-A</option>
            </select>

                   <button type="submit">Apply Filters</button>
    </form>
</div>

<form action="staff_attendance.php" method="get">
  <button type="submit" class="attendance-button">Attendance</button>
</form>

<table>
    <tr>
       <th>REGNO</th><th>Name</th><th>Email</th><th>Actions</th>
    </tr>
    <?php foreach ($student as $student): ?>
    <tr>
        <td><?= htmlspecialchars($student['regno']) ?></td>
        <td><?= htmlspecialchars($student['name']) ?></td>
        <td><?= htmlspecialchars($student['email']) ?></td>
        <td>
            <a href="edit_student.php?regno=<?= $student['regno'] ?>">Edit</a> |
            <a href="delete_student.php?regno=<?= $student['regno'] ?>" onclick="return confirm('Delete this student?')">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<script>
    function toggleFilters() {
        const filterDiv = document.getElementById('filterOptions');
        filterDiv.style.display = (filterDiv.style.display === 'none' || filterDiv.style.display === '') ? 'block' : 'none';
    }
</script>