@extends('layouts.admin')

@section('page-title', 'Analytics')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <div class="space-y-6">
        <!-- Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-500 rounded-md">
                        <i class="fas fa-chart-line text-white"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Responses</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $totalResponses }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-green-500 rounded-md">
                        <i class="fas fa-users text-white"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Sessions</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $totalSessions }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-500 rounded-md">
                        <i class="fas fa-check-circle text-white"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Completed Surveys</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $completedSessions }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-red-500 rounded-md">
                        <i class="fas fa-user-friends text-white"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Participants</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $totalParticipants }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Module Averages -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Average Scores by Module</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Module</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg
                                Score (1-5)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg
                                Weight</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($moduleAverages as $avg)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $avg['module'] }}</td>
                                 <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                     {{ number_format($avg['avg_score'], 2) }}</td>
                                 <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                     {{ number_format($avg['avg_weight'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Score Distribution -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Response Distribution</h3>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                @foreach ($scoreDistribution as $dist)
                    <div class="text-center">
                        <div class="bg-blue-100 rounded-lg p-4">
                            <div class="text-2xl font-bold text-blue-600">{{ $dist['count'] }}</div>
                            <div class="text-sm text-gray-600">Score {{ $dist['score'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Module Averages Chart -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Average Scores by Module</h3>
                <canvas id="moduleChart" width="400" height="200"></canvas>
            </div>

            <!-- Score Distribution Chart -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Response Score Distribution</h3>
                <canvas id="scoreChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <script>
        // Module Averages Bar Chart
        const moduleCtx = document.getElementById('moduleChart').getContext('2d');
        new Chart(moduleCtx, {
            type: 'bar',
            data: {
                labels: @json($moduleNames),
                datasets: [{
                    label: 'Average Score',
                    data: @json($avgScores),
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 5
                    }
                }
            }
        });

        // Score Distribution Bar Chart
        const scoreCtx = document.getElementById('scoreChart').getContext('2d');
        new Chart(scoreCtx, {
            type: 'bar',
            data: {
                labels: @json($scores),
                datasets: [{
                    label: 'Number of Responses',
                    data: @json($scoreCounts),
                    backgroundColor: 'rgba(255, 99, 132, 0.5)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection
</content>
</xai:function_call">
