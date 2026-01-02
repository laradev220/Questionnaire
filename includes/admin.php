<?php
function admin_show_login()
{
    include __DIR__ . '/../templates/admin/login.php';
}

function admin_login()
{
    $db = get_db_connection();
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Debug: check DB connection
    try {
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ? AND role IN ('admin', 'super_admin')");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
    } catch (Exception $e) {
        $error = 'DB Error: ' . $e->getMessage();
        include __DIR__ . '/../templates/admin/login.php';
        return;
    }

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
        header("Location: " . BASE_PATH . "/admin/dashboard");
    } else {
        // Debug: check why login failed
        if (!$user) {
            $error = 'No admin user found with email: ' . htmlspecialchars($email);
        } else {
            $error = 'Invalid password for user: ' . htmlspecialchars($user['name']);
        }
        include __DIR__ . '/../templates/admin/login.php';
    }
}

function admin_logout()
{
    session_destroy();
    header("Location: " . BASE_PATH . "/admin/login");
}

function admin_dashboard()
{
    $db = get_db_connection();
    $user = get_authenticated_user();

    // Key metrics for dashboard
    $stmt = $db->query("SELECT COUNT(*) as total FROM responses WHERE score IS NOT NULL");
    $totalResponses = $stmt->fetch()['total'];

    $stmt = $db->query("SELECT COUNT(*) as total FROM survey_sessions");
    $totalSessions = $stmt->fetch()['total'];

    $stmt = $db->query("SELECT COUNT(*) as total FROM survey_sessions WHERE is_completed = 1");
    $completedSessions = $stmt->fetch()['total'];

    $stmt = $db->query("SELECT COUNT(*) as total FROM participants");
    $totalParticipants = $stmt->fetch()['total'];

    // Total users (for super admin)
    $totalUsers = 0;
    if (is_super_admin()) {
        $stmt = $db->query("SELECT COUNT(*) as total FROM users");
        $totalUsers = $stmt->fetch()['total'];
    }

    // Completion rate
    $completionRate = $totalSessions > 0 ? round(($completedSessions / $totalSessions) * 100, 1) : 0;

    // Average survey score
    $stmt = $db->query("SELECT AVG(score) as avg_score FROM responses WHERE score IS NOT NULL");
    $avgScore = $stmt->fetch()['avg_score'];
    $avgScore = $avgScore ? round($avgScore, 2) : 0;

    // Recent activity (last 5 completed surveys)
    $stmt = $db->query("
        SELECT ss.id, ss.created_at, p.name, p.email
        FROM survey_sessions ss
        JOIN participants p ON ss.participant_id = p.id
        WHERE ss.is_completed = 1
        ORDER BY ss.created_at DESC
        LIMIT 5
    ");
    $recentActivity = $stmt->fetchAll();

    // Participant survey status
    $stmt = $db->query("
        SELECT p.name, p.email, p.phone, p.created_at as joined_at,
               COALESCE(ss.is_completed, 0) as is_completed,
               COALESCE(ss.created_at, p.created_at) as survey_started
        FROM participants p
        LEFT JOIN survey_sessions ss ON p.id = ss.participant_id
        ORDER BY p.created_at DESC
        LIMIT 20
    ");
    $participantStatus = $stmt->fetchAll();

    include __DIR__ . '/../templates/admin/dashboard.php';
}

function admin_questions()
{
    $db = get_db_connection();
    $user = get_authenticated_user();

    if (is_super_admin()) {
        $stmt = $db->query("SELECT q.*, u.name as creator_name FROM questions q LEFT JOIN users u ON q.user_id = u.id ORDER BY q.created_at DESC, q.module, q.id");
    } else {
        $stmt = $db->prepare("SELECT q.*, u.name as creator_name FROM questions q LEFT JOIN users u ON q.user_id = u.id WHERE q.user_id = ? OR q.user_id IS NULL ORDER BY q.created_at DESC, q.module, q.id");
        $stmt->execute([$user['id']]);
    }
    $questions = $stmt->fetchAll();
    include __DIR__ . '/../templates/admin/questions.php';
}

function admin_add_question()
{
    $db = get_db_connection();
    $user = get_authenticated_user();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $module = trim($_POST['module']);
        $group = trim($_POST['group']) ?: null;
        $code = trim($_POST['code']);
        $text = trim($_POST['text']);

        if (empty($module) || empty($code) || empty($text)) {
            $error = 'Module, code and text are required';
        } else {
            try {
                $stmt = $db->prepare("INSERT INTO questions (user_id, module, `group`, code, text) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$user['id'], $module, $group, $code, $text]);
                header("Location: " . BASE_PATH . "/admin/questions");
                exit;
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    $error = 'Question code already exists';
                } else {
                    $error = 'Failed to create question';
                }
            }
        }
    }
    include __DIR__ . '/../templates/admin/add_question.php';
}

function admin_edit_question($id)
{
    $db = get_db_connection();
    $user = get_authenticated_user();

    // Check ownership unless super admin
    if (!is_super_admin()) {
        $stmt = $db->prepare("SELECT user_id FROM questions WHERE id = ?");
        $stmt->execute([$id]);
        $question_owner = $stmt->fetch();
        if (!$question_owner || $question_owner['user_id'] != $user['id']) {
            header("Location: " . BASE_PATH . "/admin/questions");
            exit;
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $module = trim($_POST['module']);
        $group = trim($_POST['group']) ?: null;
        $code = trim($_POST['code']);
        $text = trim($_POST['text']);

        if (empty($module) || empty($code) || empty($text)) {
            $error = 'Module, code and text are required';
        } else {
            try {
                $stmt = $db->prepare("UPDATE questions SET module = ?, `group` = ?, code = ?, text = ? WHERE id = ?");
                $stmt->execute([$module, $group, $code, $text, $id]);
                header("Location: " . BASE_PATH . "/admin/questions");
                exit;
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    $error = 'Question code already exists';
                } else {
                    $error = 'Failed to update question';
                }
            }
        }
    }

    $stmt = $db->prepare("SELECT * FROM questions WHERE id = ?");
    $stmt->execute([$id]);
    $question = $stmt->fetch();
    include __DIR__ . '/../templates/admin/edit_question.php';
}

function admin_delete_question($id)
{
    $db = get_db_connection();
    $user = get_authenticated_user();

    // Check ownership unless super admin
    if (!is_super_admin()) {
        $stmt = $db->prepare("SELECT user_id FROM questions WHERE id = ?");
        $stmt->execute([$id]);
        $question_owner = $stmt->fetch();
        if (!$question_owner || $question_owner['user_id'] != $user['id']) {
            header("Location: " . BASE_PATH . "/admin/questions");
            exit;
        }
    }

    $stmt = $db->prepare("DELETE FROM questions WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: " . BASE_PATH . "/admin/questions");
    exit;
}

function admin_analytics()
{
    $db = get_db_connection();

    // Date range filter
    $startDate = isset($_GET['start_date']) && !empty($_GET['start_date']) ? $_GET['start_date'] : null;
    $endDate = isset($_GET['end_date']) && !empty($_GET['end_date']) ? $_GET['end_date'] : null;
    $dateFilter = '';
    $params = [];
    if ($startDate && $endDate) {
        $dateFilter = ' AND ss.created_at BETWEEN ? AND ?';
        $params = [$startDate . ' 00:00:00', $endDate . ' 23:59:59'];
    }

    // Total responses (only non-null scores)
    $stmt = $db->prepare("SELECT COUNT(*) as total FROM responses r JOIN survey_sessions ss ON r.session_id = ss.id WHERE r.score IS NOT NULL" . $dateFilter);
    $stmt->execute($params);
    $totalResponses = $stmt->fetch()['total'];

    // Total sessions
    $stmt = $db->prepare("SELECT COUNT(*) as total FROM survey_sessions ss WHERE 1=1" . $dateFilter);
    $stmt->execute($params);
    $totalSessions = $stmt->fetch()['total'];

    // Completed sessions
    $stmt = $db->prepare("SELECT COUNT(*) as total FROM survey_sessions ss WHERE is_completed = 1" . $dateFilter);
    $stmt->execute($params);
    $completedSessions = $stmt->fetch()['total'];

    // Total participants (unique participants who started sessions in date range)
    $stmt = $db->prepare("SELECT COUNT(DISTINCT ss.participant_id) as total FROM survey_sessions ss WHERE 1=1" . $dateFilter);
    $stmt->execute($params);
    $totalParticipants = $stmt->fetch()['total'];

    // Completion rate
    $completionRate = $totalSessions > 0 ? round(($completedSessions / $totalSessions) * 100, 1) : 0;

    // Average score per module (only non-null scores)
    $stmt = $db->prepare("
        SELECT q.module, AVG(r.score) as avg_score
        FROM questions q
        LEFT JOIN responses r ON q.id = r.question_id AND r.score IS NOT NULL
        LEFT JOIN survey_sessions ss ON r.session_id = ss.id
        WHERE 1=1" . $dateFilter . "
        GROUP BY q.module
        ORDER BY q.module
    ");
    $stmt->execute($params);
    $moduleAverages = $stmt->fetchAll();

    // Calculate overall average score
    $stmt = $db->prepare("SELECT AVG(r.score) as overall_avg FROM responses r JOIN survey_sessions ss ON r.session_id = ss.id WHERE r.score IS NOT NULL" . $dateFilter);
    $stmt->execute($params);
    $overallAvg = $stmt->fetch()['overall_avg'];

    // Replace null averages with overall average
    foreach ($moduleAverages as &$avg) {
        if ($avg['avg_score'] === null) {
            $avg['avg_score'] = $overallAvg;
        }
    }

    // Response distribution (only non-null scores)
    $stmt = $db->prepare("SELECT r.score, COUNT(*) as count FROM responses r JOIN survey_sessions ss ON r.session_id = ss.id WHERE r.score IS NOT NULL" . $dateFilter . " GROUP BY r.score ORDER BY r.score");
    $stmt->execute($params);
    $scoreDistribution = $stmt->fetchAll();

    // Participant survey status
    $stmt = $db->query("
        SELECT p.name, p.email, p.phone, p.created_at as joined_at,
               COALESCE(ss.is_completed, 0) as is_completed,
               COALESCE(ss.created_at, p.created_at) as survey_started
        FROM participants p
        LEFT JOIN survey_sessions ss ON p.id = ss.participant_id
        ORDER BY p.created_at DESC
        LIMIT 20
    ");
    $participantStatus = $stmt->fetchAll();

    include __DIR__ . '/../templates/admin/analytics.php';
}

function admin_export_participants()
{
    $db = get_db_connection();

    $startDate = $_GET['start_date'] ?? null;
    $endDate = $_GET['end_date'] ?? null;
    $params = [];
    $dateFilter = '';
    if ($startDate && $endDate) {
        $dateFilter = ' AND ss.created_at BETWEEN ? AND ?';
        $params = [$startDate . ' 00:00:00', $endDate . ' 23:59:59'];
    }

    $stmt = $db->prepare("SELECT p.name, p.email, p.phone, p.university, p.designation, p.created_at as joined_at, COALESCE(ss.is_completed, 0) as is_completed FROM participants p LEFT JOIN survey_sessions ss ON p.id = ss.participant_id WHERE 1=1 $dateFilter ORDER BY p.created_at DESC");
    $stmt->execute($params);
    $participants = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // CSV output
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="participants_' . date('Y-m-d') . '.csv"');
    $output = fopen('php://output', 'w');
    fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM
    fputcsv($output, ['Name', 'Email', 'Phone', 'University', 'Designation', 'Joined Date', 'Status']);

    foreach ($participants as $p) {
        fputcsv($output, [
            $p['name'],
            $p['email'],
            $p['phone'],
            $p['university'],
            $p['designation'],
            date('m/d/Y', strtotime($p['joined_at'])),
            $p['is_completed'] ? 'Completed' : 'In Progress'
        ]);
    }
    fclose($output);
    exit;
}

function admin_export_responses()
{
    $db = get_db_connection();

    $startDate = $_GET['start_date'] ?? null;
    $endDate = $_GET['end_date'] ?? null;
    $params = [];
    $dateFilter = '';
    if ($startDate && $endDate) {
        $dateFilter = ' AND ss.created_at BETWEEN ? AND ?';
        $params = [$startDate . ' 00:00:00', $endDate . ' 23:59:59'];
    }

    $stmt = $db->prepare("SELECT p.name, p.email, q.code, q.text, q.module, q.group, r.score, r.weight, ss.created_at as response_date FROM responses r JOIN survey_sessions ss ON r.session_id = ss.id JOIN participants p ON ss.participant_id = p.id JOIN questions q ON r.question_id = q.id WHERE r.score IS NOT NULL $dateFilter ORDER BY ss.created_at, q.code");
    $stmt->execute($params);
    $responses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // CSV output
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="responses_' . date('Y-m-d') . '.csv"');
    $output = fopen('php://output', 'w');
    fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM
    fputcsv($output, ['Participant Name', 'Email', 'Question Code', 'Question Text', 'Module', 'Group', 'Score', 'Weight', 'Response Date']);

    foreach ($responses as $r) {
        fputcsv($output, [
            $r['name'],
            $r['email'],
            $r['code'],
            $r['text'],
            $r['module'],
            $r['group'],
            $r['score'],
            $r['weight'],
            date('m/d/Y H:i', strtotime($r['response_date']))
        ]);
    }
    fclose($output);
    exit;
}

// ===== NEW CRUD FUNCTIONS FOR ENHANCED SUPER ADMIN PANEL =====

// ===== USER MANAGEMENT (SUPER ADMIN ONLY) =====

function admin_users()
{
    require_super_admin();
    $db = get_db_connection();

    $stmt = $db->query("SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC");
    $users = $stmt->fetchAll();

    include __DIR__ . '/../templates/admin/users.php';
}

function admin_add_user()
{
    require_super_admin();
    $db = get_db_connection();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // CSRF protection
        if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
            $error = 'Invalid CSRF token';
        } else {
            $name = sanitize_input($_POST['name']);
            $email = sanitize_input($_POST['email']);
            $password = $_POST['password'];
            $role = sanitize_input($_POST['role']);

            // Validation
            if (empty($name) || empty($email) || empty($password) || empty($role)) {
                $error = 'All fields are required';
            } elseif (strlen($password) < 6) {
                $error = 'Password must be at least 6 characters';
            } elseif (!in_array($role, ['researcher', 'admin', 'super_admin'])) {
                $error = 'Invalid role';
            } else {
                try {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $db->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$name, $email, $hashed_password, $role]);
                    header("Location: " . BASE_PATH . "/admin/users");
                    exit;
                } catch (PDOException $e) {
                    if ($e->getCode() == 23000) {
                        $error = 'Email already exists';
                    } else {
                        $error = 'Failed to create user';
                    }
                }
            }
        }
    }

    include __DIR__ . '/../templates/admin/add_user.php';
}

