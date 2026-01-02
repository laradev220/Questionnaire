<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            <?php $pageTitle = 'Dashboard'; include 'templates/admin/header.php'; ?>
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                <!-- KPI Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-<?php echo is_super_admin() ? '5' : '4'; ?> gap-6 mb-8">
                    <?php if (is_super_admin()): ?>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center">
                            <div class="p-3 bg-indigo-100 rounded-lg">
                                <i class="fas fa-user-shield text-indigo-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Users</p>
                                <p class="text-3xl font-bold text-gray-900"><?php echo $totalUsers; ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center">
                            <div class="p-3 bg-blue-100 rounded-lg">
                                <i class="fas fa-users text-blue-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Participants</p>
                                <p class="text-3xl font-bold text-gray-900"><?php echo $totalParticipants; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center">
                            <div class="p-3 bg-green-100 rounded-lg">
                                <i class="fas fa-check-circle text-green-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Completed Surveys</p>
                                <p class="text-3xl font-bold text-gray-900"><?php echo $completedSessions; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center">
                            <div class="p-3 bg-yellow-100 rounded-lg">
                                <i class="fas fa-clock text-yellow-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Sessions</p>
                                <p class="text-3xl font-bold text-gray-900"><?php echo $totalSessions; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center">
                            <div class="p-3 bg-purple-100 rounded-lg">
                                <i class="fas fa-star text-purple-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Avg Score</p>
                                <p class="text-3xl font-bold text-gray-900"><?php echo number_format($avgScore, 1); ?>/5</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Survey Completion Trend</h3>
                        <canvas id="completionChart" width="400" height="200"></canvas>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Response Distribution</h3>
                        <canvas id="responseChart" width="400" height="200"></canvas>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Recent Activity</h3>
                    <div class="space-y-4">
                        <?php if (empty($recentActivity)): ?>
                            <p class="text-gray-500">No recent activity.</p>
                        <?php else: ?>
                            <?php foreach ($recentActivity as $activity): ?>
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($activity['name']); ?></p>
                                        <p class="text-sm text-gray-500"><?php echo htmlspecialchars($activity['email']); ?></p>
                                    </div>
                                    <p class="text-sm text-gray-500"><?php echo date('M d, Y H:i', strtotime($activity['created_at'])); ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Participant Survey Status -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Participant Survey Status</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                 <tr>
                                     <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                     <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                     <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                     <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                     <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                                 </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($participantStatus as $participant): ?>
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                         <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                             <?php echo htmlspecialchars($participant['name']); ?>
                                         </td>
                                         <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                             <?php echo htmlspecialchars($participant['email']); ?>
                                         </td>
                                         <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                             <?php echo htmlspecialchars($participant['phone'] ?? ''); ?>
                                         </td>
                                         <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                <?php echo $participant['is_completed'] ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                                <?php echo $participant['is_completed'] ? 'Completed' : 'In Progress'; ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo date('M d, Y', strtotime($participant['joined_at'])); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
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

        // Charts
        const completionCtx = document.getElementById('completionChart').getContext('2d');
        const completionChart = new Chart(completionCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Completed Surveys',
                    data: [12, 19, 3, 5, 2, 3], // Replace with actual data from PHP
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const responseCtx = document.getElementById('responseChart').getContext('2d');
        const responseChart = new Chart(responseCtx, {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'In Progress', 'Not Started'],
                datasets: [{
                    data: [<?php echo $completedSessions; ?>, <?php echo $totalSessions - $completedSessions; ?>, 0], // Adjust as needed
                    backgroundColor: [
                        'rgb(34, 197, 94)',
                        'rgb(251, 191, 36)',
                        'rgb(239, 68, 68)'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</body>

</html>