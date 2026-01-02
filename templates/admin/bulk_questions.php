<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk Question Operations</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <?php include 'templates/admin/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <?php $pageTitle = 'Bulk Question Operations'; include 'templates/admin/header.php'; ?>
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                <div class="space-y-6">
                    <?php if (isset($message)): ?>
                    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                    <?php endif; ?>

                    <!-- Export Questions -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Export Questions</h2>
                        <p class="text-gray-600 mb-4">Download all questions as CSV file.</p>
                        <form method="POST">
                            <input type="hidden" name="action" value="export">
                            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors duration-200">
                                <i class="fas fa-download mr-2"></i>Export to CSV
                            </button>
                        </form>
                    </div>

                    <!-- Import Questions -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Import Questions</h2>
                        <p class="text-gray-600 mb-4">Upload CSV file to bulk import questions. Format: Module,Group,Code,Text</p>
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
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Bulk Update/Delete Questions</h2>
                        <p class="text-gray-600 mb-4">Select questions below and perform bulk operations.</p>

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
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="new_module" class="block text-sm font-medium text-gray-700">New Module:</label>
                                        <input type="text" name="new_module" id="new_module" placeholder="e.g., SHRM"
                                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label for="new_group" class="block text-sm font-medium text-gray-700">New Group:</label>
                                        <input type="text" name="new_group" id="new_group" placeholder="Leave empty to clear"
                                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- Questions Table with Checkboxes -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left">
                                            <input type="checkbox" id="select-all" class="rounded">
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Question</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Module</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Creator</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($questions as $question): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox" name="question_ids[]" value="<?php echo $question['id']; ?>" form="bulk-form" class="question-checkbox rounded">
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900"><?php echo htmlspecialchars(substr($question['text'], 0, 100)); ?><?php echo strlen($question['text']) > 100 ? '...' : ''; ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php echo htmlspecialchars($question['module']); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-500">
                                            <?php echo htmlspecialchars($question['code']); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo htmlspecialchars($question['creator_name'] ?: 'System'); ?>
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
            const checkboxes = document.querySelectorAll('.question-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    </script>
</body>
</html>