<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Participant Responses</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <?php include 'templates/admin/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <?php $pageTitle = 'Participant Responses';
            include 'templates/admin/header.php'; ?>
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                <div class="max-w-4xl mx-auto">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h1 class="text-2xl font-bold text-gray-900">Participant Responses</h1>
                            <a href="<?php echo BASE_PATH; ?>/admin/participants" class="text-sm text-blue-600 hover:underline">Back to participants</a>
                        </div>

                        <?php if (empty($participant)): ?>
                            <div class="text-red-600">Participant not found.</div>
                        <?php else: ?>
                            <div class="mb-4">
                                <p><strong>Name:</strong> <?php echo htmlspecialchars($participant['name'] ?? ''); ?></p>
                                <p><strong>Email:</strong> <?php echo htmlspecialchars($participant['email'] ?? ''); ?></p>
                                <p><strong>Phone:</strong> <?php echo htmlspecialchars($participant['phone'] ?? ''); ?></p>
                                <p><strong>Survey:</strong> <?php echo htmlspecialchars($participant['survey_title'] ?? ''); ?></p>
                                <p><strong>Joined:</strong> <?php echo !empty($participant['created_at']) ? date('m/d/Y H:i', strtotime($participant['created_at'])) : ''; ?></p>
                            </div>

                            <div>
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Question</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Module</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Weight</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                            <th class="px-4 py-2"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <?php if (empty($responses)): ?>
                                            <tr>
                                                <td colspan="7" class="px-4 py-4 text-center text-gray-500">No responses found for this participant.</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($responses as $r): ?>
                                                <tr>
                                                    <td class="px-4 py-2 text-sm text-gray-700"><?php echo htmlspecialchars($r['code'] ?? ''); ?></td>
                                                    <td class="px-4 py-2 text-sm text-gray-700"><?php echo htmlspecialchars($r['text'] ?? ''); ?></td>
                                                    <td class="px-4 py-2 text-sm text-gray-700"><?php echo htmlspecialchars($r['module'] ?? ''); ?></td>
                                                    <td class="px-4 py-2 text-sm text-gray-700"><?php echo htmlspecialchars($r['score'] ?? ''); ?></td>
                                                    <td class="px-4 py-2 text-sm text-gray-700"><?php echo htmlspecialchars($r['weight'] ?? ''); ?></td>
                                                    <td class="px-4 py-2 text-sm text-gray-700"><?php echo !empty($r['response_date']) ? date('m/d/Y H:i', strtotime($r['response_date'])) : ''; ?></td>
                                                    <td class="px-4 py-2 text-right">
                                                        <?php if (!empty($r['id'])): ?>
                                                            <a href="<?php echo BASE_PATH; ?>/admin/responses/<?php echo (int)$r['id']; ?>/edit" class="text-blue-600 hover:underline">Edit</a>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>