<?php
$page_title = 'Delete Survey';
$user = get_authenticated_user();
$content = '
    <div class="bg-white rounded-xl shadow-xl border border-gray-100 p-8">
        <div class="mb-6">
            <h1 class="text-3xl font-extrabold text-gray-900">Delete Survey</h1>
            <p class="text-gray-600 mt-2">Confirm survey deletion</p>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">
                        Warning: This survey contains participant data
                    </h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>The survey "<strong>' . htmlspecialchars($survey['title']) . '</strong>" has <strong>' . $participant_count . '</strong> participant(s) who have started or completed the survey.</p>
                        <p class="mt-2">Deleting this survey will permanently remove:</p>
                        <ul class="list-disc list-inside mt-1 ml-4">
                            <li>All participant responses and data</li>
                            <li>Survey questions and configuration</li>
                            <li>Survey analytics and reports</li>
                        </ul>
                        <p class="mt-2 font-medium">This action cannot be undone.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-4">
            <a href="' . BASE_PATH . '/surveys/' . $survey_id . '/analytics"
               class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-6 py-3 rounded-lg font-semibold transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Cancel
            </a>
            <a href="' . BASE_PATH . '/surveys/' . $survey_id . '/delete?confirm=1"
               class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors"
               onclick="return confirm(\'Are you absolutely sure you want to delete this survey? This action cannot be undone.\')">
                <i class="fas fa-trash mr-2"></i>Yes, Delete Survey
            </a>
        </div>
    </div>';

include __DIR__ . '/../layouts/researcher.php';
?>