function admin_edit_user($id)
{
    require_super_admin();
    $db = get_db_connection();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $role = $_POST['role'];
        $password = !empty($_POST['password']) ? $_POST['password'] : null;

        // Validation
        if (empty($name) || empty($email) || empty($role)) {
            $error = 'Name, email and role are required';
        } elseif (!in_array($role, ['researcher', 'admin', 'super_admin'])) {
            $error = 'Invalid role';
        } elseif ($password && strlen($password) < 6) {
            $error = 'Password must be at least 6 characters';
        } else {
            try {
                if ($password) {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $db->prepare("UPDATE users SET name = ?, email = ?, role = ?, password = ? WHERE id = ?");
                    $stmt->execute([$name, $email, $role, $hashed_password, $id]);
                } else {
                    $stmt = $db->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
                    $stmt->execute([$name, $email, $role, $id]);
                }
                header("Location: " . BASE_PATH . "/admin/users");
                exit;
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    $error = 'Email already exists';
                } else {
                    $error = 'Failed to update user';
                }
            }
        }
    }

    $stmt = $db->prepare("SELECT id, name, email, role FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch();

    if (!$user) {
        header("Location: " . BASE_PATH . "/admin/users");
        exit;
    }

    include __DIR__ . '/../templates/admin/edit_user.php';
}

