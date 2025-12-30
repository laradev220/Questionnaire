<?php
$page_title = 'Edit Survey';
$user = get_authenticated_user();
$content = '
    <div class="space-y-8">
        <!-- Survey Details Section -->
        <div class="bg-white rounded-xl shadow-xl border border-gray-100 p-8">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Survey Details</h2>
                <p class="text-gray-600 mt-1">Update basic survey information</p>
            </div>';

if (isset($error)) {
    $content .= '
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                ' . htmlspecialchars($error) . '
            </div>';
}

$content .= '
            <form method="POST" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Survey Title *</label>
                        <input type="text" name="title" id="title" required
                               value="' . htmlspecialchars($survey['title']) . '"
                               class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="deadline" class="block text-sm font-medium text-gray-700 mb-2">Response Deadline (Optional)</label>
                        <input type="date" name="deadline" id="deadline"
                               value="' . ($survey['deadline'] ? date('Y-m-d', strtotime($survey['deadline'])) : '') . '"
                               class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" id="description" rows="4"
                              class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">' . htmlspecialchars($survey['description'] ?? '') . '</textarea>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" id="is_active"
                           ' . ($survey['is_active'] ? 'checked' : '') . '
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-900">
                        Survey is active and accepting responses
                    </label>
                </div>

                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="' . BASE_PATH . '/dashboard" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded font-semibold transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-semibold transition-colors">
                        Update Survey
                    </button>
                </div>
            </form>
        </div>

        <!-- Question Assignment Section -->
        <div class="bg-white rounded-xl shadow-xl border border-gray-100 p-8">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Question Assignment</h2>
                <p class="text-gray-600 mt-1">Add or remove questions from this survey</p>
            </div>';

if (isset($assignment_error)) {
    $content .= '
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                ' . htmlspecialchars($assignment_error) . '
            </div>';
}

$content .= '
            <form method="POST" action="' . BASE_PATH . '/surveys/' . $survey['id'] . '/questions" class="space-y-6">
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Current Questions</h3>
                            <p class="text-gray-600 text-sm">Questions currently assigned to this survey</p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            ' . count($assigned_question_ids ?? []) . ' assigned
                        </span>
                    </div>

                    <div class="space-y-3">
                        <label class="block text-sm font-medium text-gray-700">Select Questions to Include:</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-96 overflow-y-auto">';

// Get researcher's questions for assignment
$db = get_db_connection();
$stmt = $db->prepare("SELECT * FROM questions WHERE user_id = ? ORDER BY module, code");
$stmt->execute([$user['id']]);
$all_questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$assigned_question_ids = $assigned_question_ids ?? [];

foreach ($all_questions as $question) {
    $isChecked = in_array($question['id'], $assigned_question_ids) ? 'checked' : '';
    $content .= '
                            <div class="flex items-start space-x-3 p-3 bg-white border border-gray-200 rounded-lg hover:bg-gray-50">
                                <input type="checkbox" name="questions[]" value="' . $question['id'] . '"
                                       class="question-checkbox mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                       ' . $isChecked . '>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                            ' . htmlspecialchars($question['code']) . '
                                        </span>
                                        <span class="text-xs text-gray-500">' . htmlspecialchars($question['module']) . '</span>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-900 line-clamp-2">' . htmlspecialchars($question['text']) . '</p>
                                </div>
                            </div>';
}

$content .= '
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="' . BASE_PATH . '/dashboard" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded font-semibold transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded font-semibold transition-colors">
                        <i class="fas fa-save mr-2"></i>Save Question Assignments
                    </button>
                </div>
            </form>
        </div>
    </div>
';

include __DIR__ . '/../layouts/researcher.php';
?>