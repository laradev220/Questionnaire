<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-to-br from-green-50 via-emerald-50 to-teal-100 min-h-screen">


    <main class="flex min-h-screen">
        <!-- Left Side - Illustration -->
        <div
            class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-green-600 via-emerald-600 to-teal-700 p-12 items-center justify-center relative overflow-hidden">
            <div class="text-center text-white z-10 animate-fade-in">
                <div class="mb-8">
                    <svg class="w-32 h-32 mx-auto mb-6 animate-float" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" class="text-white opacity-90" />
                    </svg>
                </div>
                <h1 class="text-4xl font-bold mb-4">Welcome Back</h1>
                <p class="text-xl opacity-90">Continue your research journey</p>
            </div>
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                            <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5" />
                        </pattern>
                    </defs>
                    <rect width="100" height="100" fill="url(#grid)" />
                </svg>
            </div>
        </div>

        <!-- Right Side - Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
            <div class="max-w-md w-full animate-slide-up">
                <div class="text-center mb-8 lg:hidden">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome Back</h2>
                    <p class="text-gray-600">Sign in to your account</p>
                </div>

                <?php if (isset($error)): ?>
                    <div
                        class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center animate-shake">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo BASE_PATH; ?>/login" class="space-y-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-emerald-500"></i>
                            </div>
                            <input type="email" id="email" name="email" required
                                class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-300 hover:border-emerald-300"
                                placeholder="Enter your registered email">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">The email address you used to create your account</p>
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-emerald-500"></i>
                            </div>
                            <input type="password" id="password" name="password" required
                                class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-300 hover:border-emerald-300"
                                placeholder="Enter your secure password">
                            <button type="button" id="togglePassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center hover:text-emerald-600 transition-colors duration-200 z-10">
                                <i class="fas fa-eye text-emerald-500" id="passwordIcon"></i>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Click the eye icon to show/hide your password</p>
                    </div>
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold py-3 px-4 rounded-lg hover:from-emerald-700 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl hover-glow">
                        <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                    </button>
                </form>
                <div class="mt-6 text-center">
                    <p class="text-gray-600">
                        Don't have an account?
                        <a href="<?php echo BASE_PATH; ?>/register"
                            class="text-emerald-600 hover:text-emerald-800 font-medium transition-colors duration-300">Register
                            here</a>
                    </p>
                </div>
            </div>
        </div>
    </main>

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slide-up {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            75% {
                transform: translateX(5px);
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes glow {

            0%,
            100% {
                box-shadow: 0 0 5px rgba(16, 185, 129, 0.5);
            }

            50% {
                box-shadow: 0 0 20px rgba(16, 185, 129, 0.8);
            }
        }

        .animate-fade-in {
            animation: fade-in 1s ease-out;
        }

        .animate-slide-up {
            animation: slide-up 0.8s ease-out 0.2s both;
        }

        .animate-shake {
            animation: shake 0.5s ease-in-out;
        }

        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        .hover-glow:hover {
            animation: glow 2s ease-in-out infinite;
        }
    </style>

    <script>
        // Password toggle functionality
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('passwordIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.className = 'fas fa-eye-slash text-emerald-500';
            } else {
                passwordInput.type = 'password';
                passwordIcon.className = 'fas fa-eye text-emerald-500';
            }
        });

        // Real-time email validation
        document.getElementById('email').addEventListener('input', function() {
            const email = this.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const isValid = emailRegex.test(email);

            if (email.length > 0) {
                if (isValid) {
                    this.classList.remove('border-red-300', 'focus:ring-red-500');
                    this.classList.add('border-green-300', 'focus:ring-green-500');
                } else {
                    this.classList.remove('border-green-300', 'focus:ring-green-500');
                    this.classList.add('border-red-300', 'focus:ring-red-500');
                }
            } else {
                this.classList.remove('border-red-300', 'border-green-300', 'focus:ring-red-500',
                    'focus:ring-green-500');
            }
        });
    </script>
</body>

</html>