<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk Participant Operations</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <?php include 'templates/admin/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <?php $pageTitle = 'Bulk Participant Operations'; include 'templates/admin/header.php'; ?>
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                <div class="space-y-6">
                    <?php if (isset($message)): ?>
                    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                    <?php endif; ?>

                    <!-- Export Participants -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Export Participants</h2>
                        <p class="text-gray-600 mb-4">Download all participants as CSV file.</p>
                        <form method="POST">
                            <input type="hidden" name="action" value="export">
                            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors duration-200">
                                <i class="fas fa-download mr-2"></i>Export to CSV
                            </button>
                        </form>
                    </div>

                    <!-- Import Participants -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Import Participants</h2>
                        <p class="text-gray-600 mb-4">Upload CSV file to bulk import participants. Format: Name,Email,Phone,University,Designation</p>
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

                    <!-- Bulk Delete -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Bulk Delete Participants</h2>
                        <p class="text-gray-600 mb-4">Delete multiple participants and their associated survey sessions and responses.</p>

                        <form method="POST">
                            <input type="hidden" name="action" value="bulk_delete">

                            <div class="mb-4">
                                <h3 class="font-medium mb-2">Select Participants to Delete:</h3>
                                <div class="max-h-96 overflow-y-auto border rounded p-2">
                                    <?php foreach ($participants as $p): ?>
                                    <label class="flex items-center mb-1">
                                        <input type="checkbox" name="participant_ids[]" value="<?php echo $p['id']; ?>" class="mr-2">
                                        <?php echo htmlspecialchars($p['name']); ?> (<?php echo htmlspecialchars($p['email']); ?>) - Survey: <?php echo htmlspecialchars($p['survey_title'] ?: 'None'); ?>
                                    </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors duration-200" onclick="return confirm('Are you sure you want to delete the selected participants? This will also delete their survey responses.')">
                                <i class="fas fa-trash mr-2"></i>Delete Selected Participants
                            </button>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>