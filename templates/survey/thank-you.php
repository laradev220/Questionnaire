<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Complete - ResearchSync</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .confirmation-card {
            background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        }

        .celebration-icon {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
        }

        .return-btn {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.2);
        }

        .confirmation-badge {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            border: 1px solid rgba(167, 243, 208, 0.5);
        }

        #confetti-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            pointer-events: none;
            z-index: 1000;
            overflow: hidden;
        }

        .confetti {
            position: absolute;
            opacity: 0;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-slate-50 via-white to-blue-50 min-h-screen">
    <!-- Confetti Container -->
    <div id="confetti-container"></div>

    <!-- Navigation -->
    <nav class="bg-white/90 backdrop-blur-sm border-b border-slate-200 shadow-sm relative z-10">
        <div class="max-w-7xl mx-auto px-4 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-lg flex items-center justify-center shadow-md">
                        <i class="fas fa-microscope text-white text-sm"></i>
                    </div>
                    <div>
                        <span class="font-bold text-lg text-slate-800 tracking-tight">ResearchSync</span>
                        <span class="text-xs text-slate-500 font-medium ml-2">Academic Survey Platform</span>
                    </div>
                </div>
                <div class="flex items-center space-x-6">
                    <a href="#footer"
                        class="text-slate-600 font-medium text-sm transition-colors duration-200 flex items-center">
                        <i class="fas fa-question-circle text-blue-500 mr-2"></i>
                        <span>Contact Us</span>
                    </a>
                    <?php if (isset($_SESSION['participant_name'])): ?>
                        <div class="flex items-center">
                            <div
                                class="w-8 h-8 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-user text-blue-600 text-sm"></i>
                            </div>
                            <span
                                class="text-sm text-slate-700 font-medium"><?php echo htmlspecialchars($_SESSION['participant_name']); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 py-8 relative z-10">
        <div class="flex items-center justify-center min-h-[calc(100vh-200px)]">
            <div class="max-w-2xl w-full">
                <!-- Confirmation Card -->
                <div class="confirmation-card rounded-3xl p-10 text-center relative">
                    <!-- Celebration Icon -->
                    <div class="celebration-icon w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-8">
                        <i class="fas fa-check text-white text-4xl"></i>
                    </div>

                    <!-- Main Message -->
                    <h1 class="text-4xl font-bold text-slate-900 mb-6">
                        Survey Successfully Completed!
                    </h1>

                    <div class="max-w-lg mx-auto">
                        <p class="text-lg text-slate-700 mb-8 leading-relaxed">
                            Thank you for your valuable contribution to our research on
                            <span class="font-semibold text-blue-700">Sustainable Human Resource Management</span>
                            and <span class="font-semibold text-blue-700">Organizational Resilience</span>.
                            Your insights are instrumental in advancing academic knowledge in this field.
                        </p>
                    </div>

                    <!-- Confirmation Details -->
                    <div class="confirmation-badge rounded-2xl p-6 mb-8 max-w-md mx-auto">
                        <div class="flex items-center justify-center mb-4">
                            <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center mr-4 shadow-sm">
                                <i class="fas fa-file-shield text-green-500 text-xl"></i>
                            </div>
                            <div class="text-left">
                                <h3 class="font-bold text-slate-900">Research Submission Confirmed</h3>
                                <p class="text-slate-600 text-sm">Your responses have been securely recorded</p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-slate-600">Submission Date & Time</span>
                                <span class="font-semibold text-slate-900"><?php echo date('F j, Y'); ?></span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-slate-600">Submission ID</span>
                                <span class="font-mono text-blue-600 font-semibold">RS-<?php echo time(); ?></span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-slate-600">Status</span>
                                <span
                                    class="px-3 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-full">Confirmed</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
                        <a href="<?php echo BASE_PATH; ?>/progress"
                            class="return-btn text-white font-bold py-4 px-8 rounded-xl text-lg">
                            <div class="flex items-center justify-center space-x-3">
                                <i class="fas fa-arrow-left"></i>
                                <span>Return to Dashboard</span>
                            </div>
                        </a>

                        <a href="#footer"
                            class="inline-flex items-center justify-center space-x-3 border-2 border-slate-300 text-slate-700 font-bold py-4 px-8 rounded-xl text-lg transition-all duration-200">
                            <i class="fas fa-envelope"></i>
                            <span>Contact Research Team</span>
                        </a>
                    </div>

                    <!-- Research Impact -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
                        <h3 class="font-bold text-slate-900 mb-4 text-lg">Your Contribution Matters</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-white rounded-xl p-4 text-center">
                                <div
                                    class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-graduation-cap text-blue-600"></i>
                                </div>
                                <p class="text-sm text-slate-700 font-semibold">Academic Advancement</p>
                            </div>
                            <div class="bg-white rounded-xl p-4 text-center">
                                <div
                                    class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-chart-line text-blue-600"></i>
                                </div>
                                <p class="text-sm text-slate-700 font-semibold">Evidence-Based Insights</p>
                            </div>
                            <div class="bg-white rounded-xl p-4 text-center">
                                <div
                                    class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-handshake text-blue-600"></i>
                                </div>
                                <p class="text-sm text-slate-700 font-semibold">Industry Best Practices</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="mt-8 text-center">
                    <div
                        class="inline-flex items-center bg-white px-6 py-4 rounded-xl shadow-sm border border-slate-200">
                        <div class="flex items-center">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-info-circle text-blue-600"></i>
                            </div>
                            <div class="text-left">
                                <p class="text-sm text-slate-700">
                                    <span class="font-semibold">Next Steps:</span>
                                    Your responses will be analyzed as part of our ongoing research.
                                    <a href="#footer" class="text-blue-600 font-medium">Learn more about our research
                                        outcomes</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- More content to ensure scrollability -->
                <div class="mt-12">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-8 border border-blue-100">
                        <h3 class="text-xl font-bold text-slate-900 mb-6 text-center">Frequently Asked Questions</h3>
                        <div class="space-y-4">
                            <div class="bg-white rounded-xl p-5">
                                <h4 class="font-semibold text-slate-900 mb-2 flex items-center">
                                    <i class="fas fa-question-circle text-blue-500 mr-3"></i>
                                    What happens to my data?
                                </h4>
                                <p class="text-slate-600 text-sm">
                                    All data is anonymized and aggregated for analysis. Individual responses are never
                                    shared.
                                </p>
                            </div>
                            <div class="bg-white rounded-xl p-5">
                                <h4 class="font-semibold text-slate-900 mb-2 flex items-center">
                                    <i class="fas fa-question-circle text-blue-500 mr-3"></i>
                                    When will the research be published?
                                </h4>
                                <p class="text-slate-600 text-sm">
                                    The research findings will be published in academic journals within 6-12 months.
                                </p>
                            </div>
                            <div class="bg-white rounded-xl p-5">
                                <h4 class="font-semibold text-slate-900 mb-2 flex items-center">
                                    <i class="fas fa-question-circle text-blue-500 mr-3"></i>
                                    Can I get a copy of the results?
                                </h4>
                                <p class="text-slate-600 text-sm">
                                    Yes! You can request a summary of findings by contacting our research team.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include __DIR__ . '/../footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const celebrationIcon = document.querySelector('.celebration-icon');
            const container = document.getElementById('confetti-container');

            // Create CSS for animations
            const style = document.createElement('style');
            style.textContent = `
                @keyframes pulse {
                    0% { transform: scale(1); box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3); }
                    50% { transform: scale(1.05); box-shadow: 0 15px 35px rgba(16, 185, 129, 0.4); }
                    100% { transform: scale(1); box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3); }
                }
                
                @keyframes confetti-fall {
                    0% {
                        transform: translateY(-100px) rotate(0deg);
                        opacity: 1;
                    }
                    100% {
                        transform: translateY(calc(100vh + 200px)) rotate(720deg);
                        opacity: 0;
                    }
                }
                
                @keyframes confetti-sway {
                    0% { transform: translateX(0px) rotate(0deg); }
                    50% { transform: translateX(20px) rotate(180deg); }
                    100% { transform: translateX(-20px) rotate(360deg); }
                }
                
                @keyframes spiral {
                    0% { transform: translate(0, 0) rotate(0deg) scale(1); }
                    100% { transform: translate(100px, 100vh) rotate(720deg) scale(0.5); }
                }
                
                @keyframes emoji-rise {
                    0% {
                        transform: translateY(0) rotate(0deg) scale(0.5);
                        opacity: 0;
                    }
                    10% {
                        opacity: 0.8;
                    }
                    90% {
                        opacity: 0.8;
                    }
                    100% {
                        transform: translateY(-100vh) rotate(360deg) scale(1.2);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);

            // Add pulsing animation to celebration icon
            celebrationIcon.style.animation = 'pulse 2s ease-in-out infinite';

            // Create LOTS of confetti!
            const colors = ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ef4444', '#ec4899', '#14b8a6', '#f97316'];
            const confettiTypes = ['circle', 'rectangle', 'triangle', 'diamond'];
            const confettiCount = 80; // Reduced but still celebratory

            for (let i = 0; i < confettiCount; i++) {
                createConfettiPiece(i);
            }

            // Continuous confetti for 5 seconds
            let confettiInterval = setInterval(() => {
                for (let i = 0; i < 10; i++) {
                    createConfettiPiece(Date.now() + i);
                }
            }, 300);

            // Stop after 5 seconds
            setTimeout(() => {
                clearInterval(confettiInterval);
            }, 5000);

            function createConfettiPiece(id) {
                const confetti = document.createElement('div');
                const color = colors[Math.floor(Math.random() * colors.length)];
                const type = confettiTypes[Math.floor(Math.random() * confettiTypes.length)];
                const size = Math.random() * 10 + 4; // 4-14px
                const left = Math.random() * 100;
                const duration = 2 + Math.random() * 3; // 2-5 seconds
                const delay = Math.random() * 2;

                // Set base styles
                confetti.style.position = 'absolute';
                confetti.style.left = `${left}%`;
                confetti.style.top = '-50px';
                confetti.style.opacity = '0';
                confetti.style.zIndex = '9999';
                confetti.style.pointerEvents = 'none';

                // Different shapes
                switch (type) {
                    case 'circle':
                        confetti.style.width = `${size}px`;
                        confetti.style.height = `${size}px`;
                        confetti.style.borderRadius = '50%';
                        confetti.style.backgroundColor = color;
                        break;
                    case 'rectangle':
                        confetti.style.width = `${size}px`;
                        confetti.style.height = `${size * 0.4}px`;
                        confetti.style.backgroundColor = color;
                        confetti.style.transform = `rotate(${Math.random() * 360}deg)`;
                        break;
                    case 'triangle':
                        confetti.style.width = '0';
                        confetti.style.height = '0';
                        confetti.style.borderLeft = `${size/2}px solid transparent`;
                        confetti.style.borderRight = `${size/2}px solid transparent`;
                        confetti.style.borderBottom = `${size}px solid ${color}`;
                        confetti.style.backgroundColor = 'transparent';
                        break;
                    case 'diamond':
                        confetti.style.width = `${size}px`;
                        confetti.style.height = `${size}px`;
                        confetti.style.backgroundColor = color;
                        confetti.style.transform = `rotate(45deg)`;
                        break;
                }

                // Random animation type
                const animationType = Math.random();

                if (animationType < 0.3) {
                    // Swaying fall
                    confetti.style.animation =
                        `confetti-fall ${duration}s ease-out ${delay}s forwards, confetti-sway ${duration/2}s ease-in-out ${delay}s infinite`;
                } else if (animationType < 0.6) {
                    // Spiral fall
                    confetti.style.animation = `spiral ${duration}s ease-in-out ${delay}s forwards`;
                } else {
                    // Normal fall with rotation
                    confetti.style.animation = `confetti-fall ${duration}s ease-out ${delay}s forwards`;
                }

                container.appendChild(confetti);

                // Remove confetti after animation
                setTimeout(() => {
                    if (confetti.parentNode === container) {
                        container.removeChild(confetti);
                    }
                }, (duration + delay) * 1000);
            }

            // Add some floating emoji celebrations too!
            setTimeout(() => {
                createEmojiCelebration();
            }, 500);

            function createEmojiCelebration() {
                const emojis = ['🎉', '🎊', '✨', '🌟', '💫', '🥳', '🎈', '🏆', '👏', '✅', '💙', '💚'];
                for (let i = 0; i < 20; i++) {
                    setTimeout(() => {
                        createEmoji(emojis[Math.floor(Math.random() * emojis.length)]);
                    }, i * 150);
                }
            }

            function createEmoji(emoji) {
                const emojiEl = document.createElement('div');
                emojiEl.textContent = emoji;
                emojiEl.style.position = 'fixed';
                emojiEl.style.fontSize = `${Math.random() * 24 + 16}px`;
                emojiEl.style.left = `${Math.random() * 100}%`;
                emojiEl.style.top = '100%';
                emojiEl.style.zIndex = '9998';
                emojiEl.style.opacity = '0.8';
                emojiEl.style.pointerEvents = 'none';
                emojiEl.style.animation = `emoji-rise ${3 + Math.random() * 3}s ease-out forwards`;

                document.body.appendChild(emojiEl);

                setTimeout(() => {
                    if (emojiEl.parentNode) {
                        emojiEl.remove();
                    }
                }, 6000);
            }

            // Make sure page is scrollable by checking if content exceeds viewport
            setTimeout(() => {
                const bodyHeight = document.body.scrollHeight;
                const viewportHeight = window.innerHeight;

                if (bodyHeight > viewportHeight) {
                    document.body.style.overflowY = 'auto';
                }
            }, 100);
        });
    </script>
</body>

</html>