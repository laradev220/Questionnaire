<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Research Questionnaire</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .form-card {
            background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .progress-bar-bg {
            background: linear-gradient(90deg, #e2e8f0 0%, #cbd5e1 100%);
        }

        .progress-bar-fill {
            background: linear-gradient(90deg, #3b82f6 0%, #1d4ed8 100%);
            transition: width 0.5s ease;
        }

        .question-item {
            transition: all 0.3s ease;
        }

        .radio-option {
            transition: all 0.2s ease;
        }

        .radio-option input:checked+.radio-content {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            border-color: #3b82f6;
            color: white;
        }

        .nav-btn {
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid #e2e8f0;
        }

        .submit-btn {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.2);
        }
    </style>
</head>

<body class="bg-gradient-to-br from-slate-50 via-white to-blue-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white/90 backdrop-blur-sm border-b border-slate-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-lg flex items-center justify-center shadow-md">
                        <i class="fas fa-microscope text-white text-sm"></i>
                    </div>
                    <div>
                        <span class="font-bold text-lg text-slate-800 tracking-tight">ResearchSync</span>
                        <span class="text-xs text-slate-500 font-medium ml-2">Academic Survey Platform</span>
                    </div>
                </div>
                <div class="text-sm text-slate-700 font-medium bg-blue-50 px-4 py-2 rounded-lg">
                    M<?php echo $moduleId; ?> of <?php echo $totalModules; ?>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 py-8">
        <div class="w-full max-w-5xl mx-auto">
            <!-- Progress Bar -->
            <div class="mb-8">
                <div class="form-card rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex justify-between text-sm font-medium text-gray-700 mb-3">
                        <span>Module <?php echo $moduleId; ?> of <?php echo $totalModules; ?>, Group
                            <?php echo $page; ?> of <?php echo $totalPages; ?></span>
                        <span
                            class="font-semibold"><?php echo round((($moduleId - 1 + ($page - 1) / $totalPages) / $totalModules) * 100); ?>%
                            Complete</span>
                    </div>
                    <div class="w-full progress-bar-bg rounded-full h-3">
                        <div class="progress-bar-fill h-3 rounded-full"
                            style="width: <?php echo (($moduleId - 1 + ($page - 1) / $totalPages) / $totalModules) * 100; ?>%">
                        </div>
                    </div>
                </div>
            </div>

            <form method="POST" action="<?php echo BASE_PATH; ?>/survey?module=<?php echo $moduleId; ?>"
                class="form-card rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <input type="hidden" name="page" value="<?php echo $page; ?>">
                <div class="bg-blue-50 px-6 py-4 border-b border-blue-200">
                    <h2 class="text-xl font-bold text-blue-900"><?php echo htmlspecialchars($module['title']); ?></h2>
                    <h3 class="text-lg font-semibold text-blue-800 mt-2">
                        <?php echo htmlspecialchars($module['group']); ?></h3>
                    <p class="text-sm text-blue-700 mt-1">Please rate the following statements on a scale from Strongly
                        Disagree to Strongly Agree.</p>
                </div>

                <div class="p-6 space-y-6">
                    <?php foreach ($module['questions'] as $id => $question): ?>
                        <div class="question-item pb-6 border-b border-gray-100 last:border-0 last:pb-0 rounded-lg p-4">
                            <div class="flex flex-col md:flex-row md:items-start md:justify-between">
                                <p class="font-medium text-gray-800 mb-4 md:mb-0 md:flex-1 md:pr-4 text-base"><span
                                        class="text-gray-500 text-sm mr-2 font-mono"><?php echo htmlspecialchars($id); ?></span><?php echo htmlspecialchars($question['text']); ?>
                                </p>

                                <div class="flex flex-col md:flex-row md:space-x-3 md:space-y-0 space-y-2 md:min-w-max">
                                    <?php if ($question['type'] === 'scale'): ?>
                                        <?php
                                        $labels = ['Strongly Disagree', 'Disagree', 'Neutral', 'Agree', 'Strongly Agree'];
                                        $colors = ['red', 'orange', 'yellow', 'lime', 'green'];
                                        ?>
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <label class="radio-option inline-flex items-center cursor-pointer group">
                                                <input type="radio" name="<?php echo htmlspecialchars($id); ?>"
                                                    value="<?php echo $i; ?>" class="sr-only peer">
                                                <div
                                                    class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center peer-checked:bg-<?php echo $colors[$i - 1]; ?>-500 peer-checked:border-<?php echo $colors[$i - 1]; ?>-500 transition-all duration-200 group-hover:border-<?php echo $colors[$i - 1]; ?>-400">
                                                    <i
                                                        class="fas fa-check text-white text-xs opacity-0 peer-checked:opacity-100 transition-opacity duration-200"></i>
                                                </div>
                                                <span
                                                    class="ml-2 text-sm text-gray-700 peer-checked:text-<?php echo $colors[$i - 1]; ?>-600 peer-checked:font-medium transition-colors duration-200"><?php echo $labels[$i - 1]; ?></span>
                                            </label>
                                        <?php endfor; ?>
                                    <?php elseif ($question['type'] === 'multiple_choice'): ?>
                                        <?php foreach ($question['options'] as $option): ?>
                                            <label class="radio-option inline-flex items-center cursor-pointer group">
                                                <input type="radio" name="<?php echo htmlspecialchars($id); ?>"
                                                    value="<?php echo $option['option_value']; ?>" class="sr-only peer">
                                                <div
                                                    class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center peer-checked:bg-blue-500 peer-checked:border-blue-500 transition-all duration-200 group-hover:border-blue-400">
                                                    <i
                                                        class="fas fa-check text-white text-xs opacity-0 peer-checked:opacity-100 transition-opacity duration-200"></i>
                                                </div>
                                                <span
                                                    class="ml-2 text-sm text-gray-700 peer-checked:text-blue-600 peer-checked:font-medium transition-colors duration-200"><?php echo htmlspecialchars($option['option_text']); ?></span>
                                            </label>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-between items-center">
                    <div class="flex space-x-3">
                        <?php if ($moduleId > 1): ?>
                            <a href="<?php echo BASE_PATH; ?>/survey?module=<?php echo $moduleId - 1; ?>"
                                class="nav-btn text-gray-600 hover:text-gray-800 px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition-all duration-200 font-medium">
                                <i class="fas fa-arrow-left mr-1"></i>Previous Module
                            </a>
                        <?php endif; ?>
                        <?php if ($page > 1): ?>
                            <a href="<?php echo BASE_PATH; ?>/survey?module=<?php echo $moduleId; ?>&page=<?php echo $page - 1; ?>"
                                class="nav-btn text-gray-600 hover:text-gray-800 px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition-all duration-200 font-medium">
                                <i class="fas fa-chevron-left mr-1"></i>Previous Group
                            </a>
                        <?php endif; ?>
                    </div>

                    <button type="submit"
                        class="submit-btn text-white font-semibold py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                        <?php echo $page == $totalPages ? ($moduleId == $totalModules ? 'Submit Survey' : 'Next Module') : 'Next Group'; ?>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </main>
    <?php include __DIR__ . '/../footer.php'; ?>

    <script>
        // Form submission loading state
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalHTML = submitBtn.innerHTML;

                submitBtn.innerHTML = `
                    <i class="fas fa-spinner fa-spin mr-2"></i>
                    Processing...
                `;
                submitBtn.disabled = true;

                // Reset button after 8 seconds if something goes wrong
                setTimeout(() => {
                    submitBtn.innerHTML = originalHTML;
                    submitBtn.disabled = false;
                }, 8000);
            });
        });
    </script>
</body>

</html>