<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Research Questionnaire</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50 text-gray-800">
    <nav class="bg-white shadow mb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <span class="font-bold text-xl text-blue-600">ResearchSync</span>
                </div>
                <div class="flex items-center">
                    <?php if (isset($_SESSION['participant_name'])): ?>
                        <span class="text-sm text-gray-500">Hello, <?php echo htmlspecialchars($_SESSION['participant_name']); ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="min-h-[80vh] flex items-center justify-center">
            <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
                <h2 class="text-2xl font-bold text-center mb-6">Participant Information</h2>
                <p class="text-gray-600 text-center mb-6">Please provide your details to start the questionnaire.</p>

                <form method="POST" action="<?php echo BASE_PATH; ?>/">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                        <input type="text" name="name" required
                            class="w-full px-3 py-2 border rounded bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                        <input type="email" name="email" required
                            class="w-full px-3 py-2 border rounded bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Phone Number</label>
                        <input type="tel" name="phone" required
                            class="w-full px-3 py-2 border rounded bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">University</label>
                        <input type="text" name="university" required
                            class="w-full px-3 py-2 border rounded bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Designation</label>
                        <input type="text" name="designation" required
                            class="w-full px-3 py-2 border rounded bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">
                        Start Questionnaire
                    </button>
                </form>
            </div>
        </div>
    </main>
</body>

</html>