<div id="sidebar" class="w-64 bg-white shadow-lg text-gray-800 fixed lg:static inset-y-0 left-0 z-40 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out border-r border-gray-200">
    <div class="p-6 flex justify-between items-center border-b border-gray-200">
        <h2 class="text-2xl font-bold text-gray-900">Admin Panel</h2>
        <button id="close-btn" class="lg:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>
    <nav class="mt-6 px-4">
        <a href="<?php echo BASE_PATH; ?>/admin/dashboard" class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-all duration-200 mb-2">
            <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
        </a>
        <div class="mb-2">
            <div class="flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg cursor-pointer" onclick="toggleSubmenu()">
                <span class="flex items-center">
                    <i class="fas fa-question-circle mr-3"></i>Manage Questions
                </span>
                <i class="fas fa-chevron-down text-gray-400 transition-transform duration-200" id="chevron"></i>
            </div>
            <div class="ml-6 mt-2 space-y-1 hidden" id="submenu">
                <a href="<?php echo BASE_PATH; ?>/admin/questions" class="block px-4 py-2 text-sm text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-all duration-200">
                    <i class="fas fa-list mr-2"></i>List Questions
                </a>
                <a href="<?php echo BASE_PATH; ?>/admin/questions/add" class="block px-4 py-2 text-sm text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-all duration-200">
                    <i class="fas fa-plus mr-2"></i>Add Question
                </a>
                <a href="<?php echo BASE_PATH; ?>/admin/questions/bulk" class="block px-4 py-2 text-sm text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-all duration-200">
                    <i class="fas fa-tasks mr-2"></i>Bulk Operations
                </a>
            </div>
        </div>
        <a href="<?php echo BASE_PATH; ?>/admin/analytics" class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-all duration-200 mb-2">
            <i class="fas fa-chart-bar mr-3"></i>Analytics
        </a>

        <?php if (is_super_admin()): ?>
        <div class="mb-2">
            <div class="flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg cursor-pointer" onclick="toggleUserSubmenu()">
                <span class="flex items-center">
                    <i class="fas fa-users mr-3"></i>User Management
                </span>
                <i class="fas fa-chevron-down text-gray-400 transition-transform duration-200" id="chevron-user"></i>
            </div>
                <div class="ml-6 mt-2 space-y-1 hidden" id="submenu-user">
                <a href="<?php echo BASE_PATH; ?>/admin/users" class="block px-4 py-2 text-sm text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-all duration-200">
                    <i class="fas fa-list mr-2"></i>List Users
                </a>
                <a href="<?php echo BASE_PATH; ?>/admin/users/add" class="block px-4 py-2 text-sm text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-all duration-200">
                    <i class="fas fa-plus mr-2"></i>Add User
                </a>
                <a href="<?php echo BASE_PATH; ?>/admin/users/bulk" class="block px-4 py-2 text-sm text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-all duration-200">
                    <i class="fas fa-tasks mr-2"></i>Bulk Operations
                </a>
            </div>
        </div>
        <?php endif; ?>

        <div class="mb-2">
            <div class="flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg cursor-pointer" onclick="toggleSurveySubmenu()">
                <span class="flex items-center">
                    <i class="fas fa-clipboard-list mr-3"></i>Survey Management
                </span>
                <i class="fas fa-chevron-down text-gray-400 transition-transform duration-200" id="chevron-survey"></i>
            </div>
            <div class="ml-6 mt-2 space-y-1 hidden" id="submenu-survey">
                <a href="<?php echo BASE_PATH; ?>/admin/surveys" class="block px-4 py-2 text-sm text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-all duration-200">
                    <i class="fas fa-list mr-2"></i>List Surveys
                </a>
                <a href="<?php echo BASE_PATH; ?>/admin/surveys/add" class="block px-4 py-2 text-sm text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-all duration-200">
                    <i class="fas fa-plus mr-2"></i>Create Survey
                </a>
                <a href="<?php echo BASE_PATH; ?>/admin/surveys/bulk" class="block px-4 py-2 text-sm text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-all duration-200">
                    <i class="fas fa-tasks mr-2"></i>Bulk Operations
                </a>
            </div>
        </div>

        <div class="mb-2">
            <div class="flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg cursor-pointer" onclick="toggleParticipantSubmenu()">
                <span class="flex items-center">
                    <i class="fas fa-user-friends mr-3"></i>Participants
                </span>
                <i class="fas fa-chevron-down text-gray-400 transition-transform duration-200" id="chevron-participant"></i>
            </div>
            <div class="ml-6 mt-2 space-y-1 hidden" id="submenu-participant">
                <a href="<?php echo BASE_PATH; ?>/admin/participants" class="block px-4 py-2 text-sm text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-all duration-200">
                    <i class="fas fa-list mr-2"></i>Manage Participants
                </a>
                <a href="<?php echo BASE_PATH; ?>/admin/participants/bulk" class="block px-4 py-2 text-sm text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-all duration-200">
                    <i class="fas fa-tasks mr-2"></i>Bulk Operations
                </a>
            </div>
        </div>

        <div class="mb-2">
            <div class="flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg cursor-pointer" onclick="toggleResponseSubmenu()">
                <span class="flex items-center">
                    <i class="fas fa-reply mr-3"></i>Responses
                </span>
                <i class="fas fa-chevron-down text-gray-400 transition-transform duration-200" id="chevron-response"></i>
            </div>
            <div class="ml-6 mt-2 space-y-1 hidden" id="submenu-response">
                <a href="<?php echo BASE_PATH; ?>/admin/responses" class="block px-4 py-2 text-sm text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-all duration-200">
                    <i class="fas fa-list mr-2"></i>Manage Responses
                </a>
            </div>
        </div>

        <a href="<?php echo BASE_PATH; ?>/admin/logout" class="flex items-center px-4 py-3 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg transition-all duration-200">
            <i class="fas fa-sign-out-alt mr-3"></i>Logout
        </a>
    </nav>
</div>

<script>
function toggleSubmenu() {
    const submenu = document.getElementById('submenu');
    const chevron = document.getElementById('chevron');
    submenu.classList.toggle('hidden');
    chevron.classList.toggle('rotate-180');
}

function toggleUserSubmenu() {
    const submenu = document.getElementById('submenu-user');
    const chevron = document.getElementById('chevron-user');
    submenu.classList.toggle('hidden');
    chevron.classList.toggle('rotate-180');
}

function toggleSurveySubmenu() {
    const submenu = document.getElementById('submenu-survey');
    const chevron = document.getElementById('chevron-survey');
    submenu.classList.toggle('hidden');
    chevron.classList.toggle('rotate-180');
}

function toggleParticipantSubmenu() {
    const submenu = document.getElementById('submenu-participant');
    const chevron = document.getElementById('chevron-participant');
    submenu.classList.toggle('hidden');
    chevron.classList.toggle('rotate-180');
}

function toggleResponseSubmenu() {
    const submenu = document.getElementById('submenu-response');
    const chevron = document.getElementById('chevron-response');
    submenu.classList.toggle('hidden');
    chevron.classList.toggle('rotate-180');
}
</script>