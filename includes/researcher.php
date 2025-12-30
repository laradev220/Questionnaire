<?php
// Researcher functions for multi-tenant survey system

/**
 * Show researcher dashboard with their surveys
 */
function researcher_dashboard() {
    require_researcher();
    $user = get_authenticated_user();
    $db = get_db_connection();

    // Get researcher's surveys
    $stmt = $db->prepare("
        SELECT s.id, s.title, s.description, s.created_at, s.deadline, s.is_active
        FROM surveys s
        WHERE s.user_id = ?
        ORDER BY s.created_at DESC
    ");
    $stmt->execute([$user['id']]);
    $surveys = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get stats for each survey
    foreach ($surveys as $key => $survey) {
        // Participant count (survey sessions)
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM survey_sessions WHERE survey_id = ?");
        $stmt->execute([$survey['id']]);
        $surveys[$key]['participant_count'] = $stmt->fetch()['count'];

        // Completed count
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM survey_sessions WHERE survey_id = ? AND is_completed = 1");
        $stmt->execute([$survey['id']]);
        $surveys[$key]['completed_count'] = $stmt->fetch()['count'];

        // Response count
        $stmt = $db->prepare("SELECT COUNT(DISTINCT r.id) as count FROM responses r JOIN survey_sessions ss ON r.session_id = ss.id WHERE ss.survey_id = ?");
        $stmt->execute([$survey['id']]);
        $surveys[$key]['response_count'] = $stmt->fetch()['count'];
    }

    // Ensure no duplicates (defensive programming)
    $unique_surveys = [];
    foreach ($surveys as $survey) {
        $unique_surveys[$survey['id']] = $survey;
    }
    $surveys = array_values($unique_surveys);

    // Calculate completion rates
    foreach ($surveys as $key => $survey) {
        $surveys[$key]['completion_rate'] = $survey['participant_count'] > 0
            ? round(($survey['completed_count'] / $survey['participant_count']) * 100, 1)
            : 0;
    }

    include __DIR__ . '/../templates/researcher/dashboard.php';
}

function researcher_create_survey() {
    require_researcher();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user = get_authenticated_user();
        $db = get_db_connection();

        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $deadline = !empty($_POST['deadline']) ? $_POST['deadline'] . ' 23:59:59' : null;

        if (empty($title)) {
            $error = 'Survey title is required';
            include __DIR__ . '/../templates/researcher/create_survey.php';
            return;
        }

        try {
            $stmt = $db->prepare("INSERT INTO surveys (user_id, title, description, deadline, link_token) VALUES (?, ?, ?, ?, ?)");
            $link_token = bin2hex(random_bytes(16)); // Generate unique token
            $stmt->execute([$user['id'], $title, $description, $deadline, $link_token]);

            $survey_id = $db->lastInsertId();

            // Redirect to question assignment
            header("Location: " . BASE_PATH . "/surveys/{$survey_id}/questions");
            exit;
        } catch (Exception $e) {
            $error = 'Failed to create survey: ' . $e->getMessage();
            include __DIR__ . '/../templates/researcher/create_survey.php';
        }
        return;
    }

    // Show the create survey form
    include __DIR__ . '/../templates/researcher/create_survey.php';
}

/**
 * Show survey edit form
 */
function researcher_edit_survey($survey_id) {
    require_researcher();
    $user = get_authenticated_user();

    if (!can_access_survey($survey_id)) {
        http_response_code(403);
        echo "Access denied";
        return;
    }

    $db = get_db_connection();
    $stmt = $db->prepare("SELECT * FROM surveys WHERE id = ?");
    $stmt->execute([$survey_id]);
    $survey = $stmt->fetch();

    if (!$survey) {
        http_response_code(404);
        echo "Survey not found";
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        return researcher_update_survey($survey_id);
    }

    // Get currently assigned questions
    $stmt = $db->prepare("SELECT question_id FROM survey_questions WHERE survey_id = ? ORDER BY order_index");
    $stmt->execute([$survey_id]);
    $assigned_question_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

    include __DIR__ . '/../templates/researcher/edit_survey.php';
}

/**
 * Update survey
 */
