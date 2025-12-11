<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="min-h-[80vh] flex items-center justify-center">
            <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
                <h2 class="text-2xl font-bold text-center mb-6">Register</h2>

                <?php if (isset($error)): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo BASE_PATH; ?>/register">
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
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                        <input type="password" name="password" required
                            class="w-full px-3 py-2 border rounded bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">
                        Register
                    </button>
                </form>
                <p class="text-center mt-4">
                    Already have an account? <a href="<?php echo BASE_PATH; ?>/login" class="text-blue-600 hover:text-blue-800">Login</a>
                </p>
            </div>
        </div>
    </main>
</body>

</html>