<?php
$page_title = 'Edit Question';
$user = get_authenticated_user();
$content = '
    <div class="bg-white rounded-xl shadow-2xl border border-gray-200 p-10">
        <div class="mb-8">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-2">Edit Question</h1>
            <p class="text-gray-600 text-lg">Update your question details</p>
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
                           value="' . htmlspecialchars($question['module']) . '"
                           class="block w-full bg-gray-50 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-4 py-3 text-base transition-all">
                </div>

                <div>
                    <label for="code" class="block text-base font-semibold text-gray-800 mb-3">Question Code *</label>
                    <input type="text" name="code" id="code" required
                           value="' . htmlspecialchars($question['code']) . '"
                           class="block w-full bg-gray-50 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-4 py-3 text-base transition-all">
                    <p class="text-sm text-gray-500 mt-2">Unique identifier for this question</p>
                </div>
            </div>

            <div>
                <label for="group" class="block text-base font-semibold text-gray-800 mb-3">Group (Optional)</label>
                <input type="text" name="group" id="group"
                       value="' . htmlspecialchars($question['group'] ?? '') . '"
                       class="block w-full bg-gray-50 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-4 py-3 text-base transition-all">
                <p class="text-sm text-gray-500 mt-2">Optional grouping for organizing questions</p>
            </div>

             <div>
                 <label for="text" class="block text-base font-semibold text-gray-800 mb-3">Question Text *</label>
                 <textarea name="text" id="text" rows="5" required
                           class="block w-full bg-gray-50 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-4 py-3 text-base transition-all resize-vertical">' . htmlspecialchars($question['text']) . '</textarea>
             </div>

             <div>
                 <label for="question_type" class="block text-base font-semibold text-gray-800 mb-3">Question Type *</label>
                 <select name="type" id="question_type" required
                         class="block w-full bg-gray-50 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-4 py-3 text-base transition-all">
                     <option value="scale" ' . (($question['type'] ?? 'scale') === 'scale' ? 'selected' : '') . '>Scale (1-5)</option>
                     <option value="multiple_choice" ' . (($question['type'] ?? '') === 'multiple_choice' ? 'selected' : '') . '>Multiple Choice</option>
                 </select>
                 <p class="text-sm text-gray-500 mt-2">Select the type of question</p>
             </div>

             <div id="options_section" class="transition-all duration-300" style="display: none;">
                 <label for="options" class="block text-base font-semibold text-gray-800 mb-3">Options *</label>
                 <textarea name="options" id="options" rows="5"
                           class="block w-full bg-gray-50 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-4 py-3 text-base transition-all resize-vertical">' . htmlspecialchars($_POST['options'] ?? implode("\n", array_column($question['options'] ?? [], 'option_text'))) . '</textarea>
                 <p class="text-sm text-gray-500 mt-2">One option per line</p>
             </div>

             <div class="flex justify-end space-x-4 pt-8">
                <a href="' . BASE_PATH . '/questions" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-semibold text-base transition-all duration-200 shadow-sm hover:shadow-md">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold text-base transition-all duration-200 shadow-sm hover:shadow-md">
                    Update Question
                </button>
            </div>
        </form>
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