function admin_delete_user($id)
{
    require_super_admin();
    $db = get_db_connection();

    // Prevent deleting self
    if ($id == $_SESSION['user_id']) {
        $error = 'Cannot delete your own account';
        header("Location: " . BASE_PATH . "/admin/users?error=" . urlencode($error));
        exit;
    }

    // Check if user has surveys
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM surveys WHERE user_id = ?");
    $stmt->execute([$id]);
    if ($stmt->fetch()['count'] > 0) {
        $error = 'Cannot delete user with existing surveys';
        header("Location: " . BASE_PATH . "/admin/users?error=" . urlencode($error));
        exit;
    }

    $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: " . BASE_PATH . "/admin/users");
    exit;
}

// ===== SURVEY MANAGEMENT =====

function admin_surveys()
{
    require_admin();
    $db = get_db_connection();

    $user = get_authenticated_user();
    if (is_super_admin()) {
        $stmt = $db->query("SELECT s.*, u.name as creator_name FROM surveys s JOIN users u ON s.user_id = u.id ORDER BY s.created_at DESC");
    } else {
        $stmt = $db->prepare("SELECT s.*, u.name as creator_name FROM surveys s JOIN users u ON s.user_id = u.id WHERE s.user_id = ? ORDER BY s.created_at DESC");
        $stmt->execute([$user['id']]);
    }
    $surveys = $stmt->fetchAll();

    include __DIR__ . '/../templates/admin/surveys.php';
}

