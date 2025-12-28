<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

// Restrict access to HOD only
if (!isset($_SESSION['hod'])) {
  header("Location: ../hodindex.html");
  exit();
}

// Fetch all staff records
$stmt = $db->query("SELECT staff_id, name, email FROM staff");
$staffs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Staffs</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    th, td {
      padding: 10px;
      border: 1px solid #ccc;
    }
    form {
      display: inline;
    }
    input[type="text"], input[type="email"] {
      width: 90%;
      padding: 6px;
    }
    input[type="submit"] {
      padding: 6px 12px;
    }
  </style>
</head>
<body>
  <h2>Edit Staff Records</h2>
  <table>
    <tr>
      <th>Staff ID</th>
      <th>Name</th>
      <th>Email</th>
      <th>Action</th>
    </tr>
    <?php foreach ($staffs as $staff): ?>
      <tr>
        <form method="post" action="update_staff.php">
          <td><?php echo htmlspecialchars($staff['staff_id']); ?>
            <input type="hidden" name="staff_id" value="<?php echo $staff['staff_id']; ?>">
          </td>
          <td><input type="text" name="name" value="<?php echo htmlspecialchars($staff['name']); ?>" required></td>
          <td><input type="email" name="email" value="<?php echo htmlspecialchars($staff['email']); ?>" required></td>
          <td><input type="submit" value="Update"></td>
        </form>
      </tr>
    <?php endforeach; ?>
  </table>
</body>
</html>