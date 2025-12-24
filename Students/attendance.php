<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: index.html"); // Adjust if index.html is in a different folder
  exit();
}
$user = $_SESSION['user'];

try {
  $db = new PDO('sqlite:' . __DIR__ . '/../deptdocs.db'); // Adjusted path if DB is in project root
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Get current month and year
  $month = date('m');
  $year = date('Y');
  $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
  $firstDayOfMonth = date('w', strtotime("$year-$month-01")); // 0 (Sun) to 6 (Sat)

  // Fetch attendance records for this student and month
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
  <title>Your Presence - DEPTDOCS</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f0f4f8;
      margin: 0;
      padding: 30px;
    }
    h2 {
      color: #1976d2;
      text-align: center;
      margin-bottom: 20px;
    }
    .calendar {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      gap: 10px;
      max-width: 800px;
      margin: auto;
    }
    .day-name, .day-cell {
      background: white;
      border: 1px solid #ccc;
      padding: 12px;
      text-align: center;
      border-radius: 6px;
      box-shadow: 0 1px 4px rgba(0,0,0,0.1);
    }
    .day-name {
      background: #e3f2fd;
      font-weight: bold;
    }
    .empty {
      background: transparent;
      border: none;
      box-shadow: none;
    }
    .back {
      text-align: center;
      margin-top: 30px;
    }
    .back a {
      text-decoration: none;
      color: #1976d2;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <h2>Your Presence for <?php echo date('F Y'); ?></h2>
  <div class="calendar">
    <?php
    $dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    foreach ($dayNames as $day) {
      echo "<div class='day-name'>$day</div>";
    }

    // Empty cells before the first day
    for ($i = 0; $i < $firstDayOfMonth; $i++) {
      echo "<div class='day-cell empty'></div>";
    }

    // Calendar days
    for ($day = 1; $day <= $daysInMonth; $day++) {
      $lookupDate = sprintf('%04d-%02d-%02d', $year, $month, $day);
      $symbol = getSymbol($records[$lookupDate] ?? '');
      echo "<div class='day-cell'><strong>$day</strong><br>$symbol</div>";
    }
    ?>
  </div>
  <div class="back">
    <a href="dashboard.php">← Back to Dashboard</a>
  </div>
</body>
</html>