function admin_add_survey()
{
    require_admin();
    $db = get_db_connection();
    $user = get_authenticated_user();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $deadline = !empty($_POST['deadline']) ? $_POST['deadline'] : null;

        if (empty($title)) {
            $error = 'Survey title is required';
        } else {
            try {
                $link_token = bin2hex(random_bytes(16));
                $stmt = $db->prepare("INSERT INTO surveys (user_id, title, description, deadline, link_token, is_active) VALUES (?, ?, ?, ?, ?, 1)");
                $stmt->execute([$user['id'], $title, $description, $deadline, $link_token]);
                $survey_id = $db->lastInsertId();

                // Optionally assign default questions
                if (isset($_POST['assign_default_questions'])) {
                    // Assign all questions to this survey
                    $db->exec("INSERT INTO survey_questions (survey_id, question_id, order_index) SELECT $survey_id, id, id FROM questions");
                }

                header("Location: " . BASE_PATH . "/admin/surveys");
                exit;
            } catch (PDOException $e) {
                $error = 'Failed to create survey';
            }
        }
    }

    include __DIR__ . '/../templates/admin/add_survey.php';
}

function admin_edit_survey($id)
{
    require_admin();
    $db = get_db_connection();

    if (!can_access_survey($id)) {
        header("Location: " . BASE_PATH . "/admin/surveys");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $deadline = !empty($_POST['deadline']) ? $_POST['deadline'] : null;
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        if (empty($title)) {
            $error = 'Survey title is required';
        } else {
            try {
                $stmt = $db->prepare("UPDATE surveys SET title = ?, description = ?, deadline = ?, is_active = ? WHERE id = ?");
                $stmt->execute([$title, $description, $deadline, $is_active, $id]);
                header("Location: " . BASE_PATH . "/admin/surveys");
                exit;
            } catch (PDOException $e) {
                $error = 'Failed to update survey';
            }
        }
    }

    $stmt = $db->prepare("SELECT * FROM surveys WHERE id = ?");
    $stmt->execute([$id]);
    $survey = $stmt->fetch();

    include __DIR__ . '/../templates/admin/edit_survey.php';
}

function admin_delete_survey($id)
{
    require_admin();
    $db = get_db_connection();

    if (!can_access_survey($id)) {
        header("Location: " . BASE_PATH . "/admin/surveys");
        exit;
    }

    // Check if survey has responses
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM survey_sessions WHERE survey_id = ? AND is_completed = 1");
    $stmt->execute([$id]);
    if ($stmt->fetch()['count'] > 0) {
        $error = 'Cannot delete survey with completed responses';
        header("Location: " . BASE_PATH . "/admin/surveys?error=" . urlencode($error));
        exit;
    }

    // Delete survey (cascade will handle related records)
    $stmt = $db->prepare("DELETE FROM surveys WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: " . BASE_PATH . "/admin/surveys");
    exit;
}



