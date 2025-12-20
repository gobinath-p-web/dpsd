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
    $name = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $regno = $_POST['regno'] ?? '';
    $address = $_POST['address'] ?? '';
    $email = $_POST['email'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $dob = $_POST['dob'] ?? '';

    // Check if this is a login or sign-up attempt
    $isLogin = isset($_POST['login']);
    $isSignup = isset($_POST['signup']);

    if ($isSignup) {
        // Check for existing register number
        $stmt = $db->prepare("SELECT COUNT(*) FROM student WHERE register_number = :regno");
        $stmt->bindParam(':regno', $regno, PDO::PARAM_INT);
        $stmt->execute();
        $exists = $stmt->fetchColumn();

        if ($exists > 0) {
            echo "Register number already exists. Please log in instead.";
            exit;
        }

        if ($name && $regno && $address && $gender && $password && $email && $dob) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $db->prepare("INSERT INTO student (register_number, name, email, password, gender, address, dob)
                                  VALUES (:regno, :name, :email, :password, :gender, :address, :dob)");
            $stmt->bindParam(':regno', $regno, PDO::PARAM_INT);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':gender', $gender);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':dob', $dob);
            $stmt->execute();

            echo "Sign-up successful!";
        } else {
            echo "Please fill all required fields.";
        }
    }

    if ($isLogin) {
        $stmt = $db->prepare("SELECT password FROM student WHERE register_number = :regno");
        $stmt->bindParam(':regno', $regno, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && password_verify($password, $row['password'])) {
            echo "Login successful!";
        } else {
            echo "Invalid register number or password.";
        }
    }
}
?>