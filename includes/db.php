<?php
try {
    $db = new PDO('sqlite:' . __DIR__ . '/../deptdocs.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Optional: create schema if student table doesn't exist
    $result = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='student'");
    if (!$result->fetch()) {
        $schemaFile = __DIR__ . '/../tables.sql';
        if (file_exists($schemaFile)) {
            $schema = file_get_contents($schemaFile);
            if ($schema !== false) {
                $db->exec($schema);
            }
        }
    }
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>