// ===== PARTICIPANT MANAGEMENT =====

function admin_participants()
{
    require_admin();
    $db = get_db_connection();

    $stmt = $db->query("
        SELECT p.*, s.title as survey_title, ss.is_completed, ss.created_at as survey_started
        FROM participants p
        LEFT JOIN survey_sessions ss ON p.id = ss.participant_id
        LEFT JOIN surveys s ON p.survey_id = s.id
        ORDER BY p.created_at DESC
    ");
    $participants = $stmt->fetchAll();

    include __DIR__ . '/../templates/admin/participants.php';
}

function admin_view_participant_responses($participant_id)
{
    require_admin();
    $db = get_db_connection();

    // Get participant info
    $stmt = $db->prepare("SELECT p.*, s.title as survey_title FROM participants p LEFT JOIN surveys s ON p.survey_id = s.id WHERE p.id = ?");
    $stmt->execute([$participant_id]);
    $participant = $stmt->fetch();

    if (!$participant) {
        header("Location: " . BASE_PATH . "/admin/participants");
        exit;
    }

    // Get responses
    $stmt = $db->prepare("
            SELECT r.id as id, q.code, q.text, q.module, r.score, r.weight, ss.created_at as response_date
            FROM responses r
            JOIN survey_sessions ss ON r.session_id = ss.id
            JOIN questions q ON r.question_id = q.id
            WHERE ss.participant_id = ?
            ORDER BY q.module, q.id
        ");
    $stmt->execute([$participant_id]);
    $responses = $stmt->fetchAll();

    include __DIR__ . '/../templates/admin/participant_responses.php';
}

function admin_delete_participant($participant_id)
{
    require_admin();
    $db = get_db_connection();

    // Delete in correct order due to foreign keys
    $stmt = $db->prepare("DELETE FROM responses WHERE session_id IN (SELECT id FROM survey_sessions WHERE participant_id = ?)");
    $stmt->execute([$participant_id]);

    $stmt = $db->prepare("DELETE FROM survey_sessions WHERE participant_id = ?");
    $stmt->execute([$participant_id]);

    $stmt = $db->prepare("DELETE FROM participants WHERE id = ?");
    $stmt->execute([$participant_id]);

    header("Location: " . BASE_PATH . "/admin/participants");
    exit;
}

// ===== RESPONSE MANAGEMENT =====

function admin_responses()
{
    require_admin();
    $db = get_db_connection();

    $stmt = $db->query("
        SELECT r.id, p.name as participant_name, p.email, q.code, q.text, q.module, r.score, r.weight, ss.created_at as response_date
        FROM responses r
        JOIN survey_sessions ss ON r.session_id = ss.id
        JOIN participants p ON ss.participant_id = p.id
        JOIN questions q ON r.question_id = q.id
        ORDER BY ss.created_at DESC, q.id
        LIMIT 100
    ");
    $responses = $stmt->fetchAll();

    include __DIR__ . '/../templates/admin/responses.php';
}

function admin_edit_response($response_id)
{
    require_admin();
    $db = get_db_connection();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $score = $_POST['score'] !== '' ? (int)$_POST['score'] : null;
        $weight = $_POST['weight'] !== '' ? (float)$_POST['weight'] : null;

        if ($score !== null && ($score < 1 || $score > 5)) {
            $error = 'Score must be between 1 and 5';
        } else {
            $stmt = $db->prepare("UPDATE responses SET score = ?, weight = ? WHERE id = ?");
            $stmt->execute([$score, $weight, $response_id]);
            header("Location: " . BASE_PATH . "/admin/responses");
            exit;
        }
    }

    $stmt = $db->prepare("
        SELECT r.*, p.name as participant_name, q.code, q.text, q.module
        FROM responses r
        JOIN survey_sessions ss ON r.session_id = ss.id
        JOIN participants p ON ss.participant_id = p.id
        JOIN questions q ON r.question_id = q.id
        WHERE r.id = ?
    ");
    $stmt->execute([$response_id]);
    $response = $stmt->fetch();
    $response = $stmt->fetch();

    include __DIR__ . '/../templates/admin/edit_response.php';
}

function admin_delete_response($response_id)
{
    require_admin();
    $db = get_db_connection();

    $stmt = $db->prepare("DELETE FROM responses WHERE id = ?");
    $stmt->execute([$response_id]);
    header("Location: " . BASE_PATH . "/admin/responses");
    exit;
}

// ===== BULK OPERATIONS =====

function admin_bulk_users()
{
    require_super_admin();
    $db = get_db_connection();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'];

        if ($action === 'export') {
            // Export users to CSV
            $stmt = $db->query("SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC");
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="users_' . date('Y-m-d') . '.csv"');
            $output = fopen('php://output', 'w');
            fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM
            fputcsv($output, ['ID', 'Name', 'Email', 'Role', 'Created At']);

            foreach ($users as $user) {
                fputcsv($output, [
                    $user['id'],
                    $user['name'],
                    $user['email'],
                    $user['role'],
                    $user['created_at']
                ]);
            }
            fclose($output);
            exit;
        } elseif ($action === 'import' && isset($_FILES['csv_file'])) {
            // Import users from CSV
            $file = $_FILES['csv_file']['tmp_name'];
            $handle = fopen($file, 'r');
            $header = fgetcsv($handle); // Skip header

            $success = 0;
            $errors = [];

            while (($data = fgetcsv($handle)) !== FALSE) {
                try {
                    $name = trim($data[1]);
                    $email = trim($data[2]);
                    $role = trim($data[3]);

                    if (empty($name) || empty($email) || empty($role)) {
                        $errors[] = "Skipping row with missing data: " . implode(',', $data);
                        continue;
                    }

                    // Generate random password
                    $password = bin2hex(random_bytes(8));
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    $stmt = $db->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$name, $email, $hashed_password, $role]);

                    $success++;
                } catch (Exception $e) {
                    $errors[] = "Error importing row: " . implode(',', $data) . " - " . $e->getMessage();
                }
            }
            fclose($handle);

            $message = "Import completed. $success users imported.";
            if (!empty($errors)) {
                $message .= " Errors: " . implode('; ', $errors);
            }
        } elseif ($action === 'bulk_update' && isset($_POST['user_ids'])) {
            $user_ids = $_POST['user_ids'];
            $new_role = $_POST['new_role'] ?? null;
            $reset_password = isset($_POST['reset_password']);

            $updated = 0;
            foreach ($user_ids as $user_id) {
                if ($new_role) {
                    $stmt = $db->prepare("UPDATE users SET role = ? WHERE id = ?");
                    $stmt->execute([$new_role, $user_id]);
                    $updated++;
                }

                if ($reset_password) {
                    $new_password = bin2hex(random_bytes(8));
                    $hashed = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
                    $stmt->execute([$hashed, $user_id]);
                    $updated++;
                }
            }

            $message = "Updated $updated user records.";
        } elseif ($action === 'bulk_delete' && isset($_POST['user_ids'])) {
            $user_ids = $_POST['user_ids'];

            // Prevent deleting self
            $user_ids = array_filter($user_ids, function ($id) {
                return $id != $_SESSION['user_id'];
            });

            if (!empty($user_ids)) {
                $placeholders = str_repeat('?,', count($user_ids) - 1) . '?';
                $stmt = $db->prepare("DELETE FROM users WHERE id IN ($placeholders)");
                $stmt->execute($user_ids);
                $message = "Deleted " . count($user_ids) . " users.";
            } else {
                $message = "Cannot delete your own account.";
            }
        }
    }

    // Get all users for display
    $stmt = $db->query("SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC");
    $users = $stmt->fetchAll();

    include __DIR__ . '/../templates/admin/bulk_users.php';
}

