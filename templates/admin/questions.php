<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Questions</title>
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
                <h1 class="text-2xl font-semibold text-gray-900">Questions</h1>
            </header>
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6 pt-16 lg:pt-6">
                <div class="mb-4">
                    <a href="<?php echo BASE_PATH; ?>/admin/questions/add" class="bg-blue-600 text-white px-4 py-2 rounded">Add Question</a>
                </div>
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">ID</th>
                            <th class="py-2 px-4 border-b">Module</th>
                            <th class="py-2 px-4 border-b">Group</th>
                            <th class="py-2 px-4 border-b">Code</th>
                            <th class="py-2 px-4 border-b">Text</th>
                            <th class="py-2 px-4 border-b">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($questions as $question): ?>
                            <tr>
                                <td class="py-2 px-4 border-b"><?php echo $question['id']; ?></td>
                                <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($question['module']); ?></td>
                                <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($question['group'] ?? ''); ?></td>
                                <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($question['code']); ?></td>
                                <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($question['text']); ?></td>
                                <td class="py-2 px-4 border-b">
                                    <a href="<?php echo BASE_PATH; ?>/admin/questions/edit/<?php echo $question['id']; ?>" class="text-blue-600">Edit</a>
                                    <a href="<?php echo BASE_PATH; ?>/admin/questions/delete/<?php echo $question['id']; ?>" class="text-red-600 ml-2">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </main>
        </div>
    </div>
    <script>
        const menuBtn = document.getElementById('menu-btn');
        const sidebar = document.getElementById('sidebar');
        const closeBtn = document.getElementById('close-btn');
        menuBtn.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });
        closeBtn.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
        });
    </script>
</body>

</html>