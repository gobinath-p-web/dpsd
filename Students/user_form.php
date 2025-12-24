<?php
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include database connection
require_once __DIR__ . '/includes/db.php';

// Handle Sign Up
if (isset($_POST['signup'])) {
    $regno = $_POST['regno'];

    // Check if regno already exists
    $stmt = $db->prepare("SELECT regno FROM student WHERE regno = ?");
    $stmt->execute([$regno]);

    if ($stmt->fetch()) {
        header("Location: students/index.html?signup_error=1");
        exit();
    }

    // Insert new student
    $stmt = $db->prepare("INSERT INTO student (
        regno, name, password, address, arrear,
        S1percentage, S2percentage, S3percentage, S4percentage, S5percentage, S6percentage,
        blood_group, phone_number, email, gender, dob
    ) VALUES (?, ?, ?, ?, 0, 0, 0, 0, 0, 0, 0, ?, ?, ?, ?, ?)");

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

    // Store user session
    $_SESSION['user'] = [
        'regno' => $_POST['regno'],
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'phno' => $_POST['phno'],
        'dob' => $_POST['dob'],
        'gender' => $_POST['gender'],
        'blood_group' => $_POST['blood_group']
    ];

    header("Location: students/dashboard.php");
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
        header("Location: students/dashboard.php");
        exit();
    } else {
        header("Location: students/index.html?login_error=1");
        exit();
    }
}

// If neither login nor signup triggered
header("Location: students/index.html");
exit();