function admin_bulk_surveys()
{
    require_admin();
    $db = get_db_connection();
    $user = get_authenticated_user();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'];

        if ($action === 'bulk_assign_questions' && isset($_POST['survey_ids'])) {
            $survey_ids = $_POST['survey_ids'];
            $question_ids = $_POST['question_ids'] ?? [];

            $assigned = 0;
            foreach ($survey_ids as $survey_id) {
                if (!can_access_survey($survey_id)) continue;

                foreach ($question_ids as $question_id) {
                    try {
                        $stmt = $db->prepare("INSERT IGNORE INTO survey_questions (survey_id, question_id, order_index) VALUES (?, ?, ?)");
                        $stmt->execute([$survey_id, $question_id, $question_id]);
                        $assigned++;
                    } catch (Exception $e) {
                        // Ignore duplicates
                    }
                }
            }
            $message = "Assigned $assigned question-survey relationships.";
        } elseif ($action === 'bulk_status_update' && isset($_POST['survey_ids'])) {
            $survey_ids = $_POST['survey_ids'];
            $new_status = isset($_POST['is_active']) ? 1 : 0;

            $updated = 0;
            foreach ($survey_ids as $survey_id) {
                if (!can_access_survey($survey_id)) continue;

                $stmt = $db->prepare("UPDATE surveys SET is_active = ? WHERE id = ?");
                $stmt->execute([$new_status, $survey_id]);
                $updated++;
            }
            $message = "Updated $updated surveys.";
        } elseif ($action === 'bulk_delete' && isset($_POST['survey_ids'])) {
            $survey_ids = $_POST['survey_ids'];

            $deleted = 0;
            foreach ($survey_ids as $survey_id) {
                if (!can_access_survey($survey_id)) continue;

                // Check if survey has responses
                $stmt = $db->prepare("SELECT COUNT(*) as count FROM survey_sessions WHERE survey_id = ? AND is_completed = 1");
                $stmt->execute([$survey_id]);
                if ($stmt->fetch()['count'] > 0) continue; // Skip surveys with responses

                $stmt = $db->prepare("DELETE FROM surveys WHERE id = ?");
                $stmt->execute([$survey_id]);
                $deleted++;
            }
            $message = "Deleted $deleted surveys (surveys with responses were skipped).";
        } elseif ($action === 'export') {
            // Export surveys to CSV
            $query = "SELECT s.*, u.name as creator_name FROM surveys s JOIN users u ON s.user_id = u.id";
            if (!is_super_admin()) {
                $query .= " WHERE s.user_id = ?";
                $stmt = $db->prepare($query);
                $stmt->execute([$user['id']]);
            } else {
                $stmt = $db->query($query);
            }
            $surveys = $stmt->fetchAll(PDO::FETCH_ASSOC);

            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="surveys_' . date('Y-m-d') . '.csv"');
            $output = fopen('php://output', 'w');
            fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM
            fputcsv($output, ['ID', 'Title', 'Description', 'Creator', 'Active', 'Created At']);

            foreach ($surveys as $survey) {
                fputcsv($output, [
                    $survey['id'],
                    $survey['title'],
                    $survey['description'],
                    $survey['creator_name'],
                    $survey['is_active'] ? 'Yes' : 'No',
                    $survey['created_at']
                ]);
            }
            fclose($output);
            exit;
        }
    }

    // Get surveys
    if (is_super_admin()) {
        $stmt = $db->query("SELECT s.*, u.name as creator_name FROM surveys s JOIN users u ON s.user_id = u.id ORDER BY s.created_at DESC");
    } else {
        $stmt = $db->prepare("SELECT s.*, u.name as creator_name FROM surveys s JOIN users u ON s.user_id = u.id WHERE s.user_id = ? ORDER BY s.created_at DESC");
        $stmt->execute([$user['id']]);
    }
    $surveys = $stmt->fetchAll();

    // Get all questions for assignment
    $stmt = $db->query("SELECT id, code, text FROM questions ORDER BY module, code");
    $questions = $stmt->fetchAll();

    include __DIR__ . '/../templates/admin/bulk_surveys.php';
}

