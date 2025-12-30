<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Researcher Dashboard'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .sidebar {
            background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }
        .sidebar-nav-item {
            transition: all 0.3s ease;
            color: #475569;
        }
        .sidebar-nav-item:hover {
            background: rgba(59, 130, 246, 0.1);
            color: #1e40af;
            transform: translateX(3px);
        }
        .sidebar-nav-item.active {
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.15) 0%, rgba(59, 130, 246, 0.08) 100%);
            color: #1e40af;
            font-weight: 600;
            border-left: 3px solid #3b82f6;
        }
        .sidebar-nav-item.active i {
            color: #3b82f6;
        }
        .submenu {
            background: rgba(241, 245, 249, 0.5);
            border-left: 2px solid rgba(59, 130, 246, 0.2);
        }
        .stat-item {
            background: rgba(255, 255, 255, 0.7);
            border-radius: 8px;
            padding: 8px 12px;
            margin-bottom: 4px;
            border: 1px solid rgba(226, 232, 240, 0.5);
        }
        .stat-item:hover {
            background: rgba(255, 255, 255, 0.9);
        }
        .submenu-item {
            transition: all 0.2s ease;
            color: #64748b;
        }
        .submenu-item:hover {
            background: rgba(59, 130, 246, 0.1);
            color: #1e40af;
            padding-left: 20px;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-slate-50 via-white to-blue-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 sidebar text-slate-700 border-r border-slate-200">
            <div class="p-6 border-b border-slate-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center shadow-md">
                        <i class="fas fa-microscope text-white text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 tracking-tight">ResearchSync</h2>
                        <p class="text-xs text-slate-500">Researcher Panel</p>
                    </div>
                </div>
            </div>
            
            <nav class="mt-6 px-4 space-y-1">
                <!-- Dashboard -->
                <a href="<?php echo BASE_PATH; ?>/dashboard" 
                   class="sidebar-nav-item flex items-center px-4 py-3 rounded-lg">
                    <i class="fas fa-tachometer-alt w-5 mr-3 text-slate-500"></i>
                    <span>Dashboard</span>
                </a>

                <!-- Manage Questions -->
                <div class="mt-2">
                    <div class="sidebar-nav-item flex items-center justify-between px-4 py-3 rounded-lg cursor-pointer" 
                         onclick="toggleSubmenu('questions-submenu', 'questions-chevron')">
                        <div class="flex items-center">
                            <i class="fas fa-question-circle w-5 mr-3 text-slate-500"></i>
                            <span class="font-medium">Manage Questions</span>
                        </div>
                        <i id="questions-chevron" class="fas fa-chevron-down text-slate-400 text-xs transition-transform duration-200"></i>
                    </div>
                    <div id="questions-submenu" class="submenu ml-4 mt-2 space-y-1 rounded-lg py-2 hidden">
                        <a href="<?php echo BASE_PATH; ?>/questions"
                           class="submenu-item block px-4 py-2 text-sm rounded transition-all duration-200">
                            <i class="fas fa-list mr-2 text-slate-400"></i>My Questions
                        </a>
                        <a href="<?php echo BASE_PATH; ?>/questions/create"
                           class="submenu-item block px-4 py-2 text-sm rounded transition-all duration-200">
                            <i class="fas fa-plus mr-2 text-slate-400"></i>Create Question
                        </a>
                    </div>
                </div>

                <!-- Manage Surveys (Moved below Questions) -->
                <div class="mt-2">
                    <div class="sidebar-nav-item flex items-center justify-between px-4 py-3 rounded-lg cursor-pointer" 
                         onclick="toggleSubmenu('surveys-submenu', 'surveys-chevron')">
                        <div class="flex items-center">
                            <i class="fas fa-poll w-5 mr-3 text-slate-500"></i>
                            <span class="font-medium">Manage Surveys</span>
                        </div>
                        <i id="surveys-chevron" class="fas fa-chevron-down text-slate-400 text-xs transition-transform duration-200"></i>
                    </div>
                    <div id="surveys-submenu" class="submenu ml-4 mt-2 space-y-1 rounded-lg py-2 hidden">
                        <a href="<?php echo BASE_PATH; ?>/dashboard"
                           class="submenu-item block px-4 py-2 text-sm rounded transition-all duration-200">
                            <i class="fas fa-list mr-2 text-slate-400"></i>My Surveys
                        </a>
                        <a href="<?php echo BASE_PATH; ?>/surveys/create"
                           class="submenu-item block px-4 py-2 text-sm rounded transition-all duration-200">
                            <i class="fas fa-plus mr-2 text-slate-400"></i>Create Survey
                        </a>
                        
                        <!-- Recent Surveys -->
                        <div class="px-4 py-2 mt-2">
                            <div class="text-xs text-slate-500 uppercase tracking-wide font-medium mb-2">Recent Surveys</div>
                            <div class="space-y-1">
                                <?php
                                $db = get_db_connection();
                                $stmt = $db->prepare("SELECT id, title FROM surveys WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
                                $stmt->execute([$user['id']]);
                                $recent_surveys = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($recent_surveys as $survey) {
                                    echo '<a href="' . BASE_PATH . '/surveys/' . $survey['id'] . '/analytics" 
                                          class="submenu-item block px-3 py-2 text-xs rounded transition-all duration-200 truncate" 
                                          title="' . htmlspecialchars($survey['title']) . '">
                                            <i class="fas fa-chart-bar mr-2 text-slate-400"></i>
                                            ' . htmlspecialchars(substr($survey['title'], 0, 20)) . (strlen($survey['title']) > 20 ? '...' : '') . 
                                          '</a>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="mt-2">
                    <div class="sidebar-nav-item flex items-center justify-between px-4 py-3 rounded-lg cursor-pointer" 
                         onclick="toggleSubmenu('stats-submenu', 'stats-chevron')">
                        <div class="flex items-center">
                            <i class="fas fa-chart-line w-5 mr-3 text-slate-500"></i>
                            <span class="font-medium">Quick Stats</span>
                        </div>
                        <i id="stats-chevron" class="fas fa-chevron-down text-slate-400 text-xs transition-transform duration-200"></i>
                    </div>
                    <div id="stats-submenu" class="submenu ml-4 mt-2 space-y-1 rounded-lg py-2 hidden">
                        <?php
                        $total_surveys = count($recent_surveys);
                        $stmt = $db->prepare("SELECT COUNT(DISTINCT p.id) as participants, COUNT(DISTINCT r.id) as responses FROM surveys s LEFT JOIN survey_sessions ss ON s.id = ss.survey_id LEFT JOIN participants p ON ss.participant_id = p.id LEFT JOIN responses r ON ss.id = r.session_id WHERE s.user_id = ?");
                        $stmt->execute([$user['id']]);
                        $stats = $stmt->fetch();
                        $total_participants = $stats['participants'];
                        $total_responses = $stats['responses'];
                        $stmt = $db->prepare("SELECT COUNT(*) as questions FROM questions WHERE user_id = ?");
                        $stmt->execute([$user['id']]);
                        $total_questions = $stmt->fetch()['questions'];
                        ?>
                        <div class="px-4 py-3 space-y-3">
                            <div class="stat-item flex justify-between items-center">
                                <span class="text-sm text-slate-600">Surveys</span>
                                <span class="font-bold text-slate-900"><?php echo $total_surveys; ?></span>
                            </div>
                            <div class="stat-item flex justify-between items-center">
                                <span class="text-sm text-slate-600">Participants</span>
                                <span class="font-bold text-slate-900"><?php echo $total_participants; ?></span>
                            </div>
                            <div class="stat-item flex justify-between items-center">
                                <span class="text-sm text-slate-600">Questions</span>
                                <span class="font-bold text-slate-900"><?php echo $total_questions; ?></span>
                            </div>
                            <div class="stat-item flex justify-between items-center">
                                <span class="text-sm text-slate-600">Responses</span>
                                <span class="font-bold text-slate-900"><?php echo $total_responses; ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile -->
                <a href="<?php echo BASE_PATH; ?>/profile" 
                   class="sidebar-nav-item flex items-center px-4 py-3 rounded-lg mt-4">
                    <i class="fas fa-user w-5 mr-3 text-slate-500"></i>
                    <span>Profile</span>
                </a>

                <!-- Logout -->
                <a href="<?php echo BASE_PATH; ?>/logout" 
                   class="sidebar-nav-item flex items-center px-4 py-3 rounded-lg hover:bg-red-50 hover:text-red-600">
                    <i class="fas fa-sign-out-alt w-5 mr-3 text-slate-500"></i>
                    <span>Logout</span>
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white/90 backdrop-blur-sm border-b border-slate-200 shadow-sm px-6 py-4">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight"><?php echo $page_title ?? 'Researcher Dashboard'; ?></h1>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-user text-blue-600 text-sm"></i>
                            </div>
                            <span class="text-sm text-slate-700 font-medium"><?php echo htmlspecialchars($user['name']); ?></span>
                        </div>
                    </div>
                </div>
            </header>
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gradient-to-br from-slate-50 via-white to-blue-50 p-6">
                <?php echo $content; ?>
            </main>
        </div>
    </div>

    <script>
        // Initialize dropdowns on page load
        document.addEventListener('DOMContentLoaded', function() {
            // First, hide all submenus
            const submenus = ['surveys-submenu', 'questions-submenu', 'stats-submenu'];
            submenus.forEach(function(submenuId) {
                const submenu = document.getElementById(submenuId);
                if (submenu) {
                    submenu.style.display = 'none';
                }
            });

            // Reset chevron rotations
            const chevrons = ['surveys-chevron', 'questions-chevron', 'stats-chevron'];
            chevrons.forEach(function(chevronId) {
                const chevron = document.getElementById(chevronId);
                if (chevron) {
                    chevron.style.transform = 'rotate(0deg)';
                }
            });

            // Check if we need to keep a dropdown open based on current page
            keepActiveDropdownOpen();

            // Add active tab highlighting
            highlightActiveTab();
        });

        function toggleSubmenu(submenuId, chevronId) {
            const submenu = document.getElementById(submenuId);
            const chevron = document.getElementById(chevronId);
            
            // Close all other submenus
            const allSubmenus = ['surveys-submenu', 'questions-submenu', 'stats-submenu'];
            const allChevrons = ['surveys-chevron', 'questions-chevron', 'stats-chevron'];
            
            allSubmenus.forEach(function(id) {
                if (id !== submenuId) {
                    const otherSubmenu = document.getElementById(id);
                    if (otherSubmenu) {
                        otherSubmenu.style.display = 'none';
                    }
                }
            });
            
            allChevrons.forEach(function(id) {
                if (id !== chevronId) {
                    const otherChevron = document.getElementById(id);
                    if (otherChevron) {
                        otherChevron.style.transform = 'rotate(0deg)';
                    }
                }
            });
            
            // Toggle current submenu
            if (submenu.style.display === 'none' || !submenu.style.display) {
                submenu.style.display = 'block';
                chevron.style.transform = 'rotate(180deg)';
            } else {
                submenu.style.display = 'none';
                chevron.style.transform = 'rotate(0deg)';
            }
        }

        function keepActiveDropdownOpen() {
            const currentPath = window.location.pathname;

            // Define routes that should keep dropdowns open
            const dropdownRoutes = {
                'questions': /^\/questions/,
                'surveys': /^\/surveys/
            };

            // Check if current page is in a dropdown section
            for (const [section, pattern] of Object.entries(dropdownRoutes)) {
                if (pattern.test(currentPath)) {
                    if (section === 'questions') {
                        const submenu = document.getElementById('questions-submenu');
                        const chevron = document.getElementById('questions-chevron');
                        if (submenu && chevron) {
                            submenu.style.display = 'block';
                            chevron.style.transform = 'rotate(180deg)';
                        }
                    } else if (section === 'surveys') {
                        const submenu = document.getElementById('surveys-submenu');
                        const chevron = document.getElementById('surveys-chevron');
                        if (submenu && chevron) {
                            submenu.style.display = 'block';
                            chevron.style.transform = 'rotate(180deg)';
                        }
                    }
                    break;
                }
            }
        }

        function highlightActiveTab() {
            const currentPath = window.location.pathname;
            const navItems = document.querySelectorAll('.sidebar-nav-item');

            // Reset all active states
            navItems.forEach(item => {
                item.classList.remove('active');
                // Reset icon colors
                const icon = item.querySelector('i');
                if (icon) {
                    icon.classList.remove('text-blue-500');
                    icon.classList.add('text-slate-500');
                }
            });

            // Also reset submenu items
            const submenuItems = document.querySelectorAll('.submenu-item');
            submenuItems.forEach(item => {
                item.classList.remove('bg-blue-50', 'text-blue-700');
                const subIcon = item.querySelector('i');
                if (subIcon) {
                    subIcon.classList.remove('text-blue-500');
                    subIcon.classList.add('text-slate-400');
                }
            });

            // Define routes for each section
            const routes = {
                'dashboard': /^\/(dashboard)?$/,
                'questions': /^\/questions/,
                'surveys': /^\/surveys/,
                'profile': /^\/profile$/
            };

            // Find matching route and highlight
            for (const [section, pattern] of Object.entries(routes)) {
                if (pattern.test(currentPath)) {
                    if (section === 'dashboard') {
                        const dashboardLink = document.querySelector('a[href*="/dashboard"]');
                        if (dashboardLink) {
                            dashboardLink.classList.add('active');
                            const icon = dashboardLink.querySelector('i');
                            if (icon) {
                                icon.classList.remove('text-slate-500');
                                icon.classList.add('text-blue-500');
                            }
                        }
                    } else if (section === 'questions') {
                        const questionsHeader = document.querySelector('[onclick*="questions-submenu"]');
                        if (questionsHeader) {
                            questionsHeader.classList.add('active');
                            const icon = questionsHeader.querySelector('i');
                            if (icon) {
                                icon.classList.remove('text-slate-500');
                                icon.classList.add('text-blue-500');
                            }
                            
                            // Also highlight the specific submenu item if we're on a question page
                            const questionLinks = document.querySelectorAll('#questions-submenu a');
                            questionLinks.forEach(link => {
                                if (link.href === window.location.href || 
                                    (currentPath.includes('/questions') && 
                                     (currentPath.includes('/create') === link.href.includes('/create')))) {
                                    link.classList.add('bg-blue-50', 'text-blue-700');
                                    const linkIcon = link.querySelector('i');
                                    if (linkIcon) {
                                        linkIcon.classList.remove('text-slate-400');
                                        linkIcon.classList.add('text-blue-500');
                                    }
                                }
                            });
                        }
                    } else if (section === 'surveys') {
                        const surveysHeader = document.querySelector('[onclick*="surveys-submenu"]');
                        if (surveysHeader) {
                            surveysHeader.classList.add('active');
                            const icon = surveysHeader.querySelector('i');
                            if (icon) {
                                icon.classList.remove('text-slate-500');
                                icon.classList.add('text-blue-500');
                            }
                        }
                    } else if (section === 'profile') {
                        const profileLink = document.querySelector('a[href*="/profile"]');
                        if (profileLink) {
                            profileLink.classList.add('active');
                            const icon = profileLink.querySelector('i');
                            if (icon) {
                                icon.classList.remove('text-slate-500');
                                icon.classList.add('text-blue-500');
                            }
                        }
                    }
                    break;
                }
            }
        }
    </script>
</body>

</html>
