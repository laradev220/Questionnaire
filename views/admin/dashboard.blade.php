@extends('layouts.admin')

@section('page-title', 'Admin Dashboard')

@section('content')
    <div class="bg-white rounded-xl shadow-xl border border-gray-100 p-8">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-6">Admin Dashboard</h1>
        <p class="text-gray-600 mb-8">Welcome, {{ $_SESSION['admin_name'] }}. Manage the research application from here.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <a href="/admin/questions" class="block bg-blue-50 hover:bg-blue-100 p-6 rounded-lg border border-blue-200 transition-colors">
                <h3 class="text-xl font-semibold text-blue-900 mb-2">Manage Questions</h3>
                <p class="text-blue-700">Add, edit, and delete survey questions.</p>
            </a>
            <a href="/admin/analytics" class="block bg-green-50 hover:bg-green-100 p-6 rounded-lg border border-green-200 transition-colors">
                <h3 class="text-xl font-semibold text-green-900 mb-2">View Analytics</h3>
                <p class="text-green-700">See survey responses and statistics.</p>
            </a>
            <a href="/admin/logout" class="block bg-red-50 hover:bg-red-100 p-6 rounded-lg border border-red-200 transition-colors">
                <h3 class="text-xl font-semibold text-red-900 mb-2">Logout</h3>
                <p class="text-red-700">Sign out of the admin panel.</p>
            </a>
        </div>
    </div>
@endsection