function admin_bulk_questions()
{
    require_admin();
    $db = get_db_connection();
    $user = get_authenticated_user();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'];

        if ($action === 'import' && isset($_FILES['csv_file'])) {
            // Import questions from CSV
            $file = $_FILES['csv_file']['tmp_name'];
            $handle = fopen($file, 'r');
            $header = fgetcsv($handle); // Skip header

            $success = 0;
            $errors = [];

            while (($data = fgetcsv($handle)) !== FALSE) {
                try {
                    $module = trim($data[0]);
                    $group = trim($data[1]) ?: null;
                    $code = trim($data[2]);
                    $text = trim($data[3]);

                    if (empty($module) || empty($code) || empty($text)) {
                        $errors[] = "Skipping row with missing data: " . implode(',', $data);
                        continue;
                    }

                    $stmt = $db->prepare("INSERT INTO questions (user_id, module, `group`, code, text) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$user['id'], $module, $group, $code, $text]);

                    $success++;
                } catch (Exception $e) {
                    $errors[] = "Error importing row: " . implode(',', $data) . " - " . $e->getMessage();
                }
            }
            fclose($handle);

            $message = "Import completed. $success questions imported.";
            if (!empty($errors)) {
                $message .= " Errors: " . implode('; ', $errors);
            }
        } elseif ($action === 'export') {
            // Export questions to CSV
            $query = "SELECT q.*, u.name as creator_name FROM questions q LEFT JOIN users u ON q.user_id = u.id";
            if (!is_super_admin()) {
                $query .= " WHERE q.user_id = ? OR q.user_id IS NULL";
                $stmt = $db->prepare($query);
                $stmt->execute([$user['id']]);
            } else {
                $stmt = $db->query($query);
            }
            $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="questions_' . date('Y-m-d') . '.csv"');
            $output = fopen('php://output', 'w');
            fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM
            fputcsv($output, ['Module', 'Group', 'Code', 'Text', 'Creator']);

            foreach ($questions as $question) {
                fputcsv($output, [
                    $question['module'],
                    $question['group'],
                    $question['code'],
                    $question['text'],
                    $question['creator_name'] ?: 'System'
                ]);
            }
            fclose($output);
            exit;
        } elseif ($action === 'bulk_update' && isset($_POST['question_ids'])) {
            $question_ids = $_POST['question_ids'];
            $new_module = $_POST['new_module'] ?? null;
            $new_group = $_POST['new_group'] ?? null;

            $updated = 0;
            foreach ($question_ids as $question_id) {
                // Check ownership
                if (!is_super_admin()) {
                    $stmt = $db->prepare("SELECT user_id FROM questions WHERE id = ?");
                    $stmt->execute([$question_id]);
                    $q = $stmt->fetch();
                    if (!$q || $q['user_id'] != $user['id']) continue;
                }

                $updates = [];
                $params = [];

                if ($new_module) {
                    $updates[] = "module = ?";
                    $params[] = $new_module;
                }

                if ($new_group !== null) {
                    $updates[] = "`group` = ?";
                    $params[] = $new_group;
                }

                if (!empty($updates)) {
                    $params[] = $question_id;
                    $stmt = $db->prepare("UPDATE questions SET " . implode(', ', $updates) . " WHERE id = ?");
                    $stmt->execute($params);
                    $updated++;
                }
            }

            $message = "Updated $updated questions.";
        } elseif ($action === 'bulk_delete' && isset($_POST['question_ids'])) {
            $question_ids = $_POST['question_ids'];

            $deleted = 0;
            foreach ($question_ids as $question_id) {
                // Check ownership
                if (!is_super_admin()) {
                    $stmt = $db->prepare("SELECT user_id FROM questions WHERE id = ?");
                    $stmt->execute([$question_id]);
                    $q = $stmt->fetch();
                    if (!$q || $q['user_id'] != $user['id']) continue;
                }

                // Check if used in surveys
                $stmt = $db->prepare("SELECT COUNT(*) as count FROM survey_questions WHERE question_id = ?");
                $stmt->execute([$question_id]);
                if ($stmt->fetch()['count'] > 0) continue; // Skip questions used in surveys

                $stmt = $db->prepare("DELETE FROM questions WHERE id = ?");
                $stmt->execute([$question_id]);
                $deleted++;
            }

            $message = "Deleted $deleted questions (questions used in surveys were skipped).";
        }
    }

    // Get questions
    if (is_super_admin()) {
        $stmt = $db->query("SELECT q.*, u.name as creator_name FROM questions q LEFT JOIN users u ON q.user_id = u.id ORDER BY q.created_at DESC, q.module, q.code");
    } else {
        $stmt = $db->prepare("SELECT q.*, u.name as creator_name FROM questions q LEFT JOIN users u ON q.user_id = u.id WHERE q.user_id = ? OR q.user_id IS NULL ORDER BY q.created_at DESC, q.module, q.code");
        $stmt->execute([$user['id']]);
    }
    $questions = $stmt->fetchAll();

    include __DIR__ . '/../templates/admin/bulk_questions.php';
}

