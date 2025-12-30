<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Admin Panel'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 text-white">
            <div class="p-4">
                <h2 class="text-2xl font-bold">Admin Panel</h2>
            </div>
            <nav class="mt-4">
                <a href="<?php echo BASE_PATH; ?>/admin/dashboard" class="block px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white">
                    <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                </a>
                <div class="px-4 py-2">
                    <div class="flex items-center justify-between cursor-pointer" onclick="toggleSubmenu('questions-submenu', 'questions-chevron')">
                        <span class="text-gray-300 font-medium">
                            <i class="fas fa-question-circle mr-2"></i>Manage Questions
                        </span>
                        <i id="questions-chevron" class="fas fa-chevron-down text-gray-400 transition-transform"></i>
                    </div>
                    <div id="questions-submenu" class="ml-6 mt-2 space-y-1">
                        <a href="<?php echo BASE_PATH; ?>/admin/questions"
                            class="block px-4 py-2 text-sm text-gray-400 hover:bg-gray-700 hover:text-white">
                            <i class="fas fa-list mr-2"></i>List Questions
                        </a>
                        <a href="<?php echo BASE_PATH; ?>/admin/questions/add"
                            class="block px-4 py-2 text-sm text-gray-400 hover:bg-gray-700 hover:text-white">
                            <i class="fas fa-plus mr-2"></i>Add Question
                        </a>
                    </div>
                </div>
                <a href="<?php echo BASE_PATH; ?>/admin/surveys" class="block px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white">
                    <i class="fas fa-poll mr-2"></i>Manage Surveys
                </a>
                <a href="<?php echo BASE_PATH; ?>/admin/participants" class="block px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white">
                    <i class="fas fa-users mr-2"></i>View Participants
                </a>
                <a href="<?php echo BASE_PATH; ?>/admin/analytics" class="block px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white">
                    <i class="fas fa-chart-bar mr-2"></i>View Analytics
                </a>
                <a href="<?php echo BASE_PATH; ?>/admin/settings" class="block px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white">
                    <i class="fas fa-cog mr-2"></i>Settings
                </a>
                <a href="<?php echo BASE_PATH; ?>/admin/logout" class="block px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white shadow-sm px-6 py-4">
                <h1 class="text-2xl font-semibold text-gray-900"><?php echo $page_title ?? 'Admin'; ?></h1>
            </header>
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                <?php echo $content; ?>
            </main>
        </div>
    </div>

    <script>
        // Initialize dropdowns on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Ensure all submenus start hidden
            const submenus = ['questions-submenu'];
            submenus.forEach(function(submenuId) {
                const submenu = document.getElementById(submenuId);
                if (submenu) {
                    submenu.style.display = 'none';
                }
            });

            // Reset chevron rotations
            const chevrons = ['questions-chevron'];
            chevrons.forEach(function(chevronId) {
                const chevron = document.getElementById(chevronId);
                if (chevron) {
                    chevron.style.transform = 'rotate(0deg)';
                }
            });
        });

        function toggleSubmenu(submenuId, chevronId) {
            const submenu = document.getElementById(submenuId);
            const chevron = document.getElementById(chevronId);
            if (submenu.style.display === 'none') {
                submenu.style.display = 'block';
                chevron.style.transform = 'rotate(180deg)';
            } else {
                submenu.style.display = 'none';
                chevron.style.transform = 'rotate(0deg)';
            }
        }
    </script>
</body>

</html>