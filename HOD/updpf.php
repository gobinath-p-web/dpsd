<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

if (!isset($_SESSION['hod'])) {
  header("Location: hodindex.html");
  exit();
}

$hod_id = $_SESSION['hod']['hod_id'];
$current_name = $_SESSION['hod']['name'];
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $new_name = trim($_POST['name']);
  $new_password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

  if ($new_password) {
    $stmt = $db->prepare("UPDATE hod SET name = ?, password = ? WHERE hod_id = ?");
    $stmt->execute([$new_name, $new_password, $hod_id]);
  } else {
    $stmt = $db->prepare("UPDATE hod SET name = ? WHERE hod_id = ?");
    $stmt->execute([$new_name, $hod_id]);
  }

  $_SESSION['hod']['name'] = $new_name;
  $current_name = $new_name;
  $message = "✅ Profile updated successfully.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Update HOD Profile</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #e3f2fd;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .form-container {
      background: #fff;
      padding: 30px 40px;
      border-radius: 12px;
      box-shadow: 0 6px 16px rgba(0,0,0,0.1);
      width: 400px;
    }

    h2 {
      text-align: center;
      color: #1976d2;
      margin-bottom: 20px;
    }

    label {
      display: block;
      margin-top: 15px;
      font-weight: bold;
      color: #333;
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 10px;
      margin-top: 6px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 16px;
    }

    input[type="submit"] {
      margin-top: 25px;
      width: 100%;
      padding: 12px;
      background: #1976d2;
      color: #fff;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.2s;
    }

    input[type="submit"]:hover {
      background: #1565c0;
    }

    .message {
      margin-top: 20px;
      text-align: center;
      color: green;
      font-weight: bold;
    }

    .back-link {
      display: block;
      text-align: center;
      margin-top: 20px;
      text-decoration: none;
      color: #1976d2;
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h2>Update Profile</h2>
    <form method="post">
      <label for="name">Full Name</label>
      <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($current_name); ?>" required>

      <label for="password">New Password (leave blank to keep current)</label>
      <input type="password" name="password" id="password" placeholder="Enter new password">

      <input type="submit" value="Update Profile">
    </form>

    <?php if (!empty($message)): ?>
      <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <a class="back-link" href="hoddash.php">← Back to Dashboard</a>
  </div>
</body>
</html>