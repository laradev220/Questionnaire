<?php

namespace App;

require_once 'DB.php';

$db = DB::getInstance()->getConnection();

$csvFile = dirname(__DIR__) . '/questionnaire.csv';

if (($handle = fopen($csvFile, 'r')) !== FALSE) {
    // Skip header
    fgetcsv($handle);

    $stmt = $db->prepare("INSERT INTO questions (module, `group`, code, text) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE module = VALUES(module), `group` = VALUES(`group`), text = VALUES(text)");

    while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
        $module = $data[0];
        $group = $data[1] ?: null; // If empty, null
        $code = $data[2];
        $text = $data[3];

        $stmt->execute([$module, $group, $code, $text]);
    }

    fclose($handle);
    echo "Questions imported successfully.\n";
} else {
    echo "Error opening CSV file.\n";
}
?>