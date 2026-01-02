<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Participant Information - ResearchSync</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .info-card {
            background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .form-input {
            transition: all 0.2s ease;
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid #e2e8f0;
        }

        .form-input:focus {
            background: white;
            border-color: #ea580c;
            box-shadow: 0 0 0 4px rgba(234, 88, 12, 0.1);
        }

        .submit-btn {
            background: linear-gradient(135deg, #ea580c 0%, #dc2626 100%);
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(234, 88, 12, 0.2);
        }
    </style>
</head>

<body class="bg-gradient-to-br from-orange-50 via-red-50 to-pink-100 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white/90 backdrop-blur-sm border-b border-orange-200 shadow-sm">
        <div class="max-w-6xl mx-auto px-4 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-orange-600 to-red-700 rounded-lg flex items-center justify-center shadow-md">
                        <i class="fas fa-microscope text-white text-sm"></i>
                    </div>
                    <div>
                        <span class="font-bold text-lg text-orange-800 tracking-tight">ResearchSync</span>
                        <span class="text-xs text-orange-500 font-medium ml-2">Academic Survey Platform</span>
                    </div>
                </div>
                <a href="#footer"
                    class="text-orange-600 font-medium text-sm transition-colors duration-200 flex items-center">
                    <i class="fas fa-question-circle text-orange-500 mr-2"></i>
                    <span>Need Help?</span>
                </a>
            </div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-4 py-8 flex justify-center items-start">
        <!-- Registration Form - Centered -->
        <div class="info-card rounded-2xl p-8 max-w-2xl w-full">
            <div class="text-center mb-8">
                <div class="mx-auto w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-user-plus text-blue-600 text-2xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Join the Survey</h2>
                <p class="text-gray-600 mb-4">Please provide your information to begin</p>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4">
                    <p class="text-sm text-blue-800 font-medium">📝 Fill out the form below and click "Start
                        Questionnaire" to begin</p>
                </div>
            </div>

            <form method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>" class="space-y-7">
                <!-- Name Field -->
                <div class="space-y-3">
                    <label class="block text-sm font-semibold text-slate-700 flex items-center">
                        <i class="fas fa-user text-orange-400 mr-2"></i>
                        Full Name
                        <span class="text-red-500 ml-1">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" id="name" name="name" required
                            class="form-input w-full pl-12 pr-4 py-4 rounded-xl placeholder-slate-400"
                            placeholder="Enter your full name">
                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400">
                            <i class="fas fa-user-circle"></i>
                        </div>
                    </div>
                </div>

                <!-- Email Field -->
                <div class="space-y-3">
                    <label class="block text-sm font-semibold text-slate-700 flex items-center">
                        <i class="fas fa-envelope text-orange-400 mr-2"></i>
                        Email Address
                        <span class="text-red-500 ml-1">*</span>
                    </label>
                    <div class="relative">
                        <input type="email" id="email" name="email" required
                            class="form-input w-full pl-12 pr-4 py-4 rounded-xl placeholder-slate-400"
                            placeholder="Enter your email">
                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400">
                            <i class="fas fa-at"></i>
                        </div>
                    </div>
                </div>

                <!-- Phone Field -->
                <div class="space-y-3">
                    <label class="block text-sm font-semibold text-slate-700 flex items-center">
                        <i class="fas fa-phone text-orange-400 mr-2"></i>
                        Phone Number
                        <span class="text-red-500 ml-1">*</span>
                    </label>
                    <div class="relative">
                        <input type="tel" id="phone" name="phone" required
                            class="form-input w-full pl-12 pr-4 py-4 rounded-xl placeholder-slate-400"
                            placeholder="Enter your phone number">
                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                    </div>
                </div>

                <!-- University Field -->
                <div class="space-y-3">
                    <label class="block text-sm font-semibold text-slate-700 flex items-center">
                        <i class="fas fa-university text-orange-400 mr-2"></i>
                        University/Institution
                        <span class="text-red-500 ml-1">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" id="university" name="university" required
                            class="form-input w-full pl-12 pr-4 py-4 rounded-xl placeholder-slate-400"
                            placeholder="Enter your university">
                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                    </div>
                </div>

                <!-- Designation Field -->
                <div class="space-y-3">
                    <label class="block text-sm font-semibold text-slate-700 flex items-center">
                        <i class="fas fa-briefcase text-orange-400 mr-2"></i>
                        Designation
                        <span class="text-red-500 ml-1">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" id="designation" name="designation" required
                            class="form-input w-full pl-12 pr-4 py-4 rounded-xl placeholder-slate-400"
                            placeholder="Enter your designation">
                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400">
                            <i class="fas fa-suitcase"></i>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit"
                        class="submit-btn w-full text-white font-bold py-5 px-6 rounded-xl text-lg hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200">
                        <div class="flex items-center justify-center space-x-3">
                            <i class="fas fa-rocket text-xl"></i>
                            <span>Start Questionnaire Now</span>
                        </div>
                    </button>

                    <!-- Assurance Note -->
                    <p class="text-center text-slate-500 text-xs mt-6 flex items-center justify-center">
                        <i class="fas fa-lock text-green-500 mr-2"></i>
                        Secure connection • Your data is encrypted and protected
                    </p>
                </div>
            </form>
        </div>
    </main>

    <script>
        // Form interaction enhancements
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.form-input');

            inputs.forEach(input => {
                // Add focus effect
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('scale-[1.01]');
                    this.parentElement.classList.add('transition-transform', 'duration-200');
                });

                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('scale-[1.01]');
                });

                // Add validation styling
                input.addEventListener('input', function() {
                    if (this.value.trim() !== '') {
                        this.classList.add('border-blue-300');
                        this.classList.remove('border-slate-300');
                    } else {
                        this.classList.remove('border-blue-300');
                        this.classList.add('border-slate-300');
                    }
                });
            });

            // Form submission loading state
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalHTML = submitBtn.innerHTML;

                submitBtn.innerHTML = `
                    <div class="flex items-center justify-center space-x-3">
                        <i class="fas fa-spinner fa-spin text-xl"></i>
                        <span>Preparing Survey...</span>
                    </div>
                `;
                submitBtn.disabled = true;

                // Reset button after 8 seconds if something goes wrong
                setTimeout(() => {
                    submitBtn.innerHTML = originalHTML;
                    submitBtn.disabled = false;
                }, 8000);
            });
        });
    </script>
    <?php include __DIR__ . '/../footer.php'; ?>
</body>

</html>