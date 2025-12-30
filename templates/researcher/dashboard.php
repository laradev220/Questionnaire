<?php
$page_title = 'Researcher Dashboard';
$user = $user ?? get_authenticated_user();

// Get comprehensive stats
$db = get_db_connection();
$stats = [];

// Total surveys
$stmt = $db->prepare("SELECT COUNT(*) as count FROM surveys WHERE user_id = ?");
$stmt->execute([$user['id']]);
$stats['total_surveys'] = $stmt->fetch()['count'];

// Active surveys
$stmt = $db->prepare("SELECT COUNT(*) as count FROM surveys WHERE user_id = ? AND is_active = 1");
$stmt->execute([$user['id']]);
$stats['active_surveys'] = $stmt->fetch()['count'];

// Total participants
$stmt = $db->prepare("SELECT COUNT(DISTINCT p.id) as count FROM participants p JOIN survey_sessions ss ON p.id = ss.participant_id JOIN surveys s ON ss.survey_id = s.id WHERE s.user_id = ?");
$stmt->execute([$user['id']]);
$stats['total_participants'] = $stmt->fetch()['count'];

// Total responses
$stmt = $db->prepare("SELECT COUNT(r.id) as count FROM responses r JOIN survey_sessions ss ON r.session_id = ss.id JOIN surveys s ON ss.survey_id = s.id WHERE s.user_id = ?");
$stmt->execute([$user['id']]);
$stats['total_responses'] = $stmt->fetch()['count'];

// Total questions
$stmt = $db->prepare("SELECT COUNT(*) as count FROM questions WHERE user_id = ?");
$stmt->execute([$user['id']]);
$stats['total_questions'] = $stmt->fetch()['count'];

// Average completion rate
$stats['avg_completion_rate'] = $stats['total_participants'] > 0 ? round(($stats['total_responses'] / $stats['total_participants']) * 100, 1) : 0;

// Recent activity (surveys created in last 30 days)
$stmt = $db->prepare("SELECT COUNT(*) as count FROM surveys WHERE user_id = ? AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)");
$stmt->execute([$user['id']]);
$stats['recent_surveys'] = $stmt->fetch()['count'];

$content = '
    <!-- Overall Stats Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-poll text-blue-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-3xl font-bold text-gray-900">' . $stats['total_surveys'] . '</h3>
                    <p class="text-gray-600 text-sm">Total Surveys</p>
                    <p class="text-xs text-blue-600 font-medium">' . $stats['active_surveys'] . ' active</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-users text-green-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-3xl font-bold text-gray-900">' . $stats['total_participants'] . '</h3>
                    <p class="text-gray-600 text-sm">Total Participants</p>
                    <p class="text-xs text-green-600 font-medium">' . $stats['avg_completion_rate'] . '% avg completion</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i class="fas fa-chart-line text-purple-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-3xl font-bold text-gray-900">' . $stats['total_responses'] . '</h3>
                    <p class="text-gray-600 text-sm">Total Responses</p>
                    <p class="text-xs text-purple-600 font-medium">' . $stats['recent_surveys'] . ' created this month</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-orange-100 rounded-lg">
                    <i class="fas fa-question-circle text-orange-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-3xl font-bold text-gray-900">' . $stats['total_questions'] . '</h3>
                    <p class="text-gray-600 text-sm">My Questions</p>
                    <p class="text-xs text-orange-600 font-medium">Reusable assets</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <a href="' . BASE_PATH . '/surveys/create"
                class="flex flex-col items-center justify-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg border-2 border-dashed border-blue-200 hover:border-blue-300 transition-all duration-200 group">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-2 group-hover:bg-blue-200 transition-colors">
                    <i class="fas fa-plus text-blue-600 text-xl"></i>
                </div>
                <span class="text-sm font-medium text-blue-700 text-center">Create Survey</span>
            </a>

            <a href="' . BASE_PATH . '/questions/create"
                class="flex flex-col items-center justify-center p-4 bg-green-50 hover:bg-green-100 rounded-lg border-2 border-dashed border-green-200 hover:border-green-300 transition-all duration-200 group">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-2 group-hover:bg-green-200 transition-colors">
                    <i class="fas fa-question-circle text-green-600 text-xl"></i>
                </div>
                <span class="text-sm font-medium text-green-700 text-center">Add Question</span>
            </a>

            <a href="' . BASE_PATH . '/questions"
                class="flex flex-col items-center justify-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg border-2 border-dashed border-purple-200 hover:border-purple-300 transition-all duration-200 group">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mb-2 group-hover:bg-purple-200 transition-colors">
                    <i class="fas fa-list text-purple-600 text-xl"></i>
                </div>
                <span class="text-sm font-medium text-purple-700 text-center">Question Bank</span>
            </a>

            <a href="' . BASE_PATH . '/profile"
                class="flex flex-col items-center justify-center p-4 bg-indigo-50 hover:bg-indigo-100 rounded-lg border-2 border-dashed border-indigo-200 hover:border-indigo-300 transition-all duration-200 group">
                <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mb-2 group-hover:bg-indigo-200 transition-colors">
                    <i class="fas fa-user text-indigo-600 text-xl"></i>
                </div>
                <span class="text-sm font-medium text-indigo-700 text-center">My Profile</span>
            </a>

            <a href="' . BASE_PATH . '/dashboard?tab=settings"
                class="flex flex-col items-center justify-center p-4 bg-gray-50 hover:bg-gray-100 rounded-lg border-2 border-dashed border-gray-200 hover:border-gray-300 transition-all duration-200 group">
                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-2 group-hover:bg-gray-200 transition-colors">
                    <i class="fas fa-cog text-gray-600 text-xl"></i>
                </div>
                <span class="text-sm font-medium text-gray-700 text-center">Settings</span>
            </a>
        </div>
    </div>

    <!-- Surveys Section -->
    <div class="bg-white rounded-xl shadow-xl border border-gray-100 p-8 mb-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900">Your Surveys</h1>
                <p class="text-gray-600 mt-2">Manage and monitor your research surveys</p>
            </div>
        </div>';

