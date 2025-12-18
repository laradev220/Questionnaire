<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics</title>
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
            <?php $pageTitle = 'Analytics';
            include 'templates/admin/header.php'; ?>

            <main class="flex-1 overflow-y-auto bg-gray-50 p-6">

                <!-- Date Range Filter -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Filter Analytics</h3>
                    <form method="GET" class="flex flex-wrap gap-4 items-end">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <input type="date" name="start_date" value="<?php echo $_GET['start_date'] ?? ''; ?>"
                                class="border border-gray-300 rounded-lg px-3 py-2 w-48 focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <input type="date" name="end_date" value="<?php echo $_GET['end_date'] ?? ''; ?>"
                                class="border border-gray-300 rounded-lg px-3 py-2 w-48 focus:ring-2 focus:ring-blue-500">
                        </div>
                        <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-filter mr-2"></i>Apply Filter
                        </button>
                    </form>
                </div>

                <!-- Export Data -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Export Data</h3>
                    <div class="flex gap-4">
                        <a href="<?php echo BASE_PATH; ?>/admin/export/participants?<?php echo http_build_query(array_filter(['start_date' => $_GET['start_date'] ?? '', 'end_date' => $_GET['end_date'] ?? ''])); ?>"
                           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-download mr-2"></i>Export Participants
                        </a>
                        <a href="<?php echo BASE_PATH; ?>/admin/export/responses?<?php echo http_build_query(array_filter(['start_date' => $_GET['start_date'] ?? '', 'end_date' => $_GET['end_date'] ?? ''])); ?>"
                           class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                            <i class="fas fa-download mr-2"></i>Export Responses
                        </a>
                    </div>
                </div>

                <!-- KPI CARDS -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">

                    <!-- Total Participants -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-blue-100 rounded-lg">
                                <i class="fas fa-users text-blue-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-600">Total Participants</p>
                                <p class="text-3xl font-bold text-gray-900">
                                    <?= $totalParticipants ?? 0 ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Completed Surveys -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-green-100 rounded-lg">
                                <i class="fas fa-check-circle text-green-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-600">Completed Surveys</p>
                                <p class="text-3xl font-bold text-gray-900">
                                    <?= $completedSessions ?? 0 ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Total Responses -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-yellow-100 rounded-lg">
                                <i class="fas fa-chart-line text-yellow-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-600">Total Responses</p>
                                <p class="text-3xl font-bold text-gray-900">
                                    <?= $totalResponses ?? 0 ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Average Score -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-purple-100 rounded-lg">
                                <i class="fas fa-star text-purple-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-600">Average Score</p>
                                <p class="text-3xl font-bold text-gray-900">
                                    <?= isset($overallAvg) ? number_format($overallAvg, 1) : '0.0' ?>/5
                                </p>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
                    <!-- Module Averages Chart -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Module Averages</h3>
                        <canvas id="moduleChart"></canvas>
                    </div>

                    <!-- Score Distribution -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Score Distribution</h3>
                        <canvas id="scoreChart"></canvas>
                    </div>
                </div>

                <!-- Participant Survey Status -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Participant Survey Status</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                 <tr>
                                     <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name
                                     </th>
                                     <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email
                                     </th>
                                     <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone
                                     </th>
                                     <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status
                                     </th>
                                     <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Joined
                                     </th>
                                 </tr>
                            </thead>

                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($participantStatus as $participant): ?>
                                    <tr class="hover:bg-gray-50">

                                         <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                             <?= htmlspecialchars($participant['name']) ?>
                                         </td>

                                         <td class="px-6 py-4 text-sm text-gray-600">
                                             <?= htmlspecialchars($participant['email']) ?>
                                         </td>

                                         <td class="px-6 py-4 text-sm text-gray-600">
                                             <?= htmlspecialchars($participant['phone'] ?? '') ?>
                                         </td>

                                         <td class="px-6 py-4">
                                            <span
                                                class="px-2.5 py-1 rounded-full text-xs font-medium
                                                <?= $participant['is_completed'] ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                                <?= $participant['is_completed'] ? 'Completed' : 'In Progress' ?>
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            <?= date('M d, Y', strtotime($participant['joined_at'])) ?>
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
        const sidebar = document.getElementById('sidebar');
        const closeBtn = document.getElementById('close-btn');

        menuBtn?.addEventListener('click', () => sidebar.classList.toggle('-translate-x-full'));
        closeBtn?.addEventListener('click', () => sidebar.classList.add('-translate-x-full'));

        // Bar Chart – Module Averages
        new Chart(document.getElementById('moduleChart'), {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_column($moduleAverages, 'module')) ?>,
                datasets: [{
                    data: <?= json_encode(array_column($moduleAverages, 'avg_score')) ?>,
                    backgroundColor: 'rgba(59,130,246,0.7)',
                    borderColor: 'rgb(59,130,246)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 5
                    }
                }
            }
        });

        // Pie Chart – Score Distribution
        new Chart(document.getElementById('scoreChart'), {
            type: 'pie',
            data: {
                labels: <?= json_encode(array_column($scoreDistribution, 'score')) ?>,
                datasets: [{
                    data: <?= json_encode(array_column($scoreDistribution, 'count')) ?>,
                    backgroundColor: [
                        'rgb(34,197,94)',
                        'rgb(59,130,246)',
                        'rgb(251,191,36)',
                        'rgb(239,68,68)',
                        'rgb(168,85,247)'
                    ]
                }]
            }
        });
    </script>

</body>

</html>