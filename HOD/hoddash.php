<?php
session_start();
if (!isset($_SESSION['hod'])) {
  header("Location: hodindex.html");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>HOD Dashboard</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(to right, #e3f2fd, #bbdefb);
    }

    .dashboard {
      max-width: 800px;
      margin: 60px auto;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
      padding: 40px;
    }

    h2 {
      text-align: center;
      color: #1976d2;
      margin-bottom: 30px;
    }

    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
    }

    .card {
      background: #f5faff;
      border: 1px solid #cfd8dc;
      border-radius: 10px;
      padding: 20px;
      text-align: center;
      transition: transform 0.2s, box-shadow 0.2s;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 16px rgba(0,0,0,0.1);
    }

    .card a {
      text-decoration: none;
      color: #1976d2;
      font-weight: bold;
      display: block;
      margin-top: 10px;
    }

    .icon {
      font-size: 36px;
      color: #1565c0;
    }

    .footer {
      text-align: center;
      margin-top: 40px;
    }

    .logout {
      color: #fff;
      background: #e53935;
      padding: 10px 20px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: bold;
      transition: background 0.2s;
    }

    .logout:hover {
      background: #c62828;
    }
  </style>
</head>
<body>
  <div class="dashboard">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['hod']['name']); ?> (HOD)</h2>
    <div class="grid">
      <div class="card">
        <div class="icon">👤</div>
        <a href="updpf.php">Update My Profile</a>
      </div>
      <div class="card">
        <div class="icon">➕</div>
        <a href="add_staff.php">Add New Staff</a>
      </div>
      <div class="card">
        <div class="icon">📝</div>
        <a href="edit_staff.php">Edit Staff Records</a>
      </div>
      <div class="card">
        <div class="icon">🔄</div>
        <a href="update_staff.php">Update Staff Details</a>
      </div>
    </div>
    <div class="footer">
      <a class="logout" href="hodlogout.php">Logout</a>
    </div>
  </div>
</body>
</html>