<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: index.html");
  exit();
}
$user = $_SESSION['user'];

try {
  $db = new PDO('sqlite:' . __DIR__ . '/deptdocs.db');
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Fetch semester percentages
  $stmt = $db->prepare("SELECT S1percentage, S2percentage, S3percentage, S4percentage, S5percentage, S6percentage FROM student WHERE regno = ?");
  $stmt->execute([$user['regno']]);
  $percentages = $stmt->fetch(PDO::FETCH_ASSOC);

  // Fetch attendance for current month
  $month = date('m');
  $year = date('Y');
  $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

  $stmt = $db->prepare("SELECT date, status FROM attendance WHERE regno = ? AND strftime('%Y-%m', date) = ?");
  $stmt->execute([$user['regno'], "$year-$month"]);
  $records = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

  function getSymbol($status) {
    return match ($status) {
      'P' => '✅',
      'A' => '❌',
      'H' => '🅷',
      default => '-',
    };
  }
} catch (PDOException $e) {
  die("Database error: " . $e->getMessage());
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
    <h2>DEPTDOCS Dashboard</h2>
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
      $percentage = isset($percentages[$semKey]) ? $percentages[$semKey] : 'N/A';
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

    <h3>Attendance for <?php echo date('F Y'); ?></h3>
    <table>
      <tr>
        <th>Date</th>
        <th>Day</th>
        <th>Status</th>
      </tr>
      <?php
      for ($day = 1; $day <= $daysInMonth; $day++) {
        $lookupDate = sprintf('%04d-%02d-%02d', $year, $month, $day); // for DB
        $displayDate = sprintf('%02d-%02d-%04d', $day, $month, $year); // for UI
        $dayName = date('l', strtotime($lookupDate));
        $status = $records[$lookupDate] ?? '';
        echo "<tr>
                <td>$displayDate</td>
                <td>$dayName</td>
                <td>" . getSymbol($status) . "</td>
              </tr>";
      }
      ?>
    </table>
  </div>
</body>
</html>