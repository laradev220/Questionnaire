<?php
$page_title = 'Create Survey';
$user = get_authenticated_user();
$content = '
    <div class="bg-white rounded-xl shadow-xl border border-gray-100 p-8">
        <div class="mb-6">
            <h1 class="text-3xl font-extrabold text-gray-900">Create New Survey</h1>
            <p class="text-gray-600 mt-2">Set up a new survey to collect responses from participants</p>
        </div>';

if (isset($error)) {
    $content .= '
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            ' . htmlspecialchars($error) . '
        </div>';
}

$content .= '
        <form method="POST" class="space-y-6">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Survey Title *</label>
                <input type="text" name="title" id="title" required
                       value="' . htmlspecialchars($_POST['title'] ?? '') . '"
                       class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                       placeholder="Enter a descriptive title for your survey">
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" id="description" rows="4"
                          class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                          placeholder="Provide additional context or instructions for participants">' . htmlspecialchars($_POST['description'] ?? '') . '</textarea>
            </div>

            <div>
                <label for="deadline" class="block text-sm font-medium text-gray-700 mb-2">Response Deadline (Optional)</label>
                <div class="max-w-md">
                    <input type="date" name="deadline" id="deadline"
                           value="' . htmlspecialchars($_POST['deadline'] ?? '') . '"
                           min="' . date('Y-m-d') . '"
                           class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm cursor-pointer">
                </div>
                <p class="text-sm text-gray-500 mt-1">Participants will not be able to start the survey after this date. Leave empty for no deadline.</p>
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="is_active" value="1" id="is_active"
                       ' . ((isset($_POST['is_active']) && $_POST['is_active']) || !isset($_POST['is_active']) ? 'checked' : '') . '
                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                    Activate survey immediately
                </label>
            </div>
            <p class="text-sm text-gray-500">Uncheck to create the survey as inactive. You can activate it later from the survey settings.</p>

            <div class="flex justify-end space-x-3 pt-6">
                <a href="' . BASE_PATH . '/dashboard" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded font-semibold">Cancel</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-semibold">
                    Create Survey
                </button>
            </div>
        </form>
    </div>

    <script>
        // Make the entire deadline container clickable to open date picker
        document.addEventListener(\'DOMContentLoaded\', function() {
            const deadlineInput = document.getElementById(\'deadline\');
            const deadlineContainer = deadlineInput.parentElement;

            deadlineContainer.addEventListener(\'click\', function(e) {
                if (e.target !== deadlineInput) {
                    deadlineInput.focus();
                    deadlineInput.showPicker && deadlineInput.showPicker();
                }
            });
        });
    </script>
';

include __DIR__ . '/../layouts/researcher.php';
?>