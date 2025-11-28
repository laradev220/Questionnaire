<?php

namespace App;

require_once 'vendor/autoload.php';

$db = DB::getInstance()->getConnection();

$stmt = $db->query("SELECT COUNT(*) as total FROM responses");
$result = $stmt->fetch();
echo "Total responses: " . $result['total'] . "\n";

    $stmt = $db->query("SELECT session_id, question_id, score, weight FROM responses LIMIT 5");
    $responses = $stmt->fetchAll();
    echo "Sample responses:\n";
    foreach ($responses as $row) {
        echo "Session: {$row['session_id']}, Question: {$row['question_id']}, Score: {$row['score']}, Weight: {$row['weight']}\n";
    }
?>