function researcher_update_survey($survey_id) {
    require_researcher();

    if (!can_access_survey($survey_id)) {
        http_response_code(403);
        echo "Access denied";
        return;
    }

    $db = get_db_connection();
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $deadline = !empty($_POST['deadline']) ? $_POST['deadline'] . ' 23:59:59' : null;
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if (empty($title)) {
        $error = 'Survey title is required';
        include __DIR__ . '/../templates/researcher/edit_survey.php';
        return;
    }

    try {
        $stmt = $db->prepare("UPDATE surveys SET title = ?, description = ?, deadline = ?, is_active = ? WHERE id = ?");
        $stmt->execute([$title, $description, $deadline, $is_active, $survey_id]);

        header("Location: " . BASE_PATH . "/dashboard");
        exit;
    } catch (Exception $e) {
        $error = 'Failed to update survey: ' . $e->getMessage();
    include __DIR__ . '/../templates/researcher/edit_survey.php';
}
}

/**
 * Delete survey (with confirmation)
 */
function researcher_delete_survey($survey_id) {
    require_researcher();

    if (!can_access_survey($survey_id)) {
        http_response_code(403);
        echo "Access denied";
        return;
    }

    $db = get_db_connection();

    // Check if survey has participants
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM survey_sessions WHERE survey_id = ?");
    $stmt->execute([$survey_id]);
    $participant_count = $stmt->fetch()['count'];

    if ($participant_count > 0) {
        // Show confirmation page
        $stmt = $db->prepare("SELECT title FROM surveys WHERE id = ?");
        $stmt->execute([$survey_id]);
        $survey = $stmt->fetch();

        include __DIR__ . '/../templates/researcher/delete_survey_confirm.php';
        return;
    }

    // Safe to delete
    researcher_perform_delete_survey($survey_id);
}

/**
 * Actually delete survey after confirmation
 */
function researcher_confirm_delete_survey($survey_id) {
    require_researcher();

    if (!can_access_survey($survey_id)) {
        http_response_code(403);
        echo "Access denied";
        return;
    }

    researcher_perform_delete_survey($survey_id);
}

/**
 * Perform the actual survey deletion
 */
function researcher_perform_delete_survey($survey_id) {
    $db = get_db_connection();

    try {
        $db->beginTransaction();

        // Delete in correct order to respect foreign keys
        $db->prepare("DELETE FROM responses WHERE session_id IN (SELECT id FROM survey_sessions WHERE survey_id = ?)")->execute([$survey_id]);
        $db->prepare("DELETE FROM survey_sessions WHERE survey_id = ?")->execute([$survey_id]);
        $db->prepare("DELETE FROM survey_questions WHERE survey_id = ?")->execute([$survey_id]);
        $db->prepare("DELETE FROM surveys WHERE id = ?")->execute([$survey_id]);

        $db->commit();

        header("Location: " . BASE_PATH . "/dashboard");
        exit;
    } catch (Exception $e) {
        $db->rollback();
        echo "Failed to delete survey: " . $e->getMessage();
    }
}

/**
 * Show question assignment interface for survey
 */
function researcher_assign_questions($survey_id) {
    require_researcher();
    $user = get_authenticated_user();

    if (!can_access_survey($survey_id)) {
        http_response_code(403);
        echo "Access denied";
        return;
    }

    $db = get_db_connection();

    // Get survey info
    $stmt = $db->prepare("SELECT * FROM surveys WHERE id = ?");
    $stmt->execute([$survey_id]);
    $survey = $stmt->fetch();

    if (!$survey) {
        http_response_code(404);
        echo "Survey not found";
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        return researcher_save_question_assignments($survey_id);
    }

    // Get researcher's questions grouped by module
    $stmt = $db->prepare("SELECT * FROM questions WHERE user_id = ? ORDER BY module, id");
    $stmt->execute([$user['id']]);
    $all_questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Group questions by module
    $questions_by_module = [];
    foreach ($all_questions as $question) {
        $questions_by_module[$question['module']][] = $question;
    }

    // Get currently assigned questions
    $stmt = $db->prepare("SELECT question_id FROM survey_questions WHERE survey_id = ? ORDER BY order_index");
    $stmt->execute([$survey_id]);
    $assigned_question_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

    include __DIR__ . '/../templates/researcher/assign_questions.php';
}

