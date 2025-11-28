<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 text-white">
            <div class="p-4">
                <h2 class="text-2xl font-bold">Admin Panel</h2>
            </div>
            <nav class="mt-4">
                <a href="/admin/dashboard" class="block px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white">
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
                        <a href="/admin/questions"
                            class="block px-4 py-2 text-sm text-gray-400 hover:bg-gray-700 hover:text-white">
                            <i class="fas fa-list mr-2"></i>List Questions
                        </a>
                        <a href="/admin/questions/add"
                            class="block px-4 py-2 text-sm text-gray-400 hover:bg-gray-700 hover:text-white">
                            <i class="fas fa-plus mr-2"></i>Add Question
                        </a>
                    </div>
                </div>
                <a href="/admin/analytics" class="block px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white">
                    <i class="fas fa-chart-bar mr-2"></i>Analytics
                </a>
                <a href="/admin/logout" class="block px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white shadow-sm px-6 py-4">
                <h1 class="text-2xl font-semibold text-gray-900">@yield('page-title', 'Admin')</h1>
            </header>
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>
</content>
</xai:function_call">
