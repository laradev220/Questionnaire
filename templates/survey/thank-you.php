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


    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="min-h-[80vh] flex items-center justify-center">
            <div class="max-w-lg w-full bg-white rounded-xl shadow-2xl p-10 text-center border border-green-200">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fa-solid fa-cloud-check text-green-600 text-4xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Survey Complete!</h2>
                <p class="text-gray-600 mb-8">
                    Your valuable responses have been securely recorded and submitted to the research team.
                    We greatly appreciate your time and contribution to this study.
                </p>

                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 text-left mb-6">
                    <p class="text-xs text-gray-500 font-mono">
                        Confirmation: Data submitted on <?php echo date('Y-m-d H:i:s'); ?>
                    </p>
                </div>

                <a href="<?php echo BASE_PATH; ?>/progress"
                    class="text-blue-600 font-medium hover:text-blue-800 flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-arrow-left"></i><span>Return to Progress</span>
                </a>
            </div>
        </div>
    </main>
</body>

</html>