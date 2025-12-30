<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: index.html");
  exit();
}
$user = $_SESSION['user'];

try {
  $db = new PDO('sqlite:D:/docs/repos/depsdoc/dpsd/deptdocs.db');
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $stmt = $db->prepare("SELECT S1percentage, S2percentage, S3percentage, S4percentage, S5percentage, S6percentage FROM student WHERE regno = ?");
  $stmt->execute([$user['regno']]);
  $percentages = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("Database error: " . htmlspecialchars($e->getMessage()));
}
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
      flex-wrap: wrap;
    }
    .left-header {
      display: flex;
      align-items: center;
      gap: 20px;
    }
    .presence-button, .logout-button {
      padding: 10px 16px;
      background: white;
      color: #1976d2;
      border: none;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
      font-size: 14px;
      margin-left: 10px;
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
      margin-bottom: 40px;
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
    <div class="left-header">
      <h2>DEPTDOCS Dashboard</h2>
      <form action="attendance.php" method="get">
        <button type="submit" class="presence-button">Your Presence</button>
      </form>
      <form action="logout.php" method="post">
        <button type="submit" class="logout-button">Logout</button>
      </form>
    </div>
    <div class="profile">
      <img src="https://via.placeholder.com/60" alt="Profile Picture" />
      <div class="profile-details">
        <div><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></div>
        <div><strong>Reg No:</strong> <?php echo htmlspecialchars($user['regno']); ?></div>
        <div><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></div>
      </div>
    </div>
  </div>

  <div class="content">
    <?php
    for ($i = 1; $i <= 6; $i++) {
      $semKey = "S{$i}percentage";
      $percentage = isset($percentages[$semKey]) ? htmlspecialchars($percentages[$semKey]) : 'N/A';
      echo "
      <div class='semester'>
        <h3>Semester $i</h3>
        <p><strong>Overall Percentage:</strong> {$percentage}%</p>
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
              <td>Sample Course</td>
              <td>CODE$i</td>
              <td>--</td>
              <td>--</td>
              <td>--</td>
              <td>{$percentage}%</td>
            </tr>
          </tbody>
        </table>
      </div>";
    }
    ?>
  </div>
</body>
</html>