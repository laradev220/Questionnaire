@extends('layouts.admin')

@section('page-title', 'Admin Dashboard')

@section('content')
    <div class="space-y-6">
        <!-- Welcome Header -->
        <div class="bg-white rounded-xl shadow-xl border border-gray-100 p-8">
            <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Admin Dashboard</h1>
            <p class="text-gray-600">Welcome back, {{ $_SESSION['admin_name'] }}. Here's what's happening with your research surveys.</p>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Responses -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-500 rounded-lg">
                        <i class="fas fa-chart-line text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Responses</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalResponses }}</p>
                    </div>
                </div>
            </div>

            <!-- Completion Rate -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-500 rounded-lg">
                        <i class="fas fa-check-circle text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Completion Rate</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $completionRate }}%</p>
                    </div>
                </div>
            </div>

            <!-- Average Score -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-500 rounded-lg">
                        <i class="fas fa-star text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Avg Score</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $avgScore }}/5</p>
                    </div>
                </div>
            </div>

            <!-- Total Participants -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-orange-500 rounded-lg">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Participants</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalParticipants }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent Activity -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Survey Completions</h3>
                <div class="space-y-3">
                    @forelse($recentActivity as $activity)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-green-600 text-sm"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $activity['name'] }}</p>
                                    <p class="text-xs text-gray-500">{{ $activity['email'] }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500">{{ date('M j, H:i', strtotime($activity['created_at'])) }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm">No recent completions</p>
                    @endforelse
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="/admin/questions" class="flex items-center p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                        <i class="fas fa-plus-circle text-blue-600 mr-3"></i>
                        <span class="text-sm font-medium text-blue-900">Manage Questions</span>
                    </a>
                    <a href="/admin/analytics" class="flex items-center p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                        <i class="fas fa-chart-bar text-green-600 mr-3"></i>
                        <span class="text-sm font-medium text-green-900">View Analytics</span>
                    </a>
                    <a href="/admin/logout" class="flex items-center p-3 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
                        <i class="fas fa-sign-out-alt text-red-600 mr-3"></i>
                        <span class="text-sm font-medium text-red-900">Logout</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection