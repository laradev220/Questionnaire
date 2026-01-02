<?php

/**
 * Start a survey based on token
 */
function survey_start_by_token($token)
{
    $db = get_db_connection();

    // Find survey by token
    $stmt = $db->prepare("SELECT * FROM surveys WHERE link_token = ? AND is_active = 1");
    $stmt->execute([$token]);
    $survey = $stmt->fetch();

    if (!$survey) {
        http_response_code(404);
        echo "Survey not found or inactive";
        exit;
    }

    // Check deadline
    if ($survey['deadline'] && strtotime($survey['deadline']) < time()) {
        echo "This survey has expired";
        exit;
    }

    // Store survey info in session
    $_SESSION['survey_id'] = $survey['id'];
    $_SESSION['survey_title'] = $survey['title'];

    // Check if participant form was submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        return survey_start_participant($survey['id']);
    } else {
        // Show participant form
        include __DIR__ . '/../templates/survey/participant_form.php';
        exit;
    }
}

/**
 * Start participant registration for a specific survey
 */
function survey_start_participant($survey_id)
{
    $db = get_db_connection();
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $university = $_POST['university'];
    $designation = $_POST['designation'];

    // Check if participant exists for this survey
    $stmt = $db->prepare("SELECT id, name FROM participants WHERE email = ? AND survey_id = ?");
    $stmt->execute([$email, $survey_id]);
    $existing = $stmt->fetch();

    if ($existing) {
        $participantId = $existing['id'];
        $name = $existing['name'];
    } else {
        // Insert new participant for this survey
        $stmt = $db->prepare("INSERT INTO participants (survey_id, name, email, phone, university, designation) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$survey_id, $name, $email, $phone, $university, $designation]);
        $participantId = $db->lastInsertId();
    }

    // Check for active session for this survey
    $stmt = $db->prepare("SELECT id FROM survey_sessions WHERE participant_id = ? AND survey_id = ? AND is_completed = 0 ORDER BY id DESC LIMIT 1");
    $stmt->execute([$participantId, $survey_id]);
    $session = $stmt->fetch();

    if (!$session) {
        // Create new survey session
        $stmt = $db->prepare("INSERT INTO survey_sessions (participant_id, survey_id) VALUES (?, ?)");
        $stmt->execute([$participantId, $survey_id]);
    }

    // Store in session
    $_SESSION['participant_id'] = $participantId;
    $_SESSION['participant_name'] = $name;

    // Redirect to progress dashboard first
    header("Location: " . BASE_PATH . "/progress");
    exit;
}

/**
 * Legacy function for backward compatibility
 * TODO: Remove this once all routes are updated
 */
function survey_start()
{
    // For now, redirect to home - this should be updated
    header("Location: " . BASE_PATH . "/");
    exit;
}

function survey_dashboard()
{
    $db = get_db_connection();
    try {
        $participantId = $_SESSION['participant_id'];
        $surveyId = $_SESSION['survey_id'] ?? null;
        $surveyTitle = $_SESSION['survey_title'] ?? 'Survey';

        if (!$surveyId) {
            header("Location: " . BASE_PATH . "/");
            exit;
        }

        // Get survey details
        $stmt = $db->prepare("SELECT * FROM surveys WHERE id = ?");
        $stmt->execute([$surveyId]);
        $survey = $stmt->fetch();

        if (!$survey) {
            header("Location: " . BASE_PATH . "/");
            exit;
        }

        // Check if participant has already completed this survey
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM survey_sessions WHERE participant_id = ? AND survey_id = ? AND is_completed = 1");
        $stmt->execute([$participantId, $surveyId]);
        $completedCount = $stmt->fetch()['count'];

        if ($completedCount > 0) {
            // Participant has completed this survey, show completed status
            $session = ['is_completed' => 1];
        } else {
            // Find or create active session for this survey
            $stmt = $db->prepare("SELECT * FROM survey_sessions WHERE participant_id = ? AND survey_id = ? AND is_completed = 0");
            $stmt->execute([$participantId, $surveyId]);
            $session = $stmt->fetch();

            if (!$session) {
                $stmt = $db->prepare("INSERT INTO survey_sessions (participant_id, survey_id) VALUES (?, ?)");
                $stmt->execute([$participantId, $surveyId]);
                $stmt = $db->prepare("SELECT * FROM survey_sessions WHERE participant_id = ? AND survey_id = ? AND is_completed = 0");
                $stmt->execute([$participantId, $surveyId]);
                $session = $stmt->fetch();
            }
        }

        // Get survey structure for progress display
        $stmt = $db->prepare("
            SELECT q.module
            FROM survey_questions sq
            JOIN questions q ON sq.question_id = q.id
            WHERE sq.survey_id = ?
            GROUP BY q.module
            ORDER BY MIN(sq.order_index)
        ");
        $stmt->execute([$surveyId]);
        $modules = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Extract variables for template
        $template_vars = [
            'survey' => $survey,
            'session' => $session,
            'modules' => $modules,
            'surveyTitle' => $surveyTitle
        ];
        extract($template_vars);

        include __DIR__ . '/../templates/survey/dashboard.php';
    } catch (Exception $e) {
        header("Location: " . BASE_PATH . "/");
        exit;
    }
}

function survey_show_module($moduleId, $page = 1)
{
    $db = get_db_connection();
    try {
        $participantId = $_SESSION['participant_id'];
        $surveyId = $_SESSION['survey_id'];

        // Load questions assigned to this survey
        $stmt = $db->prepare("
            SELECT q.id, q.code, q.text, q.module, q.`group`, q.type
            FROM survey_questions sq
            JOIN questions q ON sq.question_id = q.id
            WHERE sq.survey_id = ?
            ORDER BY sq.order_index
        ");
        $stmt->execute([$surveyId]);
        $allQuestions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Load options for multiple choice questions
        $questionIds = array_column($allQuestions, 'id');
        $options = [];
        if (!empty($questionIds)) {
            $placeholders = str_repeat('?,', count($questionIds) - 1) . '?';
            $optionStmt = $db->prepare("SELECT question_id, option_text, option_value FROM question_options WHERE question_id IN ($placeholders) ORDER BY order_index");
            $optionStmt->execute($questionIds);
            $optionRows = $optionStmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($optionRows as $row) {
                $options[$row['question_id']][] = $row;
            }
        }

        // Group questions by module
        $modules = [];
        foreach ($allQuestions as $question) {
            $question['options'] = $options[$question['id']] ?? [];
            $modules[$question['module']][] = $question;
        }

        // Convert to array for indexing
        $moduleArray = array_values($modules);
        $totalModules = count($moduleArray);

        if ($moduleId > $totalModules) {
            header("Location: " . BASE_PATH . "/thank-you");
            exit;
        }

        $currentModuleQuestions = $moduleArray[$moduleId - 1];
        $moduleName = array_keys($modules)[$moduleId - 1];

        // Group questions by their group for pagination
        $groups = [];
        foreach ($currentModuleQuestions as $question) {
            $group = $question['group'] ?: 'General';
            $groups[$group][] = $question;
        }

        $groupArray = array_values($groups);
        $totalPages = count($groupArray);
        $page = max(1, min($page, $totalPages));

        $currentGroupQuestions = $groupArray[$page - 1];
        $currentGroup = array_keys($groups)[$page - 1];

        // Convert to code => question data format for template compatibility
        $questions = [];
        foreach ($currentGroupQuestions as $question) {
            $questions[$question['code']] = [
                'text' => $question['text'],
                'type' => $question['type'],
                'options' => $question['options']
            ];
        }

        // Pass all required variables to template
        $template_vars = [
            'moduleId' => $moduleId,
            'totalModules' => $totalModules,
            'page' => $page,
            'totalPages' => $totalPages,
            'module' => [
                'title' => $moduleName,
                'group' => $currentGroup,
                'questions' => $questions
            ]
        ];

        // Extract variables for template
        extract($template_vars);

        include __DIR__ . '/../templates/survey/module.php';
    } catch (Exception $e) {
        header("Location: " . BASE_PATH . "/");
        exit;
    }
}

function survey_store_module($moduleId, $page)
{
    $db = get_db_connection();
    try {
        $participantId = $_SESSION['participant_id'];
        $surveyId = $_SESSION['survey_id'];

        // Get active session for this survey
        $stmt = $db->prepare("SELECT id FROM survey_sessions WHERE participant_id = ? AND survey_id = ? AND is_completed = 0");
        $stmt->execute([$participantId, $surveyId]);
        $session = $stmt->fetch();

        if (!$session) {
            header("Location: " . BASE_PATH . "/");
            exit;
        }

        // Load questions assigned to this survey
        $stmt = $db->prepare("
            SELECT q.id, q.code, q.text, q.module, q.`group`, q.type
            FROM survey_questions sq
            JOIN questions q ON sq.question_id = q.id
            WHERE sq.survey_id = ?
            ORDER BY sq.order_index
        ");
        $stmt->execute([$surveyId]);
        $allQuestions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Group questions by module
        $modules = [];
        foreach ($allQuestions as $question) {
            $modules[$question['module']][] = $question;
        }

        $moduleArray = array_values($modules);
        $totalModules = count($moduleArray);

        if ($moduleId > $totalModules) {
            $db->prepare("UPDATE survey_sessions SET is_completed = 1 WHERE id = ?")->execute([$session['id']]);
            header("Location: " . BASE_PATH . "/thank-you");
            exit;
        }

        $currentModuleQuestions = $moduleArray[$moduleId - 1];

        // Group questions by their group for pagination
        $groups = [];
        foreach ($currentModuleQuestions as $question) {
            $group = $question['group'] ?: 'General';
            $groups[$group][] = $question;
        }

        $groupArray = array_values($groups);
        $totalPages = count($groupArray);

        if ($page > $totalPages) {
            // Next module
            $nextModule = $moduleId + 1;
            if ($nextModule > $totalModules) {
                $db->prepare("UPDATE survey_sessions SET is_completed = 1 WHERE id = ?")->execute([$session['id']]);
                header("Location: " . BASE_PATH . "/thank-you");
            } else {
                header("Location: " . BASE_PATH . "/survey?module=" . $nextModule);
            }
            exit;
        }

        $currentGroupQuestions = $groupArray[$page - 1];

        // Save answers for all questions in group
        $weights = [1 => 1.0, 2 => 1.5, 3 => 2.0, 4 => 2.5, 5 => 3.0];
        $insertStmt = $db->prepare("INSERT INTO responses (session_id, question_id, score, weight) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE score = VALUES(score), weight = VALUES(weight)");

        foreach ($currentGroupQuestions as $question) {
            $code = $question['code'];
            $qid = $question['id'];
            $value = $_POST[$code] ?? null;

            $score = null;
            $weight = null;
            if ($value !== null && is_numeric($value) && $value >= 1 && $value <= 5) {
                $score = $value;
                $weight = $weights[$value];
            }
            $insertStmt->execute([$session['id'], $qid, $score, $weight]);
        }

        // Navigate
        if ($page < $totalPages) {
            header("Location: " . BASE_PATH . "/survey?module=" . $moduleId . "&page=" . ($page + 1));
        } else {
            // Next module
            $nextModule = $moduleId + 1;
            if ($nextModule > $totalModules) {
                $db->prepare("UPDATE survey_sessions SET is_completed = 1 WHERE id = ?")->execute([$session['id']]);
                header("Location: " . BASE_PATH . "/thank-you");
            } else {
                header("Location: " . BASE_PATH . "/survey?module=" . $nextModule);
            }
        }
    } catch (Exception $e) {
        header("Location: " . BASE_PATH . "/");
        exit;
    }
}