function admin_bulk_participants()
{
    require_admin();
    $db = get_db_connection();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'];

        if ($action === 'export') {
            // Export participants to CSV
            $stmt = $db->query("SELECT p.*, s.title as survey_title FROM participants p LEFT JOIN surveys s ON p.survey_id = s.id ORDER BY p.created_at DESC");
            $participants = $stmt->fetchAll(PDO::FETCH_ASSOC);

            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="participants_' . date('Y-m-d') . '.csv"');
            $output = fopen('php://output', 'w');
            fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM
            fputcsv($output, ['ID', 'Name', 'Email', 'Phone', 'University', 'Designation', 'Survey', 'Joined At']);

            foreach ($participants as $p) {
                fputcsv($output, [
                    $p['id'],
                    $p['name'],
                    $p['email'],
                    $p['phone'],
                    $p['university'],
                    $p['designation'],
                    $p['survey_title'] ?: 'N/A',
                    $p['created_at']
                ]);
            }
            fclose($output);
            exit;
        } elseif ($action === 'import' && isset($_FILES['csv_file'])) {
            // Import participants from CSV
            $file = $_FILES['csv_file']['tmp_name'];
            $handle = fopen($file, 'r');
            $header = fgetcsv($handle); // Skip header

            $success = 0;
            $errors = [];

            while (($data = fgetcsv($handle)) !== FALSE) {
                try {
                    $name = trim($data[0]);
                    $email = trim($data[1]);
                    $phone = trim($data[2]) ?: null;
                    $university = trim($data[3]);
                    $designation = trim($data[4]);

                    if (empty($name) || empty($email) || empty($university) || empty($designation)) {
                        $errors[] = "Skipping row with missing data: " . implode(',', $data);
                        continue;
                    }

                    $stmt = $db->prepare("INSERT INTO participants (name, email, phone, university, designation) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$name, $email, $phone, $university, $designation]);

                    $success++;
                } catch (Exception $e) {
                    $errors[] = "Error importing row: " . implode(',', $data) . " - " . $e->getMessage();
                }
            }
            fclose($handle);

            $message = "Import completed. $success participants imported.";
            if (!empty($errors)) {
                $message .= " Errors: " . implode('; ', $errors);
            }
        } elseif ($action === 'bulk_delete' && isset($_POST['participant_ids'])) {
            $participant_ids = $_POST['participant_ids'];

            $deleted = 0;
            foreach ($participant_ids as $participant_id) {
                // Delete in correct order due to foreign keys
                $stmt = $db->prepare("DELETE FROM responses WHERE session_id IN (SELECT id FROM survey_sessions WHERE participant_id = ?)");
                $stmt->execute([$participant_id]);

                $stmt = $db->prepare("DELETE FROM survey_sessions WHERE participant_id = ?");
                $stmt->execute([$participant_id]);

                $stmt = $db->prepare("DELETE FROM participants WHERE id = ?");
                $stmt->execute([$participant_id]);

                $deleted++;
            }

            $message = "Deleted $deleted participants and their associated data.";
        }
    }

    // Get participants for display
    $stmt = $db->query("SELECT p.*, s.title as survey_title FROM participants p LEFT JOIN surveys s ON p.survey_id = s.id ORDER BY p.created_at DESC");
    $participants = $stmt->fetchAll();

    include __DIR__ . '/../templates/admin/bulk_participants.php';
}
