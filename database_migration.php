<?php
// Database Migration Script for Multi-Tenant Survey System
// Run this script once to upgrade the database schema

require_once 'config.php';
require_once 'includes/db.php';

echo "Starting Multi-Tenant Survey System Database Migration...\n";

try {
    $db = get_db_connection();
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Debug: Check current database
    $stmt = $db->query("SELECT DATABASE() as db");
    $currentDb = $stmt->fetch()['db'];
    echo "Connected to database: $currentDb\n";

    // Step 1: Add role column to users table
    echo "Step 1: Adding role column to users table...\n";
    try {
        $db->exec("ALTER TABLE users ADD COLUMN role ENUM('researcher', 'admin', 'super_admin') DEFAULT 'researcher' AFTER is_admin");
        echo "✓ Added role column\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') === false) {
            throw $e;
        }
        echo "✓ Role column already exists, modifying enum...\n";
        try {
            $db->exec("ALTER TABLE users MODIFY COLUMN role ENUM('researcher', 'admin', 'super_admin') DEFAULT 'researcher'");
            echo "✓ Modified role enum\n";
        } catch (PDOException $e2) {
            echo "✓ Role enum already includes super_admin\n";
        }
    }

    // Step 2: Update existing users with correct roles based on is_admin
    echo "Step 2: Setting user roles based on is_admin flag...\n";
    $db->exec("UPDATE users SET role = CASE WHEN is_admin = 1 THEN 'admin' ELSE 'researcher' END");
    echo "✓ Updated user roles\n";

    // Step 3: Create surveys table
    echo "Step 3: Creating surveys table...\n";
    $db->exec("
        CREATE TABLE IF NOT EXISTS surveys (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            link_token VARCHAR(64) UNIQUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            deadline DATETIME NULL,
            is_active BOOLEAN DEFAULT TRUE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "✓ Created surveys table\n";

    // Step 4: Create survey_questions table
    echo "Step 4: Creating survey_questions table...\n";
    $db->exec("
        CREATE TABLE IF NOT EXISTS survey_questions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            survey_id INT NOT NULL,
            question_id INT NOT NULL,
            order_index INT DEFAULT 0,
            FOREIGN KEY (survey_id) REFERENCES surveys(id) ON DELETE CASCADE,
            FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE,
            UNIQUE KEY unique_survey_question (survey_id, question_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "✓ Created survey_questions table\n";

    // Step 5: Create question_options table
    echo "Step 5: Creating question_options table...\n";
    $db->exec("
        CREATE TABLE IF NOT EXISTS question_options (
            id INT AUTO_INCREMENT PRIMARY KEY,
            question_id INT NOT NULL,
            option_text TEXT NOT NULL,
            option_value INT NOT NULL,
            order_index INT DEFAULT 0,
            FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "✓ Created question_options table\n";

    // Step 6: Add survey_id to survey_sessions table
    echo "Step 5: Adding survey_id to survey_sessions table...\n";
    try {
        $db->exec("ALTER TABLE survey_sessions ADD COLUMN survey_id INT NULL AFTER participant_id");
        $db->exec("ALTER TABLE survey_sessions ADD CONSTRAINT fk_survey_sessions_survey FOREIGN KEY (survey_id) REFERENCES surveys(id)");
        echo "✓ Added survey_id column to survey_sessions\n";
    } catch (PDOException $e) {
        if (
            strpos($e->getMessage(), 'Duplicate column name') === false &&
            strpos($e->getMessage(), 'Duplicate foreign key') === false
        ) {
            throw $e;
        }
        echo "✓ survey_id column already exists in survey_sessions\n";
    }

    // Step 7: Add survey_id to participants table
    echo "Step 7: Adding survey_id to participants table...\n";
    try {
        $db->exec("ALTER TABLE participants ADD COLUMN survey_id INT NULL AFTER id");
        $db->exec("ALTER TABLE participants ADD CONSTRAINT fk_participants_survey FOREIGN KEY (survey_id) REFERENCES surveys(id) ON DELETE CASCADE");
        echo "✓ Added survey_id column to participants\n";
    } catch (PDOException $e) {
        if (
            strpos($e->getMessage(), 'Duplicate column name') === false &&
            strpos($e->getMessage(), 'Duplicate foreign key') === false
        ) {
            throw $e;
        }
        echo "✓ survey_id column already exists in participants\n";
    }

    // Step 7: Create settings table for admin configuration
    echo "Step 6: Creating settings table...\n";
    $db->exec("
        CREATE TABLE IF NOT EXISTS settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            `key` VARCHAR(255) UNIQUE NOT NULL,
            value TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "✓ Created settings table\n";

    // Step 9: Migrate existing data
    echo "Step 9: Migrating existing data...\n";

    // Get admin user ID
    $adminUserId = $db->query("SELECT id FROM users WHERE role = 'admin' OR role = 'super_admin' LIMIT 1")->fetchColumn();
    if (!$adminUserId) {
        $adminUserId = $db->query("SELECT id FROM users LIMIT 1")->fetchColumn();
    }

    // If there's still no user present, create a default super_admin to own migrated data
    if (!$adminUserId) {
        echo "No users found — creating default super_admin...\n";
        $defaultAdminEmail = 'admin@example.com';
        $defaultAdminName = 'Administrator';
        $defaultPassword = bin2hex(random_bytes(8));
        $hashed = password_hash($defaultPassword, PASSWORD_DEFAULT);
        $createStmt = $db->prepare("INSERT INTO users (name, email, password, role, is_admin) VALUES (?, ?, ?, 'super_admin', 1)");
        $createStmt->execute([$defaultAdminName, $defaultAdminEmail, $hashed]);
        $adminUserId = $db->lastInsertId();
        echo "✓ Created default super_admin with ID: $adminUserId and email: $defaultAdminEmail\n";
    }

    // Create default survey for existing questions (if not exists)
    $existingSurvey = $db->query("SELECT id FROM surveys WHERE title = 'SHRM Survey' LIMIT 1")->fetch();
    if (!$existingSurvey) {
        $linkToken = bin2hex(random_bytes(16));
        $stmt = $db->prepare("INSERT INTO surveys (user_id, title, description, link_token, is_active) VALUES (?, 'SHRM Survey', 'Sustainable Human Resource Management Survey', ?, 1)");
        $stmt->execute([$adminUserId, $linkToken]);
        $defaultSurveyId = $db->lastInsertId();
        echo "✓ Created default survey with ID: $defaultSurveyId\n";
    } else {
        $defaultSurveyId = $existingSurvey['id'];
        echo "✓ Default survey already exists with ID: $defaultSurveyId\n";
    }

    // Assign all existing questions to the default survey (if not already assigned)
    $assignStmt = $db->prepare("INSERT INTO survey_questions (survey_id, question_id, order_index) SELECT ?, id, id FROM questions q WHERE NOT EXISTS (SELECT 1 FROM survey_questions sq WHERE sq.survey_id = ? AND sq.question_id = q.id)");
    $assignStmt->execute([$defaultSurveyId, $defaultSurveyId]);
    echo "✓ Assigned existing questions to default survey\n";

    // Set survey_id for existing participants and sessions (if not set)
    $updPartStmt = $db->prepare("UPDATE participants SET survey_id = ? WHERE survey_id IS NULL");
    $updPartStmt->execute([$defaultSurveyId]);
    $updSessStmt = $db->prepare("UPDATE survey_sessions SET survey_id = ? WHERE survey_id IS NULL");
    $updSessStmt->execute([$defaultSurveyId]);
    echo "✓ Linked existing participants and sessions to default survey\n";

    // Set user_id for existing questions (to admin, if not set)
    $updQStmt = $db->prepare("UPDATE questions SET user_id = ? WHERE user_id IS NULL");
    $updQStmt->execute([$adminUserId]);
    echo "✓ Assigned ownership of existing questions to admin user\n";

    // Set first admin as super_admin
    $db->exec("UPDATE users SET role = 'super_admin' WHERE role = 'admin' LIMIT 1");
    echo "✓ Promoted first admin to super_admin\n";

    // Step 10: Add user_id to questions table for researcher ownership
    echo "Step 8: Adding user_id and created_at to questions table...\n";
    try {
        $db->exec("ALTER TABLE questions ADD COLUMN user_id INT NULL AFTER id");
        $db->exec("ALTER TABLE questions ADD CONSTRAINT fk_questions_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE");
        echo "✓ Added user_id column to questions table\n";
    } catch (PDOException $e) {
        if (
            strpos($e->getMessage(), 'Duplicate column name') === false &&
            strpos($e->getMessage(), 'Duplicate foreign key') === false
        ) {
            throw $e;
        }
        echo "✓ user_id column already exists in questions table\n";
    }

    try {
        $db->exec("ALTER TABLE questions ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
        echo "✓ Added created_at column to questions table\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') === false) {
            throw $e;
        }
        echo "✓ created_at column already exists in questions table\n";
    }

    try {
        $db->exec("ALTER TABLE questions ADD COLUMN type ENUM('scale','multiple_choice') DEFAULT 'scale'");
        echo "✓ Added type column to questions table\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') === false) {
            throw $e;
        }
        echo "✓ type column already exists in questions table\n";
    }

    // Step 9: Change responses.question_id to INT if it's VARCHAR
    echo "Step 9: Checking responses table structure...\n";
    // Only attempt if `responses` table exists
    $tableCheck = $db->query("SHOW TABLES LIKE 'responses'");
    if ($tableCheck->rowCount() === 0) {
        echo "✓ responses table does not exist, skipping conversion\n";
    } else {
        $stmt = $db->query("DESCRIBE responses");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $questionIdType = null;

        foreach ($columns as $column) {
            if ($column['Field'] === 'question_id') {
                $questionIdType = $column['Type'];
                break;
            }
        }

        if ($questionIdType && (strpos($questionIdType, 'varchar') !== false || strpos($questionIdType, 'char') !== false)) {
            echo "Converting responses.question_id from VARCHAR to INT...\n";

            // First, convert any question codes to IDs
            // Handle CDC_1 -> CDC 1
            $db->exec("
                    UPDATE responses r
                    JOIN questions q ON REPLACE(r.question_id, '_', ' ') = q.code
                    SET r.question_id = q.id
                    WHERE r.question_id LIKE 'CDC_%'
                ");

            // Handle HRA1_1 -> HRA1.1
            $db->exec("
                    UPDATE responses r
                    JOIN questions q ON REPLACE(r.question_id, '_', '.') = q.code
                    SET r.question_id = q.id
                    WHERE r.question_id LIKE 'HRA%_%'
                ");

            // Any remaining codes that match exactly
            $db->exec("
                    UPDATE responses r
                    JOIN questions q ON r.question_id = q.code
                    SET r.question_id = q.id
                    WHERE r.question_id NOT REGEXP '^[0-9]+$'
                ");

            // Now convert string numbers to actual integers
            $db->exec("UPDATE responses SET question_id = CAST(question_id AS UNSIGNED) WHERE question_id REGEXP '^[0-9]+$'");

            // Convert column type
            $db->exec("ALTER TABLE responses MODIFY COLUMN question_id INT NOT NULL");

            // Add foreign key constraint
            try {
                $db->exec("ALTER TABLE responses ADD CONSTRAINT fk_responses_question FOREIGN KEY (question_id) REFERENCES questions(id)");
            } catch (PDOException $e) {
                // Constraint might already exist
            }

            echo "✓ Converted responses.question_id to INT\n";
        } else {
            echo "✓ responses.question_id is already INT or question_id column not found\n";
        }
    }

    // Step 10: Add performance indexes
    echo "Step 10: Adding performance indexes...\n";
    $indexes = [
        "CREATE INDEX IF NOT EXISTS idx_surveys_user_id ON surveys(user_id)",
        "CREATE INDEX IF NOT EXISTS idx_questions_user_id ON questions(user_id)",
        "CREATE INDEX IF NOT EXISTS idx_survey_sessions_participant_survey ON survey_sessions(participant_id, survey_id)",
        "CREATE INDEX IF NOT EXISTS idx_responses_session_question ON responses(session_id, question_id)",
        "CREATE INDEX IF NOT EXISTS idx_survey_questions_survey_order ON survey_questions(survey_id, order_index)"
    ];

    foreach ($indexes as $indexSql) {
        try {
            $db->exec($indexSql);
        } catch (PDOException $e) {
            // Index might already exist
        }
    }
    echo "✓ Added performance indexes\n";

    // Step 11: Migration verification
    echo "\n=== Migration Verification ===\n";

    // Check table structures
    $tables = ['users', 'surveys', 'survey_questions', 'question_options', 'survey_sessions', 'responses', 'settings'];
    foreach ($tables as $table) {
        $stmt = $db->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "✓ Table '$table' exists\n";
        } else {
            echo "✗ Table '$table' missing\n";
        }
    }

    // Check user roles
    $stmt = $db->query("SELECT role, COUNT(*) as count FROM users GROUP BY role");
    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "\nUser roles:\n";
    foreach ($roles as $role) {
        echo "  {$role['role']}: {$role['count']} users\n";
    }

    // Check admin user
    $stmt = $db->prepare("SELECT id, name, email, role FROM users WHERE role = 'admin' LIMIT 1");
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($admin) {
        echo "\nAdmin user found: {$admin['name']} (ID: {$admin['id']})\n";
    } else {
        echo "\n⚠️  Warning: No admin user found!\n";
    }

    echo "\n🎉 Migration completed successfully!\n";
    echo "You can now use the multi-tenant survey system.\n";
    echo "Admin user can log in at /admin/login\n";
    echo "Researchers can register at /register\n";
} catch (Exception $e) {
    echo "\n❌ Migration failed: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
