<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Question</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50">
    <!-- Mobile menu button -->
    <div class="lg:hidden fixed top-0 left-0 z-50 p-4">
        <button id="menu-btn" class="text-gray-800 focus:outline-none">
            <i class="fas fa-bars text-2xl"></i>
        </button>
    </div>
    <div class="flex h-screen">
        <!-- Sidebar -->
        <?php include 'templates/admin/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <?php $pageTitle = 'Add Question'; include 'templates/admin/header.php'; ?>
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                <div class="max-w-2xl mx-auto">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                        <div class="mb-6">
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">Add New Question</h2>
                            <p class="text-gray-600">Fill in the details below to create a new survey question.</p>
                        </div>
                        <form method="POST" class="space-y-6">
                            <div>
                                <label for="module" class="block text-sm font-medium text-gray-700 mb-2">Module <span class="text-red-500">*</span></label>
                                <input type="text" id="module" name="module" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                       placeholder="Enter module name">
                            </div>
                            <div>
                                <label for="group" class="block text-sm font-medium text-gray-700 mb-2">Group</label>
                                <input type="text" id="group" name="group"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                       placeholder="Enter group (optional)">
                            </div>
                            <div>
                                <label for="code" class="block text-sm font-medium text-gray-700 mb-2">Code <span class="text-red-500">*</span></label>
                                <input type="text" id="code" name="code" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                       placeholder="Enter question code">
                            </div>
                            <div>
                                <label for="text" class="block text-sm font-medium text-gray-700 mb-2">Question Text <span class="text-red-500">*</span></label>
                                <textarea id="text" name="text" required rows="4"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-vertical"
                                          placeholder="Enter the question text"></textarea>
                            </div>
                            <div class="flex gap-4 pt-4">
                                <button type="submit"
                                        class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 font-medium">
                                    <i class="fas fa-plus mr-2"></i>Add Question
                                </button>
                                <a href="<?php echo BASE_PATH; ?>/admin/questions"
                                   class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 font-medium">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Sidebar toggle
        const menuBtn = document.getElementById('menu-btn');
        const closeBtn = document.getElementById('close-btn');
        const sidebar = document.getElementById('sidebar');
        menuBtn.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });
        closeBtn.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
        });

        // Submenu toggle
        function toggleSubmenu() {
            const submenu = document.getElementById('submenu');
            const chevron = document.getElementById('chevron');
            submenu.classList.toggle('hidden');
            chevron.classList.toggle('rotate-180');
        }
    </script>
</body>

</html>