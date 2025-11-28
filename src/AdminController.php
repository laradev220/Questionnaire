<?php

namespace App;

use PDO;

class AdminController
{
    private $db;
    private $blade;

    public function __construct($blade)
    {
        $this->db = DB::getInstance()->getConnection();
        $this->blade = $blade;
    }

    public function showLogin()
    {
        echo $this->blade->run("admin.login");
    }

    public function login()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ? AND is_admin = 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_name'] = $user['name'];
            header("Location: /admin/dashboard");
        } else {
            echo $this->blade->run("admin.login", ['error' => 'Invalid admin credentials']);
        }
    }

    public function logout()
    {
        session_destroy();
        header("Location: /admin/login");
    }

    public function dashboard()
    {
        // Show admin dashboard with links to questions, analytics, etc.
        echo $this->blade->run("admin.dashboard");
    }

    public function questions()
    {
        $stmt = $this->db->query("SELECT * FROM questions ORDER BY module, id");
        $questions = $stmt->fetchAll();
        echo $this->blade->run("admin.questions", ['questions' => $questions]);
    }

    public function addQuestion()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $module = $_POST['module'];
            $group = $_POST['group'] ?: null;
            $code = $_POST['code'];
            $text = $_POST['text'];

            $stmt = $this->db->prepare("INSERT INTO questions (module, `group`, code, text) VALUES (?, ?, ?, ?)");
            $stmt->execute([$module, $group, $code, $text]);
            header("Location: /admin/questions");
            exit;
        }
        echo $this->blade->run("admin.add_question");
    }

    public function editQuestion($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $module = $_POST['module'];
            $group = $_POST['group'] ?: null;
            $code = $_POST['code'];
            $text = $_POST['text'];

            $stmt = $this->db->prepare("UPDATE questions SET module = ?, `group` = ?, code = ?, text = ? WHERE id = ?");
            $stmt->execute([$module, $group, $code, $text, $id]);
            header("Location: /admin/questions");
            exit;
        }

        $stmt = $this->db->prepare("SELECT * FROM questions WHERE id = ?");
        $stmt->execute([$id]);
        $question = $stmt->fetch();
        echo $this->blade->run("admin.edit_question", ['question' => $question]);
    }

    public function deleteQuestion($id)
    {
        $stmt = $this->db->prepare("DELETE FROM questions WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: /admin/questions");
        exit;
    }

    public function analytics()
    {
        // Total responses (only non-null scores)
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM responses WHERE score IS NOT NULL");
        $totalResponses = $stmt->fetch()['total'];

        // Total sessions
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM survey_sessions");
        $totalSessions = $stmt->fetch()['total'];

        // Completed sessions
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM survey_sessions WHERE is_completed = 1");
        $completedSessions = $stmt->fetch()['total'];

        // Total participants
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM participants");
        $totalParticipants = $stmt->fetch()['total'];

        // Average score per module (only non-null scores)
        $stmt = $this->db->query("
            SELECT q.module, AVG(r.score) as avg_score, AVG(r.weight) as avg_weight
            FROM questions q
            LEFT JOIN responses r ON q.code = r.question_id AND r.score IS NOT NULL
            GROUP BY q.module
            ORDER BY q.module
        ");
        $moduleAverages = $stmt->fetchAll();

        // Calculate overall average score
        $stmt = $this->db->query("SELECT AVG(score) as overall_avg FROM responses WHERE score IS NOT NULL");
        $overallAvg = $stmt->fetch()['overall_avg'];

        // Replace null averages with overall average
        foreach ($moduleAverages as &$avg) {
            if ($avg['avg_score'] === null) {
                $avg['avg_score'] = $overallAvg;
                $avg['avg_weight'] = $overallAvg ? ($overallAvg - 1) * 0.5 + 1.0 : null; // approximate weight
            }
        }

        // Response distribution (only non-null scores)
        $stmt = $this->db->query("SELECT score, COUNT(*) as count FROM responses WHERE score IS NOT NULL GROUP BY score ORDER BY score");
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

        echo $this->blade->run("admin.analytics", [
            'totalResponses' => $totalResponses,
            'totalSessions' => $totalSessions,
            'completedSessions' => $completedSessions,
            'totalParticipants' => $totalParticipants,
            'moduleAverages' => $moduleAverages,
            'scoreDistribution' => $scoreDistribution,
            'moduleNames' => $moduleNames,
            'avgScores' => $avgScores,
            'scores' => $scores,
            'scoreCounts' => $counts
        ]);
    }
}