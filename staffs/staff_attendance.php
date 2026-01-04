<?php
session_start();
if (!isset($_SESSION['staff'])) {
    header("Location: staff_index.html");
    exit();
}
require_once "../includes/db.php";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['section'])) {
    $section  = $_POST['section'];
    $presents = $_POST['presents'] ?? [];
    $holidays = $_POST['holiday'] ?? [];

    // Handle holiday streak input
    $holidayStreak = isset($_POST['holiday_streak']) ? (int)$_POST['holiday_streak'] : 0;
    if ($holidayStreak > 0) {
        $startDate = new DateTime(); // today
        for ($i = 0; $i < $holidayStreak; $i++) {
            $date = $startDate->format('Y-m-d');
            $holidays[$date] = 'Holiday';
            $startDate->modify('+1 day');
        }
    }

    // Apply to all sections if selected
    $targetSections = ($section === 'ALL') ? ['A', 'B', 'C'] : [$section];

    foreach ($targetSections as $sec) {
        foreach ($presents as $date => $count) {
            $holiday = $holidays[$date] ?? '';
            $stmt = $db->prepare("REPLACE INTO attendance_summary (section, date, presents, holiday) VALUES (?, ?, ?, ?)");
            $stmt->execute([$sec, $date, $count, $holiday]);
        }
    }

    $sectionLabel = ($section === 'ALL') ? 'Sections A, B, and C' : "Section " . htmlspecialchars($section);
    echo "<p style='color:green;font-weight:bold;'>Attendance updated for {$sectionLabel}.</p>";
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Attendance Sections</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f4f6f8; padding: 20px; }
    .section-buttons { display: flex; gap: 20px; margin-bottom: 30px; }
    .section-buttons form button {
      padding: 12px 20px;
      background: #007acc;
      color: #fff;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-weight: bold;
    }
    .section-buttons form button:hover { background: #005fa3; }
    .calendar { display: grid; grid-template-columns: repeat(7, 1fr); gap: 10px; }
    .day { background: #fff; border: 1px solid #ccc; padding: 10px; text-align: center; }
    .day strong { display: block; margin-bottom: 5px; }
    input[type="number"], input[type="text"] { width: 90%; padding: 5px; margin-top: 5px; }
    button.save { margin-top: 20px; padding: 10px 15px; background: #28a745; color: #fff; border: none; border-radius: 6px; cursor: pointer; }
    button.save:hover { background: #1e7e34; }
    .holiday-box { margin-top: 20px; padding: 10px; background: #fff3cd; border: 1px solid #ffeeba; border-radius: 6px; }
  </style>
</head>
<body>
  <h2>Attendance Management</h2>
  <div class="section-buttons">
    <form method="get"><button name="section" value="A">Section A</button></form>
    <form method="get"><button name="section" value="B">Section B</button></form>
    <form method="get"><button name="section" value="C">Section C</button></form>
    <form method="get"><button name="section" value="ALL">All Sections</button></form>
  </div>

<?php
if (isset($_GET['section'])) {
    $section = $_GET['section'];

    // Generate calendar for current month
    $year = date("Y");
    $month = date("m");
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

    echo "<form method='post'>";
    echo "<input type='hidden' name='section' value='".htmlspecialchars($section)."'>";
    echo "<div class='calendar'>";

    for ($day = 1; $day <= $daysInMonth; $day++) {
        $date = sprintf("%04d-%02d-%02d", $year, $month, $day);

        // Fetch existing values
        $stmt = $db->prepare("SELECT presents, holiday FROM attendance_summary WHERE section = ? AND date = ?");
        $stmt->execute([$section, $date]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        $presentsVal = $existing['presents'] ?? '';
        $holidayVal  = $existing['holiday'] ?? '';

        echo "<div class='day'>
                <strong>".htmlspecialchars($date)."</strong>
                <input type='number' name='presents[$date]' min='0' value='".htmlspecialchars($presentsVal)."' placeholder='Enter presents'>
                <input type='text' name='holiday[$date]' value='".htmlspecialchars($holidayVal)."' placeholder='Holiday'>
              </div>";
    }

    echo "</div>";

    // Holiday streak box
    echo "<div class='holiday-box'>
            <label for='holiday_streak'>Mark next <strong>N</strong> days as holiday:</label>
            <input type='number' name='holiday_streak' id='holiday_streak' min='1' max='31' style='width:60px; padding:5px;'>
          </div>";

    echo "<button type='submit' class='save'>Save Attendance</button>";
    echo "</form>";
}
?>
</body>
</html>