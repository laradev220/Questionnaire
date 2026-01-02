<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk Survey Operations</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <?php include 'templates/admin/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <?php $pageTitle = 'Bulk Survey Operations'; include 'templates/admin/header.php'; ?>
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                <div class="space-y-6">
                    <?php if (isset($message)): ?>
                    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                    <?php endif; ?>

                    <!-- Export Surveys -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Export Surveys</h2>
                        <p class="text-gray-600 mb-4">Download all surveys as CSV file.</p>
                        <form method="POST">
                            <input type="hidden" name="action" value="export">
                            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors duration-200">
                                <i class="fas fa-download mr-2"></i>Export to CSV
                            </button>
                        </form>
                    </div>

                    <!-- Bulk Question Assignment -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Bulk Question Assignment</h2>
                        <p class="text-gray-600 mb-4">Select surveys and questions to assign multiple questions to multiple surveys at once.</p>

                        <form method="POST">
                            <input type="hidden" name="action" value="bulk_assign_questions">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                <div>
                                    <h3 class="font-medium mb-2">Select Surveys:</h3>
                                    <div class="max-h-48 overflow-y-auto border rounded p-2">
                                        <?php foreach ($surveys as $survey): ?>
                                        <label class="flex items-center mb-1">
                                            <input type="checkbox" name="survey_ids[]" value="<?php echo $survey['id']; ?>" class="mr-2">
                                            <?php echo htmlspecialchars($survey['title']); ?> (by <?php echo htmlspecialchars($survey['creator_name']); ?>)
                                        </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="font-medium mb-2">Select Questions:</h3>
                                    <div class="max-h-48 overflow-y-auto border rounded p-2">
                                        <?php foreach ($questions as $question): ?>
                                        <label class="flex items-center mb-1">
                                            <input type="checkbox" name="question_ids[]" value="<?php echo $question['id']; ?>" class="mr-2">
                                            <?php echo htmlspecialchars($question['code']); ?>: <?php echo htmlspecialchars(substr($question['text'], 0, 50)); ?>...
                                        </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                <i class="fas fa-link mr-2"></i>Assign Questions to Surveys
                            </button>
                        </form>
                    </div>

                    <!-- Bulk Status Update -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Bulk Status Update</h2>
                        <p class="text-gray-600 mb-4">Activate or deactivate multiple surveys at once.</p>

                        <form method="POST">
                            <input type="hidden" name="action" value="bulk_status_update">

                            <div class="mb-4">
                                <label class="flex items-center">
                                    <input type="checkbox" name="is_active" value="1" class="mr-2">
                                    Set selected surveys as active
                                </label>
                                <p class="text-sm text-gray-500 ml-6">Uncheck to deactivate</p>
                            </div>

                            <div class="mb-4">
                                <h3 class="font-medium mb-2">Select Surveys:</h3>
                                <div class="max-h-48 overflow-y-auto border rounded p-2">
                                    <?php foreach ($surveys as $survey): ?>
                                    <label class="flex items-center mb-1">
                                        <input type="checkbox" name="survey_ids[]" value="<?php echo $survey['id']; ?>" class="mr-2">
                                        <?php echo htmlspecialchars($survey['title']); ?> (<?php echo $survey['is_active'] ? 'Active' : 'Inactive'; ?>)
                                    </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition-colors duration-200">
                                <i class="fas fa-edit mr-2"></i>Update Status
                            </button>
                        </form>
                    </div>

                    <!-- Bulk Delete -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Bulk Delete Surveys</h2>
                        <p class="text-gray-600 mb-4">Delete multiple surveys at once. Surveys with completed responses will be skipped.</p>

                        <form method="POST">
                            <input type="hidden" name="action" value="bulk_delete">

                            <div class="mb-4">
                                <h3 class="font-medium mb-2">Select Surveys to Delete:</h3>
                                <div class="max-h-48 overflow-y-auto border rounded p-2">
                                    <?php foreach ($surveys as $survey): ?>
                                    <label class="flex items-center mb-1">
                                        <input type="checkbox" name="survey_ids[]" value="<?php echo $survey['id']; ?>" class="mr-2">
                                        <?php echo htmlspecialchars($survey['title']); ?> (<?php echo htmlspecialchars($survey['creator_name']); ?>)
                                    </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors duration-200" onclick="return confirm('Are you sure you want to delete the selected surveys?')">
                                <i class="fas fa-trash mr-2"></i>Delete Selected Surveys
                            </button>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>