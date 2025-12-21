<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: index.html");
  exit();
}
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Dashboard - DEPTDOCS</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #f0f4f8;
    }

    .header {
      background-color: #1976d2;
      color: white;
      padding: 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .profile {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .profile img {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #fff;
    }

    .profile-details {
      color: #fff;
      font-size: 14px;
    }

    .content {
      padding: 30px;
    }

    .semester {
      margin-bottom: 40px;
    }

    h3 {
      color: #1976d2;
      margin-bottom: 10px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    th, td {
      padding: 12px;
      text-align: center;
      border: 1px solid #ccc;
    }

    th {
      background-color: #e3f2fd;
    }
  </style>
</head>
<body>
  <div class="header">
    <h2>DEPTDOCS Dashboard</h2>
    <div class="profile">
      <img src="https://via.placeholder.com/60" alt="Profile Picture" />
      <div class="profile-details">
        <div><strong>Name:</strong> John Doe</div>
        <div><strong>Reg No:</strong> 12345</div>
        <div><strong>Email:</strong> john@example.com</div>
      </div>
    </div>
  </div>

  <div class="content">
    <!-- Repeat this block for each semester -->
    <div class="semester">
      <h3>Semester 1</h3>
      <table>
        <thead>
          <tr>
            <th>Course Title</th>
            <th>Course Code</th>
            <th>Internal Mark</th>
            <th>External Mark</th>
            <th>Total Mark</th>
            <th>Percentage</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Mathematics I</td>
            <td>MATH101</td>
            <td>45</td>
            <td>40</td>
            <td>85</td>
            <td>85%</td>
          </tr>
          <!-- Add more rows as needed -->
        </tbody>
      </table>
    </div>
    <!-- Repeat for Semester 2 to 6 -->
  </div>
</body>
</html>