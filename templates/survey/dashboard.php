<?php echo ""; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Research Questionnaire - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .dashboard-card {
            background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .progress-bar {
            background: linear-gradient(90deg, #3b82f6 0%, #1d4ed8 100%);
            transition: all 0.5s ease;
        }

        .module-card {
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }



        .start-btn {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.2);
        }



        .info-box {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border: 1px solid rgba(191, 219, 254, 0.5);
        }

        /* Add this to your existing style section */
        @media (min-width: 475px) {
            .xs\:inline {
                display: inline;
            }
        }
    </style>
</head>

<body class="bg-gradient-to-br from-slate-50 via-white to-blue-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white/90 backdrop-blur-sm border-b border-slate-200 shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 py-2 sm:py-3">
            <div class="flex items-center justify-between">
                <!-- Logo and Brand -->
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <div
                        class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-lg flex items-center justify-center shadow-md">
                        <i class="fas fa-microscope text-white text-xs sm:text-sm"></i>
                    </div>
                    <div>
                        <span class="font-bold text-base sm:text-lg text-slate-800 tracking-tight">ResearchSync</span>
                        <span class="hidden xs:inline text-xs text-slate-500 font-medium ml-1 sm:ml-2">Academic Survey
                            Platform</span>
                    </div>
                </div>

                <!-- Right Side Menu -->
                <div class="flex items-center space-x-3 sm:space-x-4 md:space-x-6">
                    <!-- Contact Link - Icon only on mobile -->
                    <a href="#footer"
                        class="text-slate-600 font-medium text-sm transition-colors duration-200 flex items-center group">
                        <i class="fas fa-question-circle text-blue-500 text-base sm:text-lg mr-1 sm:mr-2"></i>
                        <span class="hidden sm:inline">Contact Us</span>
                        <span class="sm:hidden text-xs">Help</span>
                    </a>

                    <!-- User Info (if logged in) -->
                    <?php if (isset($_SESSION['participant_name'])): ?>
                        <div class="hidden sm:flex items-center">
                            <div
                                class="w-7 h-7 sm:w-8 sm:h-8 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full flex items-center justify-center mr-2 sm:mr-3">
                                <i class="fas fa-user text-blue-600 text-xs sm:text-sm"></i>
                            </div>
                            <span
                                class="text-xs sm:text-sm text-slate-700 font-medium truncate max-w-[120px] md:max-w-none">
                                <?php echo htmlspecialchars($_SESSION['participant_name'] ?? 'Participant'); ?>
                            </span>
                        </div>

                        <!-- Mobile User Icon Only -->
                        <div class="sm:hidden flex items-center">
                            <div
                                class="w-7 h-7 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-blue-600 text-xs"></i>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-3 sm:px-4 py-6 sm:py-8">
        <div class="max-w-5xl mx-auto w-full">
            <!-- Welcome Header -->
            <div class="text-center mb-6 sm:mb-10">
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-slate-900 mb-3">Survey Progress Dashboard
                </h1>
                <p class="text-sm sm:text-base md:text-lg text-slate-600 max-w-2xl mx-auto px-2 sm:px-0">
                    Welcome back, <span
                        class="font-semibold text-blue-700"><?php echo htmlspecialchars($_SESSION['participant_name'] ?? 'Participant'); ?></span>!
                    Thank you for contributing to our research.
                </p>
            </div>

            <!-- Main Dashboard Card -->
            <div class="dashboard-card rounded-2xl p-4 sm:p-6 md:p-8 mb-6 sm:mb-8">
                <?php
                $currentModule = $session['current_module'] ?? 1;
                $moduleCount = count($modules);
                if ($session['is_completed'] ?? false) {
                    $progress = 100;
                    $currentModule = $moduleCount;
                } else {
                    $progress = round((($currentModule - 1) / $moduleCount) * 100);
                }
                ?>

                <!-- Survey Overview -->
                <div class="mb-10">
                    <h2 class="text-2xl font-bold text-slate-900 mb-4 flex items-center">
                        <i class="fas fa-clipboard-list text-blue-500 mr-3"></i>
                        Research Study Overview
                    </h2>
                    <p class="text-slate-600 leading-relaxed mb-6">
                        <?php echo htmlspecialchars($survey['description'] ?? 'This survey will help us gather valuable insights for our research.'); ?>
                    </p>

                    <!-- Info Box -->
                    <div class="info-box rounded-xl p-6 mb-8">
                        <div class="flex items-start">
                            <div class="mr-4 mt-1">
                                <i class="fas fa-info-circle text-blue-500 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-800 mb-2">Survey Instructions</h3>
                                <p class="text-slate-700 text-sm leading-relaxed">
                                    Please use the 5-point Likert scale to rate each statement in the survey:
                                    <span class="font-medium">1 = Strongly Disagree, 5 = Strongly Agree</span>.
                                    Take your time to reflect on each question based on your professional experience.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Progress Section -->
                <div class="mb-12">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
                        <div>
                            <h3 class="text-xl font-bold text-slate-900 mb-2">Your Current Progress</h3>
                            <p class="text-slate-600 text-sm">Complete all <?php echo $moduleCount; ?> modules to finish
                                the survey</p>
                        </div>
                        <div class="mt-4 md:mt-0 text-center">
                            <div class="text-4xl font-bold text-blue-600"><?php echo $progress; ?>%</div>
                            <div class="text-sm text-slate-500">Overall Complete</div>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="mb-6">
                        <div class="flex justify-between text-sm font-medium text-slate-700 mb-3">
                            <span>Module <?php echo $currentModule; ?> of <?php echo $moduleCount; ?></span>
                            <span><?php echo $moduleCount - $currentModule + 1; ?> modules remaining</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-3">
                            <div class="progress-bar h-3 rounded-full" style="width: <?php echo $progress; ?>%">
                                <div class="h-3 w-3 bg-white rounded-full float-right mr-1 mt-0.5 shadow-sm"></div>
                            </div>
                        </div>
                        <div class="flex justify-between mt-2 text-xs text-slate-500">
                            <?php for ($i = 1; $i <= $moduleCount; $i++): ?>
                                <span class="<?php echo $i <= $currentModule ? 'text-blue-600 font-semibold' : ''; ?>">
                                    M<?php echo $i; ?>
                                </span>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>

                <!-- Survey Status -->
                <?php if ($session['is_completed'] ?? false): ?>
                    <!-- Completion State -->
                    <div
                        class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-4 sm:p-6 md:p-8 border border-emerald-200 mb-6 sm:mb-8">
                        <div class="flex flex-col md:flex-row items-center justify-between">
                            <div class="flex flex-col sm:flex-row items-center mb-4 sm:mb-6 md:mb-0">
                                <div
                                    class="w-12 h-12 sm:w-14 sm:h-14 md:w-16 md:h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center sm:mr-4 md:mr-6 shadow-lg mb-3 sm:mb-0">
                                    <i class="fas fa-check text-white text-lg sm:text-xl md:text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-emerald-900 mb-2">Survey Completed!</h3>
                                    <p class="text-emerald-700">Thank you for your valuable contribution to our research.
                                    </p>
                                </div>
                            </div>
                            <a href="<?php echo BASE_PATH; ?>/thank-you"
                                class="inline-flex items-center justify-center space-x-3 bg-gradient-to-r from-emerald-500 to-green-600 text-white px-8 py-4 rounded-xl font-bold transition-all duration-300 shadow-lg">
                                <i class="fas fa-file-certificate"></i>
                                <span>View Completion Confirmation</span>
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Action Section -->
                    <div class="text-center pt-6 border-t border-slate-200">
                        <a href="<?php echo BASE_PATH; ?>/survey?module=<?php echo $currentModule; ?>"
                            class="start-btn inline-flex items-center justify-center space-x-3 text-white px-10 py-5 rounded-xl font-bold text-lg">
                            <span><?php echo $currentModule > 1 ? 'Continue Survey' : 'Begin Survey Now'; ?></span>
                            <i class="fas fa-arrow-right text-xl"></i>
                        </a>
                        <p class="text-slate-500 text-sm mt-4 flex items-center justify-center">
                            <i class="fas fa-clock text-blue-500 mr-2"></i>
                            Estimated time remaining:
                            <?php echo round(($moduleCount - $currentModule + 1) * 2.5); ?>-<?php echo round(($moduleCount - $currentModule + 1) * 3.5); ?>
                            minutes
                        </p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Module Grid (Only show if survey not completed) -->
            <?php if (!($session['is_completed'] ?? false)): ?>
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-slate-900 mb-6 flex items-center">
                        <i class="fas fa-layer-group text-blue-500 mr-3"></i>
                        Survey Modules
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                        <?php for ($i = 1; $i <= $moduleCount; $i++):
                            $moduleName = $modules[$i - 1] ?? 'Module ' . $i;
                            $isCurrent = $i == $currentModule;
                            $isCompleted = $i < $currentModule;
                            $isFuture = $i > $currentModule;
                        ?>
                            <div
                                class="module-card rounded-xl p-4 sm:p-5 md:p-6 <?php echo $isCurrent ? 'ring-2 ring-blue-500 ring-offset-2' : ''; ?>">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-center">
                                        <div
                                            class="w-12 h-12 rounded-xl <?php echo $isCompleted ? 'bg-gradient-to-br from-green-100 to-emerald-100 text-green-600' : ($isCurrent ? 'bg-gradient-to-br from-blue-100 to-indigo-100 text-blue-600' : 'bg-slate-100 text-slate-400'); ?> flex items-center justify-center mr-4">
                                            <?php if ($isCompleted): ?>
                                                <i class="fas fa-check text-lg"></i>
                                            <?php else: ?>
                                                <span class="font-bold text-lg"><?php echo $i; ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-slate-800"><?php echo htmlspecialchars($moduleName); ?>
                                            </h4>
                                            <p class="text-sm text-slate-500">Questions related to
                                                <?php echo htmlspecialchars(strtolower($moduleName)); ?></p>
                                        </div>
                                    </div>
                                    <?php if ($isCurrent): ?>
                                        <span
                                            class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full">Active</span>
                                    <?php elseif ($isCompleted): ?>
                                        <span
                                            class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">Completed</span>
                                    <?php endif; ?>
                                </div>
                                <div class="text-xs text-slate-500">
                                    Please answer all questions in this module carefully.
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Research Credentials -->
            <div class="bg-gradient-to-r from-slate-50 to-blue-50 rounded-xl p-6 border border-blue-100">
                <h4 class="font-bold text-slate-800 mb-4 text-center">Research Credentials</h4>
                <div class="flex flex-wrap justify-center gap-4">
                    <div class="flex items-center bg-white px-5 py-3 rounded-lg shadow-sm border border-slate-200">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-check text-green-600 text-sm"></i>
                        </div>
                        <span class="text-sm text-slate-700 font-medium">IRB Approved Study</span>
                    </div>
                    <div class="flex items-center bg-white px-5 py-3 rounded-lg shadow-sm border border-slate-200">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-lock text-blue-600 text-sm"></i>
                        </div>
                        <span class="text-sm text-slate-700 font-medium">Data Privacy Protected</span>
                    </div>
                    <div class="flex items-center bg-white px-5 py-3 rounded-lg shadow-sm border border-slate-200">
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-user-shield text-purple-600 text-sm"></i>
                        </div>
                        <span class="text-sm text-slate-700 font-medium">Confidential Responses</span>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include __DIR__ . '/../footer.php'; ?>

    <script>
        // Smooth progress bar animation
        document.addEventListener('DOMContentLoaded', function() {
            const progressBar = document.querySelector('.progress-bar');
            if (progressBar) {
                const width = progressBar.style.width;
                progressBar.style.width = '0';
                setTimeout(() => {
                    progressBar.style.width = width;
                }, 100);
            }


        });
    </script>
</body>

</html>