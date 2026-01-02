<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Response</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <?php include 'templates/admin/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <?php $pageTitle = 'Edit Response';
            include 'templates/admin/header.php'; ?>
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                <div class="max-w-2xl mx-auto">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Response</h1>

                        <?php if (isset($error)): ?>
                            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                                <?php echo htmlspecialchars($error); ?>
                            </div>
                        <?php endif; ?>

                        <div class="mb-4">
                            <p><strong>Participant:</strong> <?php echo htmlspecialchars($response['participant_name'] ?? ''); ?> (<?php echo htmlspecialchars($response['email'] ?? ''); ?>)</p>
                            <p><strong>Question:</strong> <?php echo htmlspecialchars($response['code'] ?? ''); ?> - <?php echo htmlspecialchars($response['text'] ?? ''); ?></p>
                            <p><strong>Module:</strong> <?php echo htmlspecialchars($response['module'] ?? ''); ?></p>
                        </div>

                        <form method="POST" class="space-y-6">
                            <div>
                                <label for="score" class="block text-sm font-medium text-gray-700">Score (1-5)</label>
                                <input type="number" id="score" name="score" min="1" max="5"
                                    value="<?php echo htmlspecialchars((string)($response['score'] ?? '')); ?>"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="weight" class="block text-sm font-medium text-gray-700">Weight</label>
                                <input type="number" step="0.01" id="weight" name="weight"
                                    value="<?php echo htmlspecialchars((string)($response['weight'] ?? '')); ?>"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div class="flex justify-end space-x-3">
                                <a href="<?php echo BASE_PATH; ?>/admin/responses" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition-colors duration-200">
                                    Cancel
                                </a>
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                    <i class="fas fa-save mr-2"></i>Update Response
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>