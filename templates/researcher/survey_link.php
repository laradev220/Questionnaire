<?php
$page_title = 'Survey Link';
$user = get_authenticated_user();
$content = '
    <div class="bg-white rounded-xl shadow-xl border border-gray-100 p-8">
        <div class="text-center mb-8">
            <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-check text-green-600 text-2xl"></i>
            </div>
            <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Survey Created Successfully!</h1>
            <p class="text-gray-600">Your survey is ready to share with participants</p>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-blue-900">' . htmlspecialchars($survey['title']) . '</h3>';
if ($survey['description']) {
    $content .= '<p class="text-blue-700 mt-1">' . htmlspecialchars($survey['description']) . '</p>';
}
$content .= '
                </div>
                <div class="text-right">
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        Link Generated
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <label class="block text-sm font-medium text-gray-700">Survey Link</label>
                <button onclick="copyToClipboard()"
                        class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                        id="copy-btn">
                    <i class="fas fa-copy mr-2"></i>Copy Link
                </button>
            </div>
            <div class="bg-white border border-gray-300 rounded-md p-3">
                <code class="text-sm text-gray-900 break-all" id="survey-link">' . htmlspecialchars($link) . '</code>
            </div>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Important Information</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>Share this link with your participants to collect responses. Anyone with this link can take the survey.</p>
                    </div>
                </div>
            </div>
        </div>';

if ($survey['deadline']) {
    $content .= '
        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-calendar-alt text-orange-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-orange-800">Survey Deadline</h3>
                    <div class="mt-2 text-sm text-orange-700">
                        <p>Participants cannot start the survey after ' . date('F j, Y \a\t g:i A', strtotime($survey['deadline'])) . '</p>
                    </div>
                </div>
            </div>
        </div>';
}

$content .= '
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="' . BASE_PATH . '/surveys/' . $survey['id'] . '/analytics"
                class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                <i class="fas fa-chart-bar mr-2"></i>View Analytics
            </a>
            <a href="' . BASE_PATH . '/surveys/' . $survey['id'] . '/edit"
                class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                <i class="fas fa-edit mr-2"></i>Edit Survey
            </a>
            <a href="' . BASE_PATH . '/dashboard"
                class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <script>
        function copyToClipboard() {
            const linkElement = document.getElementById(\'survey-link\');
            const textArea = document.createElement(\'textarea\');
            textArea.value = linkElement.textContent;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand(\'copy\');
            document.body.removeChild(textArea);

            const copyBtn = document.getElementById(\'copy-btn\');
            const originalHTML = copyBtn.innerHTML;
            copyBtn.innerHTML = \'<i class="fas fa-check mr-2"></i>Copied!\';
            copyBtn.classList.remove(\'bg-blue-600\', \'hover:bg-blue-700\');
            copyBtn.classList.add(\'bg-green-600\', \'hover:bg-green-700\');

            setTimeout(() => {
                copyBtn.innerHTML = originalHTML;
                copyBtn.classList.remove(\'bg-green-600\', \'hover:bg-green-700\');
                copyBtn.classList.add(\'bg-blue-600\', \'hover:bg-blue-700\');
            }, 2000);
        }
    </script>
';

include __DIR__ . '/../layouts/researcher.php';
?>