/**
 * Save question assignments for survey
 */
function researcher_save_question_assignments($survey_id) {
    require_researcher();

    if (!can_access_survey($survey_id)) {
        http_response_code(403);
        echo "Access denied";
        return;
    }

    $db = get_db_connection();

    try {
        $db->beginTransaction();

        // Delete existing assignments
        $db->prepare("DELETE FROM survey_questions WHERE survey_id = ?")->execute([$survey_id]);

        // Add new assignments
        if (isset($_POST['questions']) && is_array($_POST['questions'])) {
            $questions = $_POST['questions'];
            $order = 0;
            foreach ($questions as $question_id) {
                $stmt = $db->prepare("INSERT INTO survey_questions (survey_id, question_id, order_index) VALUES (?, ?, ?)");
                $stmt->execute([$survey_id, $question_id, $order++]);
            }
        }

        $db->commit();

        header("Location: " . BASE_PATH . "/surveys/{$survey_id}/link");
        exit;
    } catch (Exception $e) {
        $db->rollback();
        echo "Failed to save question assignments: " . $e->getMessage();
    }
}

/**
 * Generate and show survey link
 */
function researcher_generate_link($survey_id) {
    require_researcher();

    if (!can_access_survey($survey_id)) {
        http_response_code(403);
        echo "Access denied";
        return;
    }

    $db = get_db_connection();
    $stmt = $db->prepare("SELECT * FROM surveys WHERE id = ?");
    $stmt->execute([$survey_id]);
    $survey = $stmt->fetch();

    if (!$survey) {
        http_response_code(404);
        echo "Survey not found";
        return;
    }

    // Generate link using current host and protocol
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $link = "$protocol://$host" . BASE_PATH . "/s/" . $survey['link_token'];

    include __DIR__ . '/../templates/researcher/survey_link.php';
}

/**
 * Show survey analytics for researcher
 */
