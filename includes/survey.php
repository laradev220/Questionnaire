<?php
$moduleNames = [
    1 => 'Sustainable Human Resource Management (SHRM)',
    2 => 'Changing Dynamic Capabilities (CDC) & Employee Reciprocity',
    3 => 'Human Resource Analytics (HRA)',
    4 => 'HR Competencies (HRC)',
    5 => 'Knowledge Management (KM)',
    6 => 'Organisational Resilience (OR)'
];

function survey_start() {
    $db = get_db_connection();
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $university = $_POST['university'];
    $designation = $_POST['designation'];

    // Check if participant exists
    $stmt = $db->prepare("SELECT id, name FROM participants WHERE email = ?");
    $stmt->execute([$email]);
    $existing = $stmt->fetch();

    if ($existing) {
        $participantId = $existing['id'];
        $name = $existing['name'];
    } else {
        // Insert new participant
        $stmt = $db->prepare("INSERT INTO participants (name, email, phone, university, designation) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $phone, $university, $designation]);
        $participantId = $db->lastInsertId();
    }

    // Check for active session
    $stmt = $db->prepare("SELECT id FROM survey_sessions WHERE participant_id = ? AND is_completed = 0 ORDER BY id DESC LIMIT 1");
    $stmt->execute([$participantId]);
    $session = $stmt->fetch();

    if (!$session) {
        // Create new survey session
        $stmt = $db->prepare("INSERT INTO survey_sessions (participant_id) VALUES (?)");
        $stmt->execute([$participantId]);
    }

    // Store in session
    $_SESSION['participant_id'] = $participantId;
    $_SESSION['participant_name'] = $name;

    header("Location: " . BASE_PATH . "/dashboard");
    exit;
}

function survey_dashboard() {
    global $moduleNames;
    $db = get_db_connection();
    try {
        $participantId = $_SESSION['participant_id'];

        // Check if participant has already completed a survey
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM survey_sessions WHERE participant_id = ? AND is_completed = 1");
        $stmt->execute([$participantId]);
        $completedCount = $stmt->fetch()['count'];

        if ($completedCount > 0) {
            // Participant has completed before, show completed status
            $session = ['is_completed' => 1, 'current_module' => 6]; // Dummy session for display
        } else {
            // Find or create active session
            $stmt = $db->prepare("SELECT * FROM survey_sessions WHERE participant_id = ? AND is_completed = 0");
            $stmt->execute([$participantId]);
            $session = $stmt->fetch();

            if (!$session) {
                $db->prepare("INSERT INTO survey_sessions (participant_id) VALUES (?)")->execute([$participantId]);
                $stmt = $db->prepare("SELECT * FROM survey_sessions WHERE participant_id = ? AND is_completed = 0");
                $stmt->execute([$participantId]);
                $session = $stmt->fetch();
            }
        }

        include __DIR__ . '/../templates/survey/dashboard.php';
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
}

function survey_show_module($moduleId, $page = 1) {
    global $moduleNames;
    $db = get_db_connection();
    try {
        if (!isset($moduleNames[$moduleId])) {
            header("Location: " . BASE_PATH . "/thank-you");
            exit;
        }

        $moduleName = $moduleNames[$moduleId];
        $stmt = $db->prepare("SELECT `group` FROM questions WHERE module = ? GROUP BY `group` ORDER BY MIN(id)");
        $stmt->execute([$moduleName]);
        $groups = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $totalPages = count($groups);
        $page = max(1, min($page, $totalPages));
        $totalModules = count($moduleNames);

        $currentGroup = $groups[$page - 1];
        $stmt = $db->prepare("SELECT code, text FROM questions WHERE module = ? AND `group` = ? ORDER BY id");
        $stmt->execute([$moduleName, $currentGroup]);
        $questions = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // code => text

        $module = [
            'title' => $moduleName,
            'group' => $currentGroup,
            'questions' => $questions
        ];

        include __DIR__ . '/../templates/survey/module.php';
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
}

function survey_store_module($moduleId, $page) {
    global $moduleNames;
    $db = get_db_connection();
    try {
        $participantId = $_SESSION['participant_id'];

        // Get participant details
        $stmt = $db->prepare("SELECT name, email, phone FROM participants WHERE id = ?");
        $stmt->execute([$participantId]);
        $participant = $stmt->fetch();

        // Get active session
        $stmt = $db->prepare("SELECT id FROM survey_sessions WHERE participant_id = ? AND is_completed = 0");
        $stmt->execute([$participantId]);
        $session = $stmt->fetch();

        if (!$session) {
            die("No active session");
        }

        // Get groups for this module
        $moduleName = $moduleNames[$moduleId];
        $stmt = $db->prepare("SELECT `group` FROM questions WHERE module = ? GROUP BY `group` ORDER BY MIN(id)");
        $stmt->execute([$moduleName]);
        $groups = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $totalPages = count($groups);
        $currentGroup = $groups[$page - 1];

        // Get all questions in the current group
        $stmt = $db->prepare("SELECT code, text FROM questions WHERE module = ? AND `group` = ? ORDER BY id");
        $stmt->execute([$moduleName, $currentGroup]);
        $questions = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // code => text

        // Save Answers for current group
        $weights = [1 => 1.0, 2 => 1.5, 3 => 2.0, 4 => 2.5, 5 => 3.0];
        $insertStmt = $db->prepare("INSERT INTO responses (session_id, question_id, score, weight) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE score = VALUES(score), weight = VALUES(weight)");

        foreach ($questions as $code => $text) {
            $value = $_POST[$code] ?? null;
            if ($value === null || (is_numeric($value) && $value >= 1 && $value <= 5)) {
                $score = $value;
                $weight = $value ? $weights[$value] : null;
                $insertStmt->execute([$session['id'], $code, $score, $weight]);
            }
        }

        // Navigate
        if ($page < $totalPages) {
            header("Location: " . BASE_PATH . "/survey?module=" . $moduleId . "&page=" . ($page + 1));
        } else {
            // Next module
            $nextModule = $moduleId + 1;
            if ($nextModule > count($moduleNames)) {
                $db->prepare("UPDATE survey_sessions SET is_completed = 1 WHERE id = ?")->execute([$session['id']]);
                header("Location: " . BASE_PATH . "/thank-you");
            } else {
                $db->prepare("UPDATE survey_sessions SET current_module = ? WHERE id = ?")->execute([$nextModule, $session['id']]);
                header("Location: " . BASE_PATH . "/survey?module=" . $nextModule);
            }
        }
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
}
?>