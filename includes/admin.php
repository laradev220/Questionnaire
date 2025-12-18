<?php
function admin_show_login() {
    include __DIR__ . '/../templates/admin/login.php';
}

function admin_login() {
    $db = get_db_connection();
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $db->prepare("SELECT * FROM users WHERE email = ? AND is_admin = 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_name'] = $user['name'];
        header("Location: " . BASE_PATH . "/admin/dashboard");
    } else {
        $error = 'Invalid admin credentials';
        include __DIR__ . '/../templates/admin/login.php';
    }
}

function admin_logout() {
    session_destroy();
    header("Location: " . BASE_PATH . "/admin/login");
}

function admin_dashboard() {
    $db = get_db_connection();

    // Key metrics for dashboard
    $stmt = $db->query("SELECT COUNT(*) as total FROM responses WHERE score IS NOT NULL");
    $totalResponses = $stmt->fetch()['total'];

    $stmt = $db->query("SELECT COUNT(*) as total FROM survey_sessions");
    $totalSessions = $stmt->fetch()['total'];

    $stmt = $db->query("SELECT COUNT(*) as total FROM survey_sessions WHERE is_completed = 1");
    $completedSessions = $stmt->fetch()['total'];

    $stmt = $db->query("SELECT COUNT(*) as total FROM participants");
    $totalParticipants = $stmt->fetch()['total'];

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
        SELECT p.name, p.email, p.created_at as joined_at,
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

function admin_questions() {
    $db = get_db_connection();
    $stmt = $db->query("SELECT * FROM questions ORDER BY module, id");
    $questions = $stmt->fetchAll();
    include __DIR__ . '/../templates/admin/questions.php';
}

function admin_add_question() {
    $db = get_db_connection();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $module = $_POST['module'];
        $group = $_POST['group'] ?: null;
        $code = $_POST['code'];
        $text = $_POST['text'];

        $stmt = $db->prepare("INSERT INTO questions (module, `group`, code, text) VALUES (?, ?, ?, ?)");
        $stmt->execute([$module, $group, $code, $text]);
        header("Location: " . BASE_PATH . "/admin/questions");
        exit;
    }
    include __DIR__ . '/../templates/admin/add_question.php';
}

function admin_edit_question($id) {
    $db = get_db_connection();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $module = $_POST['module'];
        $group = $_POST['group'] ?: null;
        $code = $_POST['code'];
        $text = $_POST['text'];

        $stmt = $db->prepare("UPDATE questions SET module = ?, `group` = ?, code = ?, text = ? WHERE id = ?");
        $stmt->execute([$module, $group, $code, $text, $id]);
        header("Location: " . BASE_PATH . "/admin/questions");
        exit;
    }

    $stmt = $db->prepare("SELECT * FROM questions WHERE id = ?");
    $stmt->execute([$id]);
    $question = $stmt->fetch();
    include __DIR__ . '/../templates/admin/edit_question.php';
}

function admin_delete_question($id) {
    $db = get_db_connection();
    $stmt = $db->prepare("DELETE FROM questions WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: " . BASE_PATH . "/admin/questions");
    exit;
}

function admin_analytics() {
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
        LEFT JOIN responses r ON q.code = r.question_id AND r.score IS NOT NULL
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
        SELECT p.name, p.email, p.created_at as joined_at,
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

function admin_export_participants() {
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
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
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

function admin_export_responses() {
    $db = get_db_connection();

    $startDate = $_GET['start_date'] ?? null;
    $endDate = $_GET['end_date'] ?? null;
    $params = [];
    $dateFilter = '';
    if ($startDate && $endDate) {
        $dateFilter = ' AND ss.created_at BETWEEN ? AND ?';
        $params = [$startDate . ' 00:00:00', $endDate . ' 23:59:59'];
    }

    $stmt = $db->prepare("SELECT p.name, p.email, q.code, q.text, q.module, q.group, r.score, r.weight, ss.created_at as response_date FROM responses r JOIN survey_sessions ss ON r.session_id = ss.id JOIN participants p ON ss.participant_id = p.id JOIN questions q ON r.question_id = q.code WHERE r.score IS NOT NULL $dateFilter ORDER BY ss.created_at, q.code");
    $stmt->execute($params);
    $responses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // CSV output
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="responses_' . date('Y-m-d') . '.csv"');
    $output = fopen('php://output', 'w');
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
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
?>