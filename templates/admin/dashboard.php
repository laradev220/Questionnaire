<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-to-br from-gray-100 to-gray-200">
    <!-- Mobile menu button -->
    <div class="lg:hidden fixed top-0 left-0 z-50 p-4">
        <button id="menu-btn" class="text-gray-800 focus:outline-none">
            <i class="fas fa-bars text-2xl"></i>
        </button>
    </div>
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div id="sidebar" class="w-64 bg-gray-800 text-white fixed lg:static inset-y-0 left-0 z-40 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
            <div class="p-4 flex justify-between items-center">
                <h2 class="text-2xl font-bold">Admin Panel</h2>
                <button id="close-btn" class="lg:hidden text-gray-300 hover:text-white focus:outline-none">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <nav class="mt-4">
                <a href="<?php echo BASE_PATH; ?>/admin/dashboard" class="block px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white transition-all duration-200 transition-all duration-200">
                    <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                </a>
                <div class="px-4 py-2">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-300 font-medium">
                            <i class="fas fa-question-circle mr-2"></i>Manage Questions
                        </span>
                        <i class="fas fa-chevron-down text-gray-400"></i>
                    </div>
                    <div class="ml-6 mt-2 space-y-1">
                        <a href="<?php echo BASE_PATH; ?>/admin/questions"
                            class="block px-4 py-2 text-sm text-gray-400 hover:bg-gray-700 hover:text-white transition-all duration-200">
                            <i class="fas fa-list mr-2"></i>List Questions
                        </a>
                        <a href="<?php echo BASE_PATH; ?>/admin/questions/add"
                            class="block px-4 py-2 text-sm text-gray-400 hover:bg-gray-700 hover:text-white transition-all duration-200">
                            <i class="fas fa-plus mr-2"></i>Add Question
                        </a>
                    </div>
                </div>
                <a href="<?php echo BASE_PATH; ?>/admin/analytics" class="block px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white transition-all duration-200">
                    <i class="fas fa-chart-bar mr-2"></i>Analytics
                </a>
                <a href="<?php echo BASE_PATH; ?>/admin/logout" class="block px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white transition-all duration-200">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white shadow-sm px-6 py-4">
                <h1 class="text-2xl font-semibold text-gray-900">Dashboard</h1>
            </header>
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6 pt-16 lg:pt-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-500 rounded-md">
                                <i class="fas fa-users text-white"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Participants</p>
                                <p class="text-2xl font-semibold text-gray-900"><?php echo $totalParticipants; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-500 rounded-md">
                                <i class="fas fa-check-circle text-white"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Completed Surveys</p>
                                <p class="text-2xl font-semibold text-gray-900"><?php echo $completedSessions; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-yellow-500 rounded-md">
                                <i class="fas fa-clock text-white"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Sessions</p>
                                <p class="text-2xl font-semibold text-gray-900"><?php echo $totalSessions; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-purple-500 rounded-md">
                                <i class="fas fa-star text-white"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Avg Score</p>
                                <p class="text-2xl font-semibold text-gray-900"><?php echo $avgScore; ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h3>
                    <div class="space-y-4">
                        <?php foreach ($recentActivity as $activity): ?>
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($activity['name']); ?></p>
                                    <p class="text-sm text-gray-500"><?php echo htmlspecialchars($activity['email']); ?></p>
                                </div>
                                <p class="text-sm text-gray-500"><?php echo $activity['created_at']; ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script>
        const menuBtn = document.getElementById('menu-btn');
        const closeBtn = document.getElementById('close-btn');
        const sidebar = document.getElementById('sidebar');
        menuBtn.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });
        closeBtn.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
        });
    </script>
</body>

</html>