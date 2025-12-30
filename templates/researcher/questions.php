<?php
$page_title = 'My Questions';
$user = get_authenticated_user();
$content = '
    <div class="bg-white rounded-xl shadow-xl border border-gray-100 p-8 mb-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900">My Questions</h1>
                <p class="text-gray-600 mt-2">Manage your survey questions</p>
            </div>
            <a href="' . BASE_PATH . '/questions/create"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold flex items-center">
                <i class="fas fa-plus mr-2"></i>Create Question
            </a>
        </div>';

if (isset($error)) {
    $content .= '
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            ' . htmlspecialchars($error) . '
        </div>';
}

if (empty($questions)) {
    $content .= '
        <div class="text-center py-12">
            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                <i class="fas fa-question-circle text-3xl text-gray-400"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-4">No questions yet</h2>
            <p class="text-gray-600 mb-6">Create your first question to start building surveys.</p>
            <a href="' . BASE_PATH . '/questions/create"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold inline-flex items-center">
                <i class="fas fa-plus mr-2"></i>Create Your First Question
            </a>
        </div>';
} else {
    $content .= '<div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Module</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Question</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Group</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">';

    foreach ($questions as $question) {
        $content .= '
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">' . htmlspecialchars($question['module']) . '</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                ' . htmlspecialchars($question['code']) . '
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 max-w-md">
                            <div class="truncate">' . htmlspecialchars($question['text']) . '</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">' . htmlspecialchars(ucfirst($question['type'] ?? 'scale')) . '</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">' . htmlspecialchars($question['group'] ?? '-') . '</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="' . BASE_PATH . '/questions/' . $question['id'] . '/edit" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                            <a href="' . BASE_PATH . '/questions/' . $question['id'] . '/delete" class="text-red-600 hover:text-red-900"
                               onclick="return confirm(\'Are you sure you want to delete this question?\')">Delete</a>
                        </td>
                    </tr>';
    }

    $content .= '
                </tbody>
            </table>
        </div>';
}

$content .= '
    </div>
';

include __DIR__ . '/../layouts/researcher.php';
?>