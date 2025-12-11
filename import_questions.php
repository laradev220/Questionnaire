<?php
require_once 'config.php';
require_once 'includes/db.php';

$db = get_db_connection();

$handle = fopen('questionnaire.csv', 'r');
if ($handle === false) {
    die("Cannot open CSV file\n");
}

// Skip header
fgetcsv($handle);

$stmt = $db->prepare("INSERT INTO questions (module, `group`, code, text) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE module = VALUES(module), `group` = VALUES(`group`), text = VALUES(text)");

$inserted = 0;
while (($data = fgetcsv($handle, 1000, ',')) !== false) {
    if (count($data) >= 4) {
        $stmt->execute([$data[0], $data[1], $data[2], $data[3]]);
        $inserted++;
    }
}

fclose($handle);

echo "Imported $inserted questions\n";
?>