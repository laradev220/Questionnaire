<?php
$page_title = 'Participant Responses';
$user = get_authenticated_user();
$content = '';

if (isset($_GET['updated'])) {
    $content .= '
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <i class="fas fa-check-circle mr-2"></i>Participant responses updated successfully!
        </div>';
}

if (isset($_GET['error'])) {
    $content .= '
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <i class="fas fa-exclamation-triangle mr-2"></i>Failed to update participant responses. Please try again.
        </div>';
}

$content .= '
    <div class="bg-white rounded-xl shadow-xl border border-gray-100 p-8 mb-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900">Participant Responses</h1>
                <p class="text-gray-600 mt-2">' . htmlspecialchars($participant['name']) . ' - ' . htmlspecialchars($survey['title']) . '</p>
            </div>
            <div class="flex space-x-3">
                <a href="' . BASE_PATH . '/surveys/' . $survey['id'] . '/participants"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Participants
                </a>
                <a href="' . BASE_PATH . '/surveys/' . $survey['id'] . '/analytics"
                    class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-4 py-2 rounded-lg font-medium transition-colors">
                    <i class="fas fa-chart-bar mr-2"></i>Back to Analytics
                </a>
            </div>
        </div>

        <!-- Participant Info -->
        <div class="bg-gray-50 rounded-lg p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Name</h3>
                    <p class="text-lg font-semibold text-gray-900">' . htmlspecialchars($participant['name']) . '</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Email</h3>
                    <p class="text-lg font-semibold text-gray-900">' . htmlspecialchars($participant['email']) . '</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">University</h3>
                    <p class="text-lg font-semibold text-gray-900">' . htmlspecialchars($participant['university']) . '</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Status</h3>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ' .
                        ($session['is_completed'] ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800') . '">
                        <i class="fas ' . ($session['is_completed'] ? 'fa-check-circle' : 'fa-clock') . ' mr-1"></i>
                        ' . ($session['is_completed'] ? 'Completed' : 'In Progress') . '
                    </span>
                </div>
            </div>
        </div>
    </div>';

if (empty($responses)) {
    $content .= '
        <div class="bg-white rounded-xl shadow-xl border border-gray-100 p-8">
            <div class="text-center py-12">
                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-question-circle text-3xl text-gray-400"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">No responses yet</h2>
                <p class="text-gray-600">This participant hasn\'t provided any responses yet.</p>
            </div>
        </div>';
} else {
    $content .= '<div class="bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Responses (' . count($responses) . ')</h2>
                    <p class="text-gray-600 text-sm mt-1">All responses from this participant</p>
                </div>
                <button onclick="openEditModal()" id="editButton"
                    class="bg-orange-100 hover:bg-orange-200 text-orange-700 px-4 py-2 rounded-lg font-medium transition-colors">
                    <i class="fas fa-edit mr-2"></i>Edit Responses
                </button>
            </div>
            <div class="divide-y divide-gray-200">';

    foreach ($responses as $response) {
        $responseValue = $response['score'];
        $displayValue = '';

        if ($response['type'] === 'scale') {
            $displayValue = $responseValue ? $responseValue . '/5' : 'Not answered';
        } elseif ($response['type'] === 'multiple_choice') {
            $displayValue = $response['option_text'] ?: ($responseValue ? 'Option ' . $responseValue : 'Not answered');
        }

        $content .= '
                    <div class="px-6 py-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        ' . htmlspecialchars($response['code']) . '
                                    </span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ' .
                                        ($response['type'] === 'scale' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800') . '">
                                        <i class="fas ' . ($response['type'] === 'scale' ? 'fa-star' : 'fa-list') . ' mr-1"></i>
                                        ' . ucfirst(str_replace('_', ' ', $response['type'])) . '
                                    </span>
                                </div>
                                <p class="text-gray-900 font-medium mb-3">' . htmlspecialchars($response['text']) . '</p>
                            </div>
                            <div class="ml-6 flex-shrink-0">
                                <div id="display-' . $response['id'] . '" class="text-right">
                                    <div class="text-lg font-semibold text-gray-900">' . htmlspecialchars($displayValue) . '</div>
                                    <div class="text-sm text-gray-500">Response</div>
                                </div>
                                <div id="edit-' . $response['id'] . '" class="hidden text-right">';

        if ($response['type'] === 'scale') {
            $content .= '<select name="responses[' . $response['id'] . ']" class="block w-20 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">';
            $content .= '<option value="">Not answered</option>';
            for ($i = 1; $i <= 5; $i++) {
                $selected = ($responseValue == $i) ? 'selected' : '';
                $content .= '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
            }
            $content .= '</select>';
        } elseif ($response['type'] === 'multiple_choice') {
            // Get all options for this question
            $db = get_db_connection();
            $stmt = $db->prepare("SELECT option_text, option_value FROM question_options WHERE question_id = ? ORDER BY order_index");
            $stmt->execute([$response['id']]);
            $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $content .= '<select name="responses[' . $response['id'] . ']" class="block w-40 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">';
            $content .= '<option value="">Not answered</option>';
            foreach ($options as $option) {
                $selected = ($responseValue == $option['option_value']) ? 'selected' : '';
                $content .= '<option value="' . $option['option_value'] . '" ' . $selected . '>' . htmlspecialchars($option['option_text']) . '</option>';
            }
            $content .= '</select>';
        }

        $content .= '
                                </div>
                            </div>
                        </div>
                    </div>';
    }

    $content .= '</div>
            <!-- Edit Modal -->
            <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
                <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Edit Participant Responses</h3>
                        <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <form id="responsesForm" method="POST" action="' . BASE_PATH . '/surveys/' . $survey['id'] . '/participants/' . $participant['id'] . '/update">
                        <div class="max-h-96 overflow-y-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50 sticky top-0">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Question</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Response</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">New Response</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">';

    foreach ($responses as $response) {
        $responseValue = $response['score'];
        $displayValue = '';

        if ($response['type'] === 'scale') {
            $displayValue = $responseValue ? $responseValue . '/5' : 'Not answered';
        } elseif ($response['type'] === 'multiple_choice') {
            $displayValue = $response['option_text'] ?: ($responseValue ? 'Option ' . $responseValue : 'Not answered');
        }

        $content .= '
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-2 mb-1">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    ' . htmlspecialchars($response['code']) . '
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-900">' . htmlspecialchars($response['text']) . '</p>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ' .
                                                ($response['type'] === 'scale' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800') . '">
                                                <i class="fas ' . ($response['type'] === 'scale' ? 'fa-star' : 'fa-list') . ' mr-1"></i>
                                                ' . ucfirst(str_replace('_', ' ', $response['type'])) . '
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            ' . htmlspecialchars($displayValue) . '
                                        </td>
                                        <td class="px-6 py-4">';

        if ($response['type'] === 'scale') {
            $content .= '<select name="responses[' . $response['id'] . ']" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">';
            $content .= '<option value="">Not answered</option>';
            for ($i = 1; $i <= 5; $i++) {
                $selected = ($responseValue == $i) ? 'selected' : '';
                $content .= '<option value="' . $i . '" ' . $selected . '>' . $i . ' - ';

                // Add descriptive text for scale values
                $labels = ['Strongly Disagree', 'Disagree', 'Neutral', 'Agree', 'Strongly Agree'];
                $content .= $labels[$i-1] . '</option>';
            }
            $content .= '</select>';
        } elseif ($response['type'] === 'multiple_choice') {
            // Get all options for this question
            $db = get_db_connection();
            $stmt = $db->prepare("SELECT option_text, option_value FROM question_options WHERE question_id = ? ORDER BY order_index");
            $stmt->execute([$response['id']]);
            $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $content .= '<select name="responses[' . $response['id'] . ']" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">';
            $content .= '<option value="">Not answered</option>';
            foreach ($options as $option) {
                $selected = ($responseValue == $option['option_value']) ? 'selected' : '';
                $content .= '<option value="' . $option['option_value'] . '" ' . $selected . '>' . htmlspecialchars($option['option_text']) . '</option>';
            }
            $content .= '</select>';
        }

        $content .= '
                                        </td>
                                    </tr>';
    }

    $content .= '
                                </tbody>
                            </table>
                        </div>

                        <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200 bg-gray-50 -mx-5 -mb-5 px-5 py-4 rounded-b-md">
                            <button type="button" onclick="closeEditModal()"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg font-medium transition-colors">
                                Cancel
                            </button>
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-2 rounded-lg font-medium transition-colors">
                                <i class="fas fa-save mr-2"></i>Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>';
}

$content .= '
    <script>
        function openEditModal() {
            document.getElementById(\'editModal\').classList.remove(\'hidden\');
            document.body.style.overflow = \'hidden\'; // Prevent background scrolling
        }

        function closeEditModal() {
            document.getElementById(\'editModal\').classList.add(\'hidden\');
            document.body.style.overflow = \'auto\'; // Restore scrolling
        }

        // Close modal when clicking outside
        document.getElementById(\'editModal\').addEventListener(\'click\', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });

        // Close modal on escape key
        document.addEventListener(\'keydown\', function(e) {
            if (e.key === \'Escape\' && !document.getElementById(\'editModal\').classList.contains(\'hidden\')) {
                closeEditModal();
            }
        });
    </script>
';
?>
    <style>
        /* Enhanced tooltip styles */
        [title]:hover::after {
            content: attr(title);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            white-space: nowrap;
            z-index: 1000;
            margin-bottom: 5px;
        }

        [title]:hover::before {
            content: "";
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            border: 5px solid transparent;
            border-top-color: rgba(0, 0, 0, 0.8);
            margin-bottom: -5px;
            z-index: 1000;
        }

        /* Modal improvements */
        #editModal {
            backdrop-filter: blur(2px);
        }

        #editModal .overflow-y-auto::-webkit-scrollbar {
            width: 8px;
        }

        #editModal .overflow-y-auto::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }

        #editModal .overflow-y-auto::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        #editModal .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Table styling improvements */
        #editModal table {
            border-collapse: separate;
            border-spacing: 0;
        }

        #editModal th {
            position: sticky;
            top: 0;
            background: #f9fafb;
            z-index: 10;
        }

        /* Mobile responsiveness improvements */
        @media (max-width: 640px) {
            .grid-cols-2 {
                grid-template-columns: 1fr;
            }

            .flex-col {
                align-items: stretch;
            }

            .space-x-8 > * + * {
                margin-left: 1rem;
            }

            #editModal .relative {
                top: 10px;
                margin: 10px;
                width: calc(100% - 20px);
                max-width: none;
            }

            #editModal .max-h-96 {
                max-height: 60vh;
            }
        }

        /* Progress bar animation */
        @keyframes progress-fill {
            from { width: 0%; }
            to { width: var(--progress-width); }
        }

        .progress-bar {
            animation: progress-fill 1s ease-out forwards;
        }
    </style>
<?php
include __DIR__ . '/../layouts/researcher.php';
?>
?>