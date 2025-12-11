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
        <div class="w-full">
            <!-- Progress Bar -->
            <div class="mb-8">
                <div class="flex justify-between text-sm font-medium text-gray-500 mb-2">
                    <span>Module <?php echo $moduleId; ?> of <?php echo $totalModules; ?>, Group <?php echo $page; ?> of <?php echo $totalPages; ?></span>
                    <span><?php echo round((($moduleId - 1 + ($page - 1) / $totalPages) / $totalModules) * 100); ?>%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: <?php echo (($moduleId - 1 + ($page - 1) / $totalPages) / $totalModules) * 100; ?>%"></div>
                </div>
            </div>

                <form method="POST" action="<?php echo BASE_PATH; ?>/survey?module=<?php echo $moduleId; ?>" class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <input type="hidden" name="page" value="<?php echo $page; ?>">
                <div class="bg-blue-50 px-6 py-4 border-b border-blue-100">
                    <h2 class="text-xl font-bold text-blue-900"><?php echo htmlspecialchars($module['title']); ?></h2>
                    <h3 class="text-lg font-semibold text-blue-800 mt-2"><?php echo htmlspecialchars($module['group']); ?></h3>
                    <p class="text-sm text-blue-700 mt-1">Please rate the following statements on a scale from Strongly Disagree to Strongly Agree.</p>
                </div>

                <div class="p-6 space-y-6">
                    <?php foreach ($module['questions'] as $id => $text): ?>
                        <div class="pb-4 border-b border-gray-100 last:border-0">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                <p class="font-medium text-gray-800 mb-3 md:mb-0 md:flex-1 md:pr-4"><span
                                        class="text-gray-400 text-xs mr-2"><?php echo htmlspecialchars($id); ?></span><?php echo htmlspecialchars($text); ?></p>

                                <div class="flex flex-col md:flex-row md:space-x-4 md:space-y-0 space-y-2">
                                    <?php
                                    $labels = ['Strongly Disagree', 'Disagree', 'Neutral', 'Agree', 'Strongly Agree'];
                                    ?>
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <label class="flex items-center cursor-pointer group">
                                            <input type="radio" name="<?php echo htmlspecialchars($id); ?>" value="<?php echo $i; ?>"
                                                class="peer sr-only">
                                            <div
                                                class="w-5 h-5 rounded border border-gray-300 flex items-center justify-center peer-checked:bg-blue-600 peer-checked:border-blue-600 transition-all group-hover:border-blue-400">
                                                <i class="fas fa-check text-white text-xs peer-checked:block hidden"></i>
                                            </div>
                                            <span class="ml-2 text-sm text-gray-700 peer-checked:text-blue-600"><?php echo $labels[$i-1]; ?></span>
                                        </label>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex justify-between items-center">
                    <div class="flex space-x-2">
                        <?php if ($moduleId > 1): ?>
                            <a href="<?php echo BASE_PATH; ?>/survey?module=<?php echo $moduleId - 1; ?>" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded">Previous Module</a>
                        <?php endif; ?>
                        <?php if ($page > 1): ?>
                            <a href="<?php echo BASE_PATH; ?>/survey?module=<?php echo $moduleId; ?>&page=<?php echo $page - 1; ?>" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded">Previous Group</a>
                        <?php endif; ?>
                    </div>

                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded shadow-sm transition-colors">
                        <?php echo $page == $totalPages ? ($moduleId == $totalModules ? 'Submit Survey' : 'Next Module') : 'Next Group'; ?>
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>

</html>