<?php
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connect to SQLite database
try {
    $db = new PDO('sqlite:' . __DIR__ . '/deptdocs.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Load schema from tables.sql if it exists
   $schemaFile = __DIR__ . '/tables.sql';
$result = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='student'");
if (!$result->fetch()) {
    if (file_exists($schemaFile)) {
        $schema = file_get_contents($schemaFile);
        echo "<pre>$schema</pre>"; // ← Add this line here to debug the schema content
        if ($schema === false) {
            die("Failed to read tables.sql file.");
        }
        if ($db->exec($schema) === false) {
            $error = $db->errorInfo();
            die("Schema execution failed: " . $error[2]);
        }
    } else {
        die("Schema file not found.");
    }
}
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Handle Sign Up
if (isset($_POST['signup'])) {
    $regno = $_POST['regno'];
    $stmt = $db->prepare("SELECT regno FROM student WHERE regno = ?");
    $stmt->execute([$regno]);

    if ($stmt->fetch()) {
        header("Location: index.html?signup_error=1");
        exit();
    }

    $stmt = $db->prepare("INSERT INTO student (
        regno, name, password, address, arrear,
        S1percentage, S2percentage, S3percentage, S4percentage, S5percentage, S6percentage,
        blood_group, phone_number, email, gender, dob
    ) VALUES (?, ?, ?, ?, 0, 0, 0, 0, 0, 0, 0, ?, ?, ?,?,?)");

    $stmt->execute([
        $_POST['regno'],
        $_POST['name'],
        password_hash($_POST['password'], PASSWORD_DEFAULT),
        $_POST['address'],
        $_POST['blood_group'],
        $_POST['phno'],
        $_POST['email'],
        $_POST['gender'],
        $_POST['dob']
    ]);

    $_SESSION['user'] = [
        'regno' => $_POST['regno'],
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'phno' => $_POST['phno'],
        'dob' => $_POST['dob'],
        'gender' => $_POST['gender'],
        'blood_group' => $_POST['blood_group']
    ];

    header("Location: dashboard.php");
    exit();
}

// Handle Login
if (isset($_POST['login'])) {
    $regno = $_POST['regno'];
    $password = $_POST['password'];

    $stmt = $db->prepare("SELECT * FROM student WHERE regno = ?");
    $stmt->execute([$regno]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'regno' => $user['regno'],
            'name' => $user['name'],
            'email' => $user['email'],
            'phno' => $user['phone_number'],
            'dob' => $user['dob'],
            'gender' => $user['gender'],
            'blood_group' => $user['blood_group']
        ];
        header("Location: dashboard.php");
        exit();
    } else {
        header("Location: index.html?login_error=1");
        exit();
    }
}
?>