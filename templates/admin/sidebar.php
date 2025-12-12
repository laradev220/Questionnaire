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
            </div>
        </div>
        <a href="<?php echo BASE_PATH; ?>/admin/analytics" class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-all duration-200 mb-2">
            <i class="fas fa-chart-bar mr-3"></i>Analytics
        </a>
        <a href="<?php echo BASE_PATH; ?>/admin/logout" class="flex items-center px-4 py-3 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg transition-all duration-200">
            <i class="fas fa-sign-out-alt mr-3"></i>Logout
        </a>
    </nav>
</div>