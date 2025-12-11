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
    <nav class="bg-white shadow mb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <span class="font-bold text-xl text-blue-600">ResearchSync</span>
                </div>
                <div class="flex items-center">
                    <?php if (isset($_SESSION['participant_name'])): ?>
                        <span class="text-sm text-gray-500">Hello, <?php echo htmlspecialchars($_SESSION['participant_name']); ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto py-10">
            <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-xl border border-gray-100 p-8">
                <h1 class="text-3xl font-extrabold text-gray-900 mb-4">Survey Progress</h1>
                <p class="text-gray-600 mb-4">Welcome, <?php echo htmlspecialchars($_SESSION['participant_name'] ?? 'Participant'); ?>!</p>
                <p class="text-gray-600 mb-8 max-w-2xl">
                    Thank you for participating in our research on Sustainable Human Resource Management and Organizational
                    Resilience.
                    The survey consists of 6 core modules and should take approximately 10-15 minutes to complete.
                </p>

                <div class="bg-blue-50 rounded-lg p-6 border-l-4 border-blue-500 mb-8 flex items-start space-x-4">
                    <i class="fa-solid fa-circle-info text-blue-500 text-xl flex-shrink-0 mt-0.5"></i>
                    <div>
                        <h3 class="font-semibold text-blue-900">Instructions</h3>
                        <p class="text-blue-700 text-sm mt-1">
                            Please use the 5-point Likert scale to rate each statement (1 = Strongly Disagree, 5 = Strongly
                            Agree).
                        </p>
                    </div>
                </div>

                <?php
                $currentModule = $session['current_module'] ?? 1;
                $moduleCount = 6; // Total modules based on the schema
                if ($session['is_completed'] ?? false) {
                    $progress = 100;
                    $currentModule = $moduleCount;
                } else {
                    $progress = round((($currentModule - 1) / $moduleCount) * 100);
                }
                ?>

                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Your Progress</h3>
                    <div class="flex justify-between text-sm font-medium text-gray-500 mb-2">
                        <span>Module <?php echo $currentModule; ?> of <?php echo $moduleCount; ?></span>
                        <span><?php echo $progress; ?>% Complete</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-500"
                            style="width: <?php echo $progress; ?>%"></div>
                    </div>
                </div>

                <?php if ($session['is_completed'] ?? false): ?>
                    <div class="bg-green-100 text-green-800 p-4 rounded-lg mb-4 font-medium">
                        <i class="fa-solid fa-check-circle mr-2"></i>Survey Completed. Thank you!
                    </div>
                    <a href="<?php echo BASE_PATH; ?>/thank-you" class="text-blue-600 hover:text-blue-800 font-medium">View Final Confirmation</a>
                <?php else: ?>
                    <a href="<?php echo BASE_PATH; ?>/survey?module=<?php echo $currentModule; ?>"
                        class="inline-flex items-center justify-center space-x-2 bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition-all duration-200 shadow-md hover:shadow-lg">
                        <span><?php echo $currentModule > 1 ? 'Continue Survey' : 'Start Questionnaire'; ?></span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>

</html>