function researcher_survey_analytics($survey_id) {
    require_researcher();

    if (!can_access_survey($survey_id)) {
        http_response_code(403);
        echo "Access denied";
        return;
    }

    $db = get_db_connection();

    // Get survey info
    $stmt = $db->prepare("SELECT * FROM surveys WHERE id = ?");
    $stmt->execute([$survey_id]);
    $survey = $stmt->fetch();

    if (!$survey) {
        http_response_code(404);
        echo "Survey not found";
        return;
    }

    // Get basic stats
    $stmt = $db->prepare("
        SELECT
            COUNT(DISTINCT ss.id) as total_sessions,
            COUNT(DISTINCT CASE WHEN ss.is_completed = 1 THEN ss.id END) as completed_sessions,
            COUNT(DISTINCT r.id) as total_responses
        FROM survey_sessions ss
        LEFT JOIN responses r ON ss.id = r.session_id
        WHERE ss.survey_id = ?
    ");
    $stmt->execute([$survey_id]);
    $stats = $stmt->fetch();

    $stats['completion_rate'] = $stats['total_sessions'] > 0
        ? round(($stats['completed_sessions'] / $stats['total_sessions']) * 100, 1)
        : 0;

    // Get average scores by question
    $stmt = $db->prepare("
        SELECT q.id, q.code, q.text, q.type,
               COALESCE(AVG(r.score), 0) as avg_score,
               COUNT(r.score) as response_count,
               MIN(sq.order_index) as question_order,
               MIN(ss.created_at) as first_response,
               MAX(ss.created_at) as last_response
        FROM survey_questions sq
        JOIN questions q ON sq.question_id = q.id
        LEFT JOIN responses r ON q.id = r.question_id
        LEFT JOIN survey_sessions ss ON r.session_id = ss.id AND ss.survey_id = sq.survey_id
        WHERE sq.survey_id = ?
        GROUP BY q.id, q.code, q.text, q.type
        ORDER BY question_order
    ");
    $stmt->execute([$survey_id]);
    $question_averages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get response distributions for charts
    $response_distributions = [];
    foreach ($question_averages as $question) {
        $question_id = $question['id'];

        if ($question['type'] === 'scale') {
            // For scale questions, count responses by score (1-5)
            $stmt = $db->prepare("
                SELECT r.score, COUNT(*) as count
                FROM responses r
                JOIN survey_sessions ss ON r.session_id = ss.id
                WHERE r.question_id = ? AND ss.survey_id = ? AND r.score IS NOT NULL
                GROUP BY r.score
                ORDER BY r.score
            ");
            $stmt->execute([$question_id, $survey_id]);
            $scores = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

            $response_distributions[$question_id] = [
                'type' => 'scale',
                'data' => array_replace([1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0], $scores)
            ];
        } elseif ($question['type'] === 'multiple_choice') {
            // For multiple choice questions, count responses by option
            $stmt = $db->prepare("
                SELECT qo.option_text, COUNT(r.id) as count
                FROM question_options qo
                LEFT JOIN responses r ON qo.question_id = r.question_id AND qo.option_value = r.score
                LEFT JOIN survey_sessions ss ON r.session_id = ss.id AND ss.survey_id = ?
                WHERE qo.question_id = ?
                GROUP BY qo.option_text, qo.order_index
                ORDER BY qo.order_index
            ");
            $stmt->execute([$survey_id, $question_id]);
            $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $response_distributions[$question_id] = [
                'type' => 'multiple_choice',
                'data' => array_column($options, 'count', 'option_text')
            ];
        }
    }

    // Get survey response period and timeline data
    $stmt = $db->prepare("
        SELECT MIN(created_at) as survey_start, MAX(created_at) as survey_end
        FROM survey_sessions
        WHERE survey_id = ? AND is_completed = 1
    ");
    $stmt->execute([$survey_id]);
    $survey_period = $stmt->fetch();

    // Get response timeline data (daily response counts)
    $stmt = $db->prepare("
        SELECT DATE(ss.created_at) as response_date, COUNT(*) as daily_responses
        FROM survey_sessions ss
        WHERE ss.survey_id = ?
        GROUP BY DATE(ss.created_at)
        ORDER BY response_date
    ");
    $stmt->execute([$survey_id]);
    $response_timeline = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate insights
    $insights = [];
    if (!empty($question_averages)) {
        // Filter out null responses for accurate calculations
        $valid_responses = array_filter($question_averages, fn($q) => $q['response_count'] > 0);

        if (!empty($valid_responses)) {
            $max_responses = max(array_column($valid_responses, 'response_count'));
            $min_responses = min(array_column($valid_responses, 'response_count'));

            // Get scale questions only for rating calculations
            $scale_questions = array_filter($valid_responses, fn($q) => $q['type'] == 'scale' && $q['avg_score'] !== null);
            if (!empty($scale_questions)) {
                $highest_avg = max(array_column($scale_questions, 'avg_score'));
                $lowest_avg = min(array_column($scale_questions, 'avg_score'));

                $insights['highest_rated'] = array_filter($scale_questions, fn($q) => $q['avg_score'] == $highest_avg);
                $insights['lowest_rated'] = array_filter($scale_questions, fn($q) => $q['avg_score'] == $lowest_avg);
            }

            $insights['most_responded'] = array_filter($valid_responses, fn($q) => $q['response_count'] == $max_responses);
            $insights['least_responded'] = array_filter($valid_responses, fn($q) => $q['response_count'] == $min_responses);
        }

        // Add survey period insights
        $insights['survey_period'] = [
            'start' => $survey_period['survey_start'] ?? null,
            'end' => $survey_period['survey_end'] ?? null
        ];
    }

    include __DIR__ . '/../templates/researcher/survey_analytics.php';
}

/**
 * Show survey participants list
 */
function researcher_survey_participants($survey_id) {
    require_researcher();

    $db = get_db_connection();

    // Get survey info
    $stmt = $db->prepare("SELECT * FROM surveys WHERE id = ?");
    $stmt->execute([$survey_id]);
    $survey = $stmt->fetch();

    if (!$survey) {
        http_response_code(404);
        echo "Survey not found";
        return;
    }

    // Get participants with their progress
    $stmt = $db->prepare("
        SELECT
            p.id,
            p.name,
            p.email,
            p.phone,
            p.university,
            p.designation,
            ss.id as session_id,
            ss.is_completed,
            ss.created_at as started_at,
            COUNT(r.id) as response_count,
            ss.created_at as last_activity
        FROM participants p
        JOIN survey_sessions ss ON p.id = ss.participant_id AND ss.survey_id = ?
        LEFT JOIN responses r ON ss.id = r.session_id
        GROUP BY p.id, p.name, p.email, p.phone, p.university, p.designation, ss.id, ss.is_completed, ss.created_at
        ORDER BY ss.created_at DESC
    ");
    $stmt->execute([$survey_id]);
    $participants = $stmt->fetchAll(PDO::FETCH_ASSOC);

    include __DIR__ . '/../templates/researcher/survey_participants.php';
}

/**
 * Show individual participant responses
 */
function researcher_participant_responses($survey_id, $participant_id) {
    require_researcher();

    $db = get_db_connection();

    // Get survey and participant info
    $stmt = $db->prepare("SELECT * FROM surveys WHERE id = ?");
    $stmt->execute([$survey_id]);
    $survey = $stmt->fetch();

    $stmt = $db->prepare("SELECT * FROM participants WHERE id = ?");
    $stmt->execute([$participant_id]);
    $participant = $stmt->fetch();

    if (!$survey || !$participant) {
        http_response_code(404);
        echo "Survey or participant not found";
        return;
    }

    // Get participant's session
    $stmt = $db->prepare("
        SELECT ss.*, COUNT(r.id) as response_count
        FROM survey_sessions ss
        LEFT JOIN responses r ON ss.id = r.session_id
        WHERE ss.survey_id = ? AND ss.participant_id = ?
        GROUP BY ss.id
    ");
    $stmt->execute([$survey_id, $participant_id]);
    $session = $stmt->fetch();

    if (!$session) {
        http_response_code(404);
        echo "Session not found";
        return;
    }

    // Get all questions and responses for this survey
    $stmt = $db->prepare("
        SELECT
            q.id,
            q.code,
            q.text,
            q.type,
            qo.option_text,
            r.score,
            r.weight
        FROM survey_questions sq
        JOIN questions q ON sq.question_id = q.id
        LEFT JOIN responses r ON q.id = r.question_id AND r.session_id = ?
        LEFT JOIN question_options qo ON q.id = qo.question_id AND r.score = qo.option_value
        WHERE sq.survey_id = ?
        ORDER BY sq.order_index
    ");
    $stmt->execute([$session['id'], $survey_id]);
    $responses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    include __DIR__ . '/../templates/researcher/participant_responses.php';
}

/**
 * Update participant responses
 */
function researcher_update_participant_responses($survey_id, $participant_id) {
    require_researcher();

    if (!can_access_survey($survey_id)) {
        http_response_code(403);
        echo "Access denied";
        return;
    }

    $db = get_db_connection();

    // Get participant's session
    $stmt = $db->prepare("SELECT id FROM survey_sessions WHERE survey_id = ? AND participant_id = ?");
    $stmt->execute([$survey_id, $participant_id]);
    $session = $stmt->fetch();

    if (!$session) {
        http_response_code(404);
        echo "Session not found";
        return;
    }

    try {
        $db->beginTransaction();

        if (isset($_POST['responses']) && is_array($_POST['responses'])) {
            foreach ($_POST['responses'] as $question_id => $score) {
                $score = trim($score);
                $weight = null;

                if ($score !== '' && is_numeric($score)) {
                    // Calculate weight based on question type
                    $stmt = $db->prepare("SELECT type FROM questions WHERE id = ?");
                    $stmt->execute([$question_id]);
                    $question = $stmt->fetch();

                    if ($question['type'] === 'scale') {
                        $weights = [1 => 1.0, 2 => 1.5, 3 => 2.0, 4 => 2.5, 5 => 3.0];
                        $weight = $weights[$score] ?? null;
                    }
                    // For multiple choice, weight remains null or could be calculated differently
                } else {
                    $score = null;
                }

                // Insert or update response
                $stmt = $db->prepare("
                    INSERT INTO responses (session_id, question_id, score, weight)
                    VALUES (?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE score = VALUES(score), weight = VALUES(weight)
                ");
                $stmt->execute([$session['id'], $question_id, $score, $weight]);
            }
        }

        $db->commit();

        // Redirect back to the participant responses page with success message
        header("Location: " . BASE_PATH . "/surveys/{$survey_id}/participants/{$participant_id}?updated=1");
        exit;

    } catch (Exception $e) {
        $db->rollback();
        // Redirect back with error
        header("Location: " . BASE_PATH . "/surveys/{$survey_id}/participants/{$participant_id}?error=1");
        exit;
    }
}

/**
 * Show researcher's questions
 */
function researcher_questions() {
    require_researcher();
    $user = get_authenticated_user();
    $db = get_db_connection();

    // Get researcher's questions
    $stmt = $db->prepare("SELECT * FROM questions WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user['id']]);
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    include __DIR__ . '/../templates/researcher/questions.php';
}

/**
 * Show create question form
 */
function researcher_create_question() {
    require_researcher();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        return researcher_store_question();
    }

    include __DIR__ . '/../templates/researcher/create_question.php';
}

/**
 * Store new question
 */
function researcher_store_question() {
    require_researcher();
    $user = get_authenticated_user();
    $db = get_db_connection();

    $module = trim($_POST['module'] ?? '');
    $group = trim($_POST['group'] ?? '');
    $code = trim($_POST['code'] ?? '');
    $text = trim($_POST['text'] ?? '');
    $type = trim($_POST['type'] ?? 'scale');

    if (empty($module) || empty($code) || empty($text)) {
        $error = 'Module, code, and question text are required';
        include __DIR__ . '/../templates/researcher/create_question.php';
        return;
    }

    if ($type === 'multiple_choice' && empty(trim($_POST['options'] ?? ''))) {
        $error = 'Options are required for multiple choice questions';
        include __DIR__ . '/../templates/researcher/create_question.php';
        return;
    }

    try {
        $db->beginTransaction();

        $stmt = $db->prepare("INSERT INTO questions (user_id, module, `group`, code, text, type) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user['id'], $module, $group, $code, $text, $type]);
        $question_id = $db->lastInsertId();

        if ($type === 'multiple_choice') {
            $options = explode("\n", trim($_POST['options']));
            $order = 1;
            $insertOption = $db->prepare("INSERT INTO question_options (question_id, option_text, option_value, order_index) VALUES (?, ?, ?, ?)");
            foreach ($options as $option) {
                $option = trim($option);
                if (!empty($option)) {
                    $insertOption->execute([$question_id, $option, $order, $order]);
                    $order++;
                }
            }
        }

        $db->commit();

        header("Location: " . BASE_PATH . "/questions");
        exit;
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $error = 'A question with this code already exists';
        } else {
            $error = 'Failed to create question: ' . $e->getMessage();
        }
        include __DIR__ . '/../templates/researcher/create_question.php';
    }
}

/**
 * Show edit question form
 */
function researcher_edit_question($question_id) {
    require_researcher();
    $user = get_authenticated_user();

    if (!can_access_question($question_id, $user['id'])) {
        http_response_code(403);
        echo "Access denied";
        return;
    }

    $db = get_db_connection();
    $stmt = $db->prepare("SELECT * FROM questions WHERE id = ?");
    $stmt->execute([$question_id]);
    $question = $stmt->fetch();

    if (!$question) {
        http_response_code(404);
        echo "Question not found";
        return;
    }

    // Load options if multiple choice
    if ($question['type'] === 'multiple_choice') {
        $stmt = $db->prepare("SELECT option_text FROM question_options WHERE question_id = ? ORDER BY order_index");
        $stmt->execute([$question_id]);
        $question['options'] = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        return researcher_update_question($question_id);
    }

    include __DIR__ . '/../templates/researcher/edit_question.php';
}

/**
 * Update question
 */
function researcher_update_question($question_id) {
    require_researcher();
    $user = get_authenticated_user();

    if (!can_access_question($question_id, $user['id'])) {
        http_response_code(403);
        echo "Access denied";
        return;
    }

    $db = get_db_connection();
    $module = trim($_POST['module'] ?? '');
    $group = trim($_POST['group'] ?? '');
    $code = trim($_POST['code'] ?? '');
    $text = trim($_POST['text'] ?? '');
    $type = trim($_POST['type'] ?? 'scale');

    if (empty($module) || empty($code) || empty($text)) {
        $error = 'Module, code, and question text are required';
        include __DIR__ . '/../templates/researcher/edit_question.php';
        return;
    }

    if ($type === 'multiple_choice' && empty(trim($_POST['options'] ?? ''))) {
        $error = 'Options are required for multiple choice questions';
        include __DIR__ . '/../templates/researcher/edit_question.php';
        return;
    }

    try {
        $db->beginTransaction();

        $stmt = $db->prepare("UPDATE questions SET module = ?, `group` = ?, code = ?, text = ?, type = ? WHERE id = ?");
        $stmt->execute([$module, $group, $code, $text, $type, $question_id]);

        // Delete existing options
        $db->prepare("DELETE FROM question_options WHERE question_id = ?")->execute([$question_id]);

        // Insert new options if multiple choice
        if ($type === 'multiple_choice') {
            $options = explode("\n", trim($_POST['options']));
            $order = 1;
            $insertOption = $db->prepare("INSERT INTO question_options (question_id, option_text, option_value, order_index) VALUES (?, ?, ?, ?)");
            foreach ($options as $option) {
                $option = trim($option);
                if (!empty($option)) {
                    $insertOption->execute([$question_id, $option, $order, $order]);
                    $order++;
                }
            }
        }

        $db->commit();

        header("Location: " . BASE_PATH . "/questions");
        exit;
    } catch (PDOException $e) {
        $db->rollback();
        if ($e->getCode() == 23000) {
            $error = 'A question with this code already exists';
        } else {
            $error = 'Failed to update question: ' . $e->getMessage();
        }
        include __DIR__ . '/../templates/researcher/edit_question.php';
    }
}

/**
 * Delete question
 */
function researcher_delete_question($question_id) {
    require_researcher();
    $user = get_authenticated_user();

    if (!can_access_question($question_id, $user['id'])) {
        http_response_code(403);
        echo "Access denied";
        return;
    }

    $db = get_db_connection();

    // Check if question is used in any surveys
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM survey_questions WHERE question_id = ?");
    $stmt->execute([$question_id]);
    $usage_count = $stmt->fetch()['count'];

    if ($usage_count > 0) {
        $error = "Cannot delete question: it is currently used in {$usage_count} survey(s). Remove it from surveys first.";
        include __DIR__ . '/../templates/researcher/questions.php';
        return;
    }

    try {
        $stmt = $db->prepare("DELETE FROM questions WHERE id = ?");
        $stmt->execute([$question_id]);

        header("Location: " . BASE_PATH . "/questions");
        exit;
    } catch (Exception $e) {
        echo "Failed to delete question: " . $e->getMessage();
    }
}

/**
 * Check if user can access a question
 */
function can_access_question($question_id, $user_id) {
    $db = get_db_connection();
    $stmt = $db->prepare("SELECT user_id FROM questions WHERE id = ?");
    $stmt->execute([$question_id]);
    $question = $stmt->fetch();

    return $question && $question['user_id'] == $user_id;
}

/**
 * Handle question assignment updates for surveys
 */
function researcher_update_survey_questions($survey_id) {
    require_researcher();
    $user = get_authenticated_user();

    if (!can_access_survey($survey_id)) {
        http_response_code(403);
        echo "Access denied";
        return;
    }

    $db = get_db_connection();

    try {
        $db->beginTransaction();

        // Delete existing assignments
        $db->prepare("DELETE FROM survey_questions WHERE survey_id = ?")->execute([$survey_id]);

        // Add new assignments
        if (isset($_POST['questions']) && is_array($_POST['questions'])) {
            $questions = $_POST['questions'];
            $order = 0;
            foreach ($questions as $question_id) {
                // Verify the question belongs to the user
                if (can_access_question($question_id, $user['id'])) {
                    $stmt = $db->prepare("INSERT INTO survey_questions (survey_id, question_id, order_index) VALUES (?, ?, ?)");
                    $stmt->execute([$survey_id, $question_id, $order++]);
                }
            }
        }

        $db->commit();

        header("Location: " . BASE_PATH . "/surveys/{$survey_id}/edit");
        exit;
    } catch (Exception $e) {
        $db->rollback();
        $assignment_error = 'Failed to update question assignments: ' . $e->getMessage();
        // Re-show the edit form with error
        researcher_edit_survey($survey_id);
    }
}
?>