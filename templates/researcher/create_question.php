<?php
$page_title = 'Create Question';
$user = get_authenticated_user();
$content = '
    <div class="bg-white rounded-xl shadow-2xl border border-gray-200 p-10">
        <div class="mb-8">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-2">Create New Question</h1>
            <p class="text-gray-600 text-lg">Add a new question to your question bank</p>
        </div>';

if (isset($error)) {
    $content .= '
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            ' . htmlspecialchars($error) . '
        </div>';
}

$content .= '
        <form method="POST" class="space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label for="module" class="block text-base font-semibold text-gray-800 mb-3">Module *</label>
                    <input type="text" name="module" id="module" required
                           value="' . htmlspecialchars($_POST['module'] ?? '') . '"
                           class="block w-full bg-gray-50 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-4 py-3 text-base transition-all"
                           placeholder="e.g., Demographics, Satisfaction, etc.">
                </div>

                <div>
                    <label for="code" class="block text-base font-semibold text-gray-800 mb-3">Question Code *</label>
                    <input type="text" name="code" id="code" required
                           value="' . htmlspecialchars($_POST['code'] ?? '') . '"
                           class="block w-full bg-gray-50 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-4 py-3 text-base transition-all"
                           placeholder="e.g., Q1, DEMO_AGE, etc.">
                    <p class="text-sm text-gray-500 mt-2">Unique identifier for this question</p>
                </div>
            </div>

            <div>
                <label for="group" class="block text-base font-semibold text-gray-800 mb-3">Group (Optional)</label>
                <input type="text" name="group" id="group"
                       value="' . htmlspecialchars($_POST['group'] ?? '') . '"
                       class="block w-full bg-gray-50 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-4 py-3 text-base transition-all"
                       placeholder="e.g., Personal Info, Work Experience, etc.">
                <p class="text-sm text-gray-500 mt-2">Optional grouping for organizing questions</p>
            </div>

            <div>
                 <label for="text" class="block text-base font-semibold text-gray-800 mb-3">Question Text *</label>
                 <textarea name="text" id="text" rows="5" required
                           class="block w-full bg-gray-50 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-4 py-3 text-base transition-all resize-vertical"
                           placeholder="Enter the full question text that participants will see...">' . htmlspecialchars($_POST['text'] ?? '') . '</textarea>
             </div>

             <div>
                 <label for="question_type" class="block text-base font-semibold text-gray-800 mb-3">Question Type *</label>
                 <select name="type" id="question_type" required
                         class="block w-full bg-gray-50 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-4 py-3 text-base transition-all">
                     <option value="scale" ' . (($_POST['type'] ?? 'scale') === 'scale' ? 'selected' : '') . '>Scale (1-5)</option>
                     <option value="multiple_choice" ' . (($_POST['type'] ?? '') === 'multiple_choice' ? 'selected' : '') . '>Multiple Choice</option>
                 </select>
                 <p class="text-sm text-gray-500 mt-2">Select the type of question</p>
             </div>

             <div id="options_section" class="transition-all duration-300" style="display: none;">
                 <label for="options" class="block text-base font-semibold text-gray-800 mb-3">Options *</label>
                 <textarea name="options" id="options" rows="5"
                           class="block w-full bg-gray-50 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-4 py-3 text-base transition-all resize-vertical"
                           placeholder="Enter each option on a new line (e.g., Strongly Agree\nAgree\nNeutral\nDisagree\nStrongly Disagree)">' . htmlspecialchars($_POST['options'] ?? '') . '</textarea>
                 <p class="text-sm text-gray-500 mt-2">One option per line</p>
             </div>

             <div class="flex justify-end space-x-4 pt-8">
                <a href="' . BASE_PATH . '/questions" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-semibold text-base transition-all duration-200 shadow-sm hover:shadow-md">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold text-base transition-all duration-200 shadow-sm hover:shadow-md">
                    Create Question
                </button>
            </div>
        </form>
    </div>

    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6 mt-8 shadow-sm">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-lightbulb text-blue-500 text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-blue-900 mb-2">Question Guidelines</h3>
                <div class="text-base text-blue-800">
                    <ul class="list-disc list-inside space-y-2">
                        <li>Use clear, concise language for better participant understanding</li>
                        <li>Choose unique codes for easy reference and organization</li>
                        <li>Group related questions together for logical survey flow</li>
                        <li>Questions will be available for use in your surveys immediately</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

     <script>
         document.getElementById(\'question_type\').addEventListener(\'change\', function() {
             const optionsSection = document.getElementById(\'options_section\');
             if (this.value === \'multiple_choice\') {
                 optionsSection.style.display = \'block\';
                 document.getElementById(\'options\').required = true;
             } else {
                 optionsSection.style.display = \'none\';
                 document.getElementById(\'options\').required = false;
             }
         });

         // Trigger on load
         document.getElementById(\'question_type\').dispatchEvent(new Event(\'change\'));
     </script>
 ';

 include __DIR__ . '/../layouts/researcher.php';
?>