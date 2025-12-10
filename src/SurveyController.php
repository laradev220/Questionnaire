<?php

namespace App;

use PDO;

class SurveyController
{
    private $db;
    private $blade;

    private $moduleNames = [
        1 => 'Sustainable Human Resource Management (SHRM)',
        2 => 'Changing Dynamic Capabilities (CDC) & Employee Reciprocity',
        3 => 'Human Resource Analytics (HRA)',
        4 => 'HR Competencies (HRC)',
        5 => 'Knowledge Management (KM)',
        6 => 'Organisational Resilience (OR)'
    ];

    public function __construct($blade)
    {
        $this->db = DB::getInstance()->getConnection();
        $this->blade = $blade;
    }

    public function startSurvey()
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $university = $_POST['university'];
        $designation = $_POST['designation'];

        // Check if participant exists
        $stmt = $this->db->prepare("SELECT id, name FROM participants WHERE email = ?");
        $stmt->execute([$email]);
        $existing = $stmt->fetch();

        if ($existing) {
            $participantId = $existing['id'];
            $name = $existing['name'];
        } else {
            // Insert new participant
            $stmt = $this->db->prepare("INSERT INTO participants (name, email, phone, university, designation) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $email, $phone, $university, $designation]);
            $participantId = $this->db->lastInsertId();
        }

        // Check for active session
        $stmt = $this->db->prepare("SELECT id FROM survey_sessions WHERE participant_id = ? AND is_completed = 0 ORDER BY id DESC LIMIT 1");
        $stmt->execute([$participantId]);
        $session = $stmt->fetch();

        if (!$session) {
            // Create new survey session
            $stmt = $this->db->prepare("INSERT INTO survey_sessions (participant_id) VALUES (?)");
            $stmt->execute([$participantId]);
        }

        // Store in session
        $_SESSION['participant_id'] = $participantId;
        $_SESSION['participant_name'] = $name;

        header("Location: /dashboard");
        exit;
    }

    public function dashboard()
    {
        try {
            $participantId = $_SESSION['participant_id'];

            // Check if participant has already completed a survey
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM survey_sessions WHERE participant_id = ? AND is_completed = 1");
            $stmt->execute([$participantId]);
            $completedCount = $stmt->fetch()['count'];

            if ($completedCount > 0) {
                // Participant has completed before, show completed status
                $session = ['is_completed' => 1, 'current_module' => 6]; // Dummy session for display
            } else {
                // Find or create active session
                $stmt = $this->db->prepare("SELECT * FROM survey_sessions WHERE participant_id = ? AND is_completed = 0");
                $stmt->execute([$participantId]);
                $session = $stmt->fetch();

                if (!$session) {
                    $this->db->prepare("INSERT INTO survey_sessions (participant_id) VALUES (?)")->execute([$participantId]);
                    $stmt = $this->db->prepare("SELECT * FROM survey_sessions WHERE participant_id = ? AND is_completed = 0");
                    $stmt->execute([$participantId]);
                    $session = $stmt->fetch();
                }
            }

            echo $this->blade->run("survey.dashboard", ['session' => $session]);
        } catch (\Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function showModule($moduleId, $page = 1)
    {
        try {
            if (!isset($this->moduleNames[$moduleId])) {
                header("Location: /thank-you");
                exit;
            }

            $moduleName = $this->moduleNames[$moduleId];
            $stmt = $this->db->prepare("SELECT `group` FROM questions WHERE module = ? GROUP BY `group` ORDER BY MIN(id)");
            $stmt->execute([$moduleName]);
            $groups = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $totalPages = count($groups);
            $page = max(1, min($page, $totalPages));

            $currentGroup = $groups[$page - 1];
            $stmt = $this->db->prepare("SELECT code, text FROM questions WHERE module = ? AND `group` = ? ORDER BY id");
            $stmt->execute([$moduleName, $currentGroup]);
            $questions = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // code => text

            $module = [
                'title' => $moduleName,
                'group' => $currentGroup,
                'questions' => $questions
            ];

            echo $this->blade->run("survey.module", [
                'module' => $module,
                'moduleId' => $moduleId,
                'totalModules' => count($this->moduleNames),
                'page' => $page,
                'totalPages' => $totalPages
            ]);
        } catch (\Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function storeModule($moduleId, $page)
    {
        try {
            $participantId = $_SESSION['participant_id'];

            // Get active session
            $stmt = $this->db->prepare("SELECT id FROM survey_sessions WHERE participant_id = ? AND is_completed = 0");
            $stmt->execute([$participantId]);
            $session = $stmt->fetch();

            if (!$session) {
                die("No active session");
            }

            // Get groups for this module
            $moduleName = $this->moduleNames[$moduleId];
            $stmt = $this->db->prepare("SELECT `group` FROM questions WHERE module = ? GROUP BY `group` ORDER BY MIN(id)");
            $stmt->execute([$moduleName]);
            $groups = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $totalPages = count($groups);
            $currentGroup = $groups[$page - 1];



             // Get all questions in the current group
             $stmt = $this->db->prepare("SELECT code, text FROM questions WHERE module = ? AND `group` = ? ORDER BY id");
             $stmt->execute([$moduleName, $currentGroup]);
             $questions = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // code => text

              // Save Answers for current group
              $weights = [1 => 1.0, 2 => 1.5, 3 => 2.0, 4 => 2.5, 5 => 3.0];
               $insertStmt = $this->db->prepare("INSERT INTO responses (session_id, question_id, score, weight) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE score = VALUES(score), weight = VALUES(weight)");

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
                header("Location: /survey?module=" . $moduleId . "&page=" . ($page + 1));
            } else {
                // Next module
                $nextModule = $moduleId + 1;
                if ($nextModule > count($this->moduleNames)) {
                    $this->db->prepare("UPDATE survey_sessions SET is_completed = 1 WHERE id = ?")->execute([$session['id']]);
                    header("Location: /thank-you");
                } else {
                    $this->db->prepare("UPDATE survey_sessions SET current_module = ? WHERE id = ?")->execute([$nextModule, $session['id']]);
                    header("Location: /survey?module=" . $nextModule);
                }
            }
        } catch (\Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }
}
