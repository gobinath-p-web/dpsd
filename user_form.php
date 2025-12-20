<?php
try {
    $db = new PDO('sqlite:depsdoc.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Load schema if needed
    $schemaFile = __DIR__ . '/tables.sql';
    if (file_exists($schemaFile)) {
        $schema = file_get_contents($schemaFile);
        $db->exec($schema);
    }
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $password = $_POST['password'] ?? '';
    $regno = $_POST['regno'] ?? '';
    $address = $_POST['address'] ?? '';
    $email = $_POST['email'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $phno = $_POST['phno'] ?? '';
    $blood_group = $_POST['blood_group'] ?? '';

    $isLogin = isset($_POST['login']);
    $isSignup = isset($_POST['signup']);

    if ($isSignup) {
        $stmt = $db->prepare("SELECT COUNT(*) FROM students WHERE regno = :regno");
        $stmt->bindParam(':regno', $regno);
        $stmt->execute();
        $exists = $stmt->fetchColumn();

        if ($exists > 0) {
            header("Location: index.html?signup_error=1");
            exit;
        }

        if ($name && $regno && $address && $gender && $password && $email && $dob && $phno && $blood_group) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $db->prepare("INSERT INTO students (regno, username, email, password, gender, address, dob, phone_number, blood_group)
                                  VALUES (:regno, :name, :email, :password, :gender, :address, :dob, :phno, :blood_group)");
            $stmt->bindParam(':regno', $regno);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':gender', $gender);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':dob', $dob);
            $stmt->bindParam(':phno', $phno);
            $stmt->bindParam(':blood_group', $blood_group);
            $stmt->execute();

            header("Location: index.html?signup_success=1");
            exit;
        } else {
            header("Location: index.html?signup_error=2");
            exit;
        }
    }

    if ($isLogin) {
        $stmt = $db->prepare("SELECT password FROM students WHERE regno = :regno");
        $stmt->bindParam(':regno', $regno);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && password_verify($password, $row['password'])) {
            header("Location: dashboard.html"); // or your landing page
            exit;
        } else {
            header("Location: index.html?login_error=1");
            exit;
        }
    }
}
?>