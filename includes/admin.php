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

    // Total responses (only non-null scores)
    $stmt = $db->query("SELECT COUNT(*) as total FROM responses WHERE score IS NOT NULL");
    $totalResponses = $stmt->fetch()['total'];

    // Total sessions
    $stmt = $db->query("SELECT COUNT(*) as total FROM survey_sessions");
    $totalSessions = $stmt->fetch()['total'];

    // Completed sessions
    $stmt = $db->query("SELECT COUNT(*) as total FROM survey_sessions WHERE is_completed = 1");
    $completedSessions = $stmt->fetch()['total'];

    // Total participants
    $stmt = $db->query("SELECT COUNT(*) as total FROM participants");
    $totalParticipants = $stmt->fetch()['total'];

    // Average score per module (only non-null scores)
    $stmt = $db->query("
        SELECT q.module, AVG(r.score) as avg_score, AVG(r.weight) as avg_weight
        FROM questions q
        LEFT JOIN responses r ON q.code = r.question_id AND r.score IS NOT NULL
        GROUP BY q.module
        ORDER BY q.module
    ");
    $moduleAverages = $stmt->fetchAll();

    // Calculate overall average score
    $stmt = $db->query("SELECT AVG(score) as overall_avg FROM responses WHERE score IS NOT NULL");
    $overallAvg = $stmt->fetch()['overall_avg'];

    // Replace null averages with overall average
    foreach ($moduleAverages as &$avg) {
        if ($avg['avg_score'] === null) {
            $avg['avg_score'] = $overallAvg;
            $avg['avg_weight'] = $overallAvg ? ($overallAvg - 1) * 0.5 + 1.0 : null; // approximate weight
        }
    }

    // Response distribution (only non-null scores)
    $stmt = $db->query("SELECT score, COUNT(*) as count FROM responses WHERE score IS NOT NULL GROUP BY score ORDER BY score");
    $scoreDistribution = $stmt->fetchAll();

    // Module names for charts
    $moduleNames = [];
    $avgScores = [];
    foreach ($moduleAverages as $avg) {
        $moduleNames[] = $avg['module'];
        $avgScores[] = $avg['avg_score'] !== null ? round($avg['avg_score'], 2) : 0;
    }

    // Score distribution for chart
    $scores = [];
    $counts = [];
    foreach ($scoreDistribution as $dist) {
        $scores[] = $dist['score'];
        $counts[] = $dist['count'];
    }

    include __DIR__ . '/../templates/admin/analytics.php';
}
?>