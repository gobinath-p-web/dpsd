<?php
try {
    // Absolute path to the database file
    $dbPath = 'D:/docs/repos/depsdoc/dpsd/deptdocs.db';

    // Connect to SQLite database
    $db = new PDO('sqlite:' . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Define required tables
    $requiredTables = ['attendance_summary','student', 'attendance','hod','staff'];
    $schemaFile = __DIR__ . '/tables.sql';

    // Check which tables are missing
    $missingTables = [];
    foreach ($requiredTables as $table) {
        $stmt = $db->prepare("SELECT name FROM sqlite_master WHERE type='table' AND name = ?");
        $stmt->execute([$table]);
        if (!$stmt->fetch()) {
            $missingTables[] = $table;
        }
    }

    // Load schema if any required table is missing
    if (!empty($missingTables)) {
        if (!file_exists($schemaFile)) {
            die("Schema file not found at: " . htmlspecialchars($schemaFile));
        }

        $schema = file_get_contents($schemaFile);
        if ($schema === false) {
            die("Failed to read schema file.");
        }

        $result = $db->exec($schema);
        if ($result === false) {
            $error = $db->errorInfo();
            die("Schema execution failed: " . htmlspecialchars($error[2]));
        }
    }

} catch (PDOException $e) {
    die("Database connection failed: " . htmlspecialchars($e->getMessage()));
}
?>