<?php
$page_title = 'Assign Questions';
$user = get_authenticated_user();
$content = '
    <div class="bg-white rounded-xl shadow-xl border border-gray-100 p-8 mb-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Assign Questions to Survey</h1>
            <p class="text-gray-600 mt-1">Select which questions to include in your survey</p>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-blue-900">' . htmlspecialchars($survey['title']) . '</h3>';
if ($survey['description']) {
    $content .= '<p class="text-blue-700 mt-1">' . htmlspecialchars($survey['description']) . '</p>';
}
$content .= '
                </div>
                <div class="text-right">
                    <div class="text-sm text-blue-600">
                        <span class="font-medium">' . count($assigned_question_ids) . '</span> questions assigned
                    </div>
                    <div class="text-sm text-blue-600" id="selected-count">
                        0 questions selected
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="divide-y divide-gray-200">';

foreach ($questions_by_module as $module => $questions) {
    $moduleId = md5($module);
    $content .= '
                <div class="module-section">
                    <div class="bg-gray-50 px-6 py-4 cursor-pointer hover:bg-gray-100 transition-colors" onclick="toggleModule(\'' . $moduleId . '\')">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <h3 class="text-lg font-semibold text-gray-900">' . htmlspecialchars($module) . '</h3>
                                <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    ' . count($questions) . ' questions
                                </span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <button type="button" onclick="event.stopPropagation(); selectAll(\'' . $moduleId . '\')"
                                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm font-medium transition-colors">
                                    Toggle All
                                </button>
                                <svg id="chevron-' . $moduleId . '" class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="question-list hidden" id="questions-' . $moduleId . '">
                        <div class="divide-y divide-gray-100">';

    foreach ($questions as $question) {
        $isChecked = in_array($question['id'], $assigned_question_ids) ? 'checked' : '';
        $content .= '
                            <div class="px-6 py-4 hover:bg-gray-50">
                                <div class="flex items-start space-x-3">
                                    <input type="checkbox" name="questions[]" value="' . $question['id'] . '"
                                           class="question-checkbox mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                           ' . $isChecked . '>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                ' . htmlspecialchars($question['code']) . '
                                            </span>';
        if ($question['group']) {
            $content .= '
                                            <span class="text-sm text-gray-500">• ' . htmlspecialchars($question['group']) . '</span>';
        }
        $content .= '
                                        </div>
                                        <p class="mt-2 text-sm text-gray-900">' . htmlspecialchars($question['text']) . '</p>
                                    </div>
                                </div>
                            </div>';
    }

    $content .= '
                        </div>
                    </div>
                </div>';
}

$content .= '
            </div>

            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        <span id="selected-display" class="font-medium">0 questions selected</span>
                        <span class="text-gray-500 ml-2">• Click module headers to expand/collapse</span>
                    </div>
                    <div class="flex space-x-3">
                        <a href="' . BASE_PATH . '/dashboard" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded font-semibold transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-semibold transition-colors">
                            Save Assignments
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function toggleModule(moduleId) {
            const questionList = document.getElementById(\'questions-\' + moduleId);
            const chevron = document.getElementById(\'chevron-\' + moduleId);

            if (questionList.classList.contains(\'hidden\')) {
                questionList.classList.remove(\'hidden\');
                chevron.style.transform = \'rotate(180deg)\';
            } else {
                questionList.classList.add(\'hidden\');
                chevron.style.transform = \'rotate(0deg)\';
            }
        }

        function selectAll(moduleId) {
            const container = document.getElementById(\'questions-\' + moduleId);
            const checkboxes = container.querySelectorAll(\'input[name="questions[]"]\');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);

            checkboxes.forEach(cb => cb.checked = !allChecked);
            updateSelectedCount();
        }

        function updateSelectedCount() {
            const checkboxes = document.querySelectorAll(\'input[name="questions[]"]:checked\');
            const count = checkboxes.length;
            const displayText = count + \' question\' + (count !== 1 ? \'s\' : \'\') + \' selected\';

            document.getElementById(\'selected-count\').textContent = displayText;
            document.getElementById(\'selected-display\').textContent = displayText;
        }

        // Initialize
        document.addEventListener(\'DOMContentLoaded\', function() {
            updateSelectedCount();

            // Add change listeners to checkboxes
            document.querySelectorAll(\'input[name="questions[]"]\').forEach(cb => {
                cb.addEventListener(\'change\', updateSelectedCount);
            });
        });
    </script>
';

include __DIR__ . '/../layouts/researcher.php';
?>