<?php
$page_title = 'Profile';
$user = get_authenticated_user();
$content = '
    <div class="bg-white rounded-xl shadow-xl border border-gray-100 p-8">
        <div class="mb-6">
            <h1 class="text-3xl font-extrabold text-gray-900">Profile Settings</h1>
            <p class="text-gray-600 mt-2">Manage your account information</p>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-2">Account Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-blue-700">Name</label>
                    <p class="text-blue-900 font-medium">' . htmlspecialchars($user['name']) . '</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-blue-700">Email</label>
                    <p class="text-blue-900 font-medium">' . htmlspecialchars($user['email']) . '</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-blue-700">Role</label>
                    <p class="text-blue-900 font-medium">' . ucfirst($user['role']) . '</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-blue-700">Member Since</label>
                    <p class="text-blue-900 font-medium">' . date('M j, Y', strtotime($user['created_at'])) . '</p>
                </div>
            </div>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Profile Management</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>Profile editing features will be available in a future update. For now, your account information is managed by administrators.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
';

include __DIR__ . '/../layouts/researcher.php';
?>