if (empty($surveys)) {
    $content .= '
        <div class="text-center py-12">
            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                <i class="fas fa-poll text-3xl text-gray-400"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-4">No surveys yet</h2>
            <p class="text-gray-600 mb-6">Create your first survey to start collecting responses from participants.</p>
            <a href="' . BASE_PATH . '/surveys/create"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold inline-flex items-center">
                <i class="fas fa-plus mr-2"></i>Create Your First Survey
            </a>
        </div>';
} else {
    $content .= '<div class="grid grid-cols-1 md:grid-cols-2 gap-6">';

    foreach ($surveys as $survey) {
        $statusClass = $survey['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
        $statusText = $survey['is_active'] ? 'Active' : 'Inactive';

        $content .= '
            <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 leading-tight">' . htmlspecialchars($survey['title']) . '</h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' . $statusClass . '">
                        ' . $statusText . '
                    </span>
                </div>';

        if ($survey['description']) {
            $content .= '<p class="text-gray-600 text-sm mb-4">' . htmlspecialchars(substr($survey['description'], 0, 120)) . (strlen($survey['description']) > 120 ? '...' : '') . '</p>';
        }

        if ($survey['deadline']) {
            $content .= '<div class="text-sm text-orange-600 mb-4">
                <i class="fas fa-calendar-alt mr-1"></i>Deadline: ' . date('M j, Y', strtotime($survey['deadline'])) . '
            </div>';
        }

        $content .= '<div class="grid grid-cols-3 gap-4 mb-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">' . $survey['participant_count'] . '</div>
                    <div class="text-xs text-gray-500">Participants</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">' . $survey['completion_rate'] . '%</div>
                    <div class="text-xs text-gray-500">Completed</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-purple-600">' . $survey['response_count'] . '</div>
                    <div class="text-xs text-gray-500">Responses</div>
                </div>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="' . BASE_PATH . '/surveys/' . $survey['id'] . '/analytics"
                    class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 px-3 py-2 rounded text-sm font-medium text-center transition-colors">
                    <i class="fas fa-chart-bar mr-1"></i>Analytics
                </a>
                <a href="' . BASE_PATH . '/surveys/' . $survey['id'] . '/link"
                    class="flex-1 bg-blue-100 hover:bg-blue-200 text-blue-800 px-3 py-2 rounded text-sm font-medium text-center transition-colors">
                    <i class="fas fa-link mr-1"></i>Share
                </a>
                <a href="' . BASE_PATH . '/surveys/' . $survey['id'] . '/questions"
                    class="flex-1 bg-purple-100 hover:bg-purple-200 text-purple-800 px-3 py-2 rounded text-sm font-medium text-center transition-colors">
                    <i class="fas fa-question-circle mr-1"></i>Questions
                </a>
                <a href="' . BASE_PATH . '/surveys/' . $survey['id'] . '/edit"
                    class="flex-1 bg-yellow-100 hover:bg-yellow-200 text-yellow-800 px-3 py-2 rounded text-sm font-medium text-center transition-colors">
                    <i class="fas fa-edit mr-1"></i>Edit
                </a>
                <a href="' . BASE_PATH . '/surveys/' . $survey['id'] . '/delete"
                    class="flex-1 bg-red-100 hover:bg-red-200 text-red-800 px-3 py-2 rounded text-sm font-medium text-center transition-colors"
                    onclick="return confirm(\'Are you sure you want to delete this survey?\')">
                    <i class="fas fa-trash mr-1"></i>Delete
                </a>
            </div>
        </div>';
    }

    $content .= '</div>';
}

$content .= '
    </div>
';

include __DIR__ . '/../layouts/researcher.php';
?>