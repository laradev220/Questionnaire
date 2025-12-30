<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Research Questionnaire</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50 text-gray-800">


    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto py-10">
            <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-xl border border-gray-100 p-8">
                <h1 class="text-3xl font-extrabold text-gray-900 mb-4"><?php echo htmlspecialchars($surveyTitle); ?></h1>
                <p class="text-gray-600 mb-4">Welcome, <?php echo htmlspecialchars($_SESSION['participant_name'] ?? 'Participant'); ?>!</p>

                <?php if ($survey['description']): ?>
                    <p class="text-gray-600 mb-8 max-w-2xl"><?php echo htmlspecialchars($survey['description']); ?></p>
                <?php endif; ?>

                <div class="bg-blue-50 rounded-lg p-6 border-l-4 border-blue-500 mb-8 flex items-start space-x-4">
                    <i class="fa-solid fa-circle-info text-blue-500 text-xl flex-shrink-0 mt-0.5"></i>
                    <div>
                        <h3 class="font-semibold text-blue-900">Instructions</h3>
                        <p class="text-blue-700 text-sm mt-1">
                            Please use the 5-point Likert scale to rate each statement (1 = Strongly Disagree, 5 = Strongly Agree).
                        </p>
                    </div>
                </div>

                <?php
                $moduleCount = count($modules);
                $currentModule = 1; // Start from first module
                $progress = 0; // No progress tracking for now

                if ($session['is_completed'] ?? false) {
                    $progress = 100;
                }
                ?>

                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Survey Overview</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div class="bg-white p-4 rounded-lg border">
                            <div class="text-2xl font-bold text-blue-600"><?php echo $moduleCount; ?></div>
                            <div class="text-sm text-gray-500">Modules</div>
                        </div>
                        <?php
                        $stmt = $db->prepare("SELECT COUNT(*) as count FROM survey_questions WHERE survey_id = ?");
                        $stmt->execute([$surveyId]);
                        $questionCount = $stmt->fetch()['count'];
                        ?>
                        <div class="bg-white p-4 rounded-lg border">
                            <div class="text-2xl font-bold text-green-600"><?php echo $questionCount; ?></div>
                            <div class="text-sm text-gray-500">Questions</div>
                        </div>
                        <div class="bg-white p-4 rounded-lg border">
                            <div class="text-2xl font-bold text-purple-600"><?php echo $progress; ?>%</div>
                            <div class="text-sm text-gray-500">Complete</div>
                        </div>
                    </div>
                </div>

                <?php if ($session['is_completed'] ?? false): ?>
                    <div class="bg-green-100 text-green-800 p-4 rounded-lg mb-4 font-medium">
                        <i class="fa-solid fa-check-circle mr-2"></i>Survey Completed. Thank you for your participation!
                    </div>
                    <a href="<?php echo BASE_PATH; ?>/thank-you"
                        class="inline-flex items-center justify-center space-x-2 bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-semibold transition-all duration-200 shadow-md hover:shadow-lg">
                        <span>View Completion Confirmation</span>
                        <i class="fa-solid fa-check"></i>
                    </a>
                <?php else: ?>
                    <a href="<?php echo BASE_PATH; ?>/survey?module=1"
                        class="inline-flex items-center justify-center space-x-2 bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition-all duration-200 shadow-md hover:shadow-lg">
                        <span>Start Questionnaire</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>

</html>