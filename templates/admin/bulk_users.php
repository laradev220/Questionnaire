<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk User Operations</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <?php include 'templates/admin/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <?php $pageTitle = 'Bulk User Operations'; include 'templates/admin/header.php'; ?>
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                <div class="space-y-6">
                    <?php if (isset($message)): ?>
                    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                    <?php endif; ?>

                    <!-- Export Users -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Export Users</h2>
                        <p class="text-gray-600 mb-4">Download all users as CSV file for backup or analysis.</p>
                        <form method="POST">
                            <input type="hidden" name="action" value="export">
                            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors duration-200">
                                <i class="fas fa-download mr-2"></i>Export to CSV
                            </button>
                        </form>
                    </div>

                    <!-- Import Users -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Import Users</h2>
                        <p class="text-gray-600 mb-4">Upload CSV file to bulk import users. Format: ID,Name,Email,Role (ID will be ignored for new users).</p>
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="import">
                            <div class="mb-4">
                                <label for="csv_file" class="block text-sm font-medium text-gray-700">CSV File</label>
                                <input type="file" id="csv_file" name="csv_file" accept=".csv" required
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                <i class="fas fa-upload mr-2"></i>Import from CSV
                            </button>
                        </form>
                    </div>

                    <!-- Bulk Update/Delete -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Bulk Update/Delete Users</h2>
                        <p class="text-gray-600 mb-4">Select users below and perform bulk operations.</p>

                        <!-- Bulk Actions Form -->
                        <form method="POST" id="bulk-form">
                            <input type="hidden" name="action" id="bulk-action" value="">
                            <div class="mb-4 flex space-x-4">
                                <button type="button" onclick="setAction('bulk_update')" class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition-colors duration-200">
                                    <i class="fas fa-edit mr-2"></i>Bulk Update
                                </button>
                                <button type="button" onclick="setAction('bulk_delete')" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors duration-200">
                                    <i class="fas fa-trash mr-2"></i>Bulk Delete
                                </button>
                            </div>

                            <!-- Bulk Update Options (hidden initially) -->
                            <div id="update-options" class="mb-4 p-4 bg-gray-50 rounded-lg hidden">
                                <h3 class="font-medium mb-2">Update Options:</h3>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="reset_password" value="1" class="mr-2">
                                        Reset passwords to random values
                                    </label>
                                    <div>
                                        <label for="new_role" class="block text-sm font-medium text-gray-700">Change Role To:</label>
                                        <select name="new_role" id="new_role" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">No role change</option>
                                            <option value="researcher">Researcher</option>
                                            <option value="admin">Administrator</option>
                                            <option value="super_admin">Super Administrator</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- Users Table with Checkboxes -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left">
                                            <input type="checkbox" id="select-all" class="rounded">
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($users as $user): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox" name="user_ids[]" value="<?php echo $user['id']; ?>" form="bulk-form"
                                                   class="user-checkbox rounded" <?php echo $user['id'] == $_SESSION['user_id'] ? 'disabled' : ''; ?>>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($user['name']); ?></div>
                                            <div class="text-sm text-gray-500"><?php echo htmlspecialchars($user['email']); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                <?php
                                                switch($user['role']) {
                                                    case 'super_admin': echo 'bg-red-100 text-red-800'; break;
                                                    case 'admin': echo 'bg-blue-100 text-blue-800'; break;
                                                    default: echo 'bg-green-100 text-green-800';
                                                }
                                                ?>">
                                                <?php echo htmlspecialchars(get_user_role_display($user['role'])); ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo date('M j, Y', strtotime($user['created_at'])); ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        function setAction(action) {
            document.getElementById('bulk-action').value = action;
            if (action === 'bulk_update') {
                document.getElementById('update-options').classList.remove('hidden');
            } else {
                document.getElementById('update-options').classList.add('hidden');
            }
            document.getElementById('bulk-form').submit();
        }

        // Select all checkbox
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.user-checkbox:not([disabled])');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    </script>
</body>
</html>