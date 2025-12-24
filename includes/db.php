<?php
try {
    // Connect to SQLite database (adjust path if needed)
    $db = new PDO('sqlite:' . __DIR__ . '/../deptdocs.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Define required tables
    $requiredTables = ['student', 'attendance'];
    $schemaFile = __DIR__ . '/../tables.sql';

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
            die("Schema file not found at: $schemaFile");
        }

        $schema = file_get_contents($schemaFile);
        if ($schema === false) {
            die("Failed to read schema file.");
        }

        $result = $db->exec($schema);
        if ($result === false) {
            $error = $db->errorInfo();
            die("Schema execution failed: " . $error[2]);
        }
    }

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>