<?php
$page_title = 'Survey Participants';
$user = get_authenticated_user();
$content = '
    <div class="bg-white rounded-xl shadow-xl border border-gray-100 p-8 mb-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900">Survey Participants</h1>
                <p class="text-gray-600 mt-2">' . htmlspecialchars($survey['title']) . '</p>
            </div>
            <a href="' . BASE_PATH . '/surveys/' . $survey['id'] . '/analytics"
                class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to Analytics
            </a>
        </div>
    </div>';

if (empty($participants)) {
    $content .= '
        <div class="bg-white rounded-xl shadow-xl border border-gray-100 p-8">
            <div class="text-center py-12">
                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-users text-3xl text-gray-400"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">No participants yet</h2>
                <p class="text-gray-600 mb-6">Participants will appear here once they start taking the survey.</p>
                <a href="' . BASE_PATH . '/surveys/' . $survey['id'] . '/link"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold inline-flex items-center">
                    <i class="fas fa-share mr-2"></i>Share Survey Link
                </a>
            </div>
        </div>';
} else {
    $content .= '<div class="bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Participants (' . count($participants) . ')</h2>
                <p class="text-gray-600 text-sm mt-1">Click on a participant to view their responses</p>
            </div>
            <div class="divide-y divide-gray-200">';

    foreach ($participants as $participant) {
        $statusClass = $participant['is_completed'] ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800';
        $statusText = $participant['is_completed'] ? 'Completed' : 'In Progress';

        $content .= '
                <div class="px-6 py-6 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-blue-600"></i>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-semibold text-gray-900 truncate">' . htmlspecialchars($participant['name']) . '</h3>
                                    <p class="text-sm text-gray-600">' . htmlspecialchars($participant['email']) . '</p>
                                    <div class="flex items-center space-x-4 mt-1">
                                        <span class="text-xs text-gray-500">
                                            <i class="fas fa-building mr-1"></i>' . htmlspecialchars($participant['university']) . '
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            <i class="fas fa-briefcase mr-1"></i>' . htmlspecialchars($participant['designation']) . '
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-6">
                            <div class="text-center">
                                <div class="text-sm font-medium text-gray-900">' . $participant['response_count'] . '</div>
                                <div class="text-xs text-gray-500">Responses</div>
                            </div>
                             <div class="text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' . $statusClass . '">
                                    ' . $statusText . '
                                </span>
                                <div class="text-xs text-gray-500 mt-1">
                                    ' . date('M j, Y', strtotime($participant['started_at'])) . '
                                </div>
                            </div>
                            <a href="' . BASE_PATH . '/surveys/' . $survey['id'] . '/participants/' . $participant['id'] . '"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <i class="fas fa-eye mr-2"></i>View Responses
                            </a>
                        </div>
                    </div>
                </div>';
    }

    $content .= '</div></div>';
}

$content .= '
';

include __DIR__ . '/../layouts/researcher.php';
?>