<?php
$page_title = 'Survey Analytics';
$user = get_authenticated_user();
$content = '
    <div class="bg-white rounded-xl shadow-xl border border-gray-100 p-8 mb-8">
        <div class="mb-6">
            <h1 class="text-3xl font-extrabold text-gray-900">Survey Analytics</h1>
            <p class="text-gray-600 mt-2">Monitor responses and performance for your survey</p>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <h3 class="text-lg font-semibold text-blue-900">' . htmlspecialchars($survey['title']) . '</h3>';
if ($survey['description']) {
    $content .= '<p class="text-blue-700 mt-1">' . htmlspecialchars($survey['description']) . '</p>';
}
$content .= '
        </div>
    </div>

     '; // End of content before tabs

    // Store stats content for reuse
    $stats_content = '
         <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-4 md:p-6 group hover:shadow-xl transition-all duration-200 cursor-help" title="Total number of participants who started the survey">
             <div class="flex items-center">
                 <div class="p-2 md:p-3 bg-blue-100 rounded-lg group-hover:bg-blue-200 transition-colors">
                     <i class="fas fa-users text-blue-600 text-lg md:text-xl"></i>
                 </div>
                 <div class="ml-3 md:ml-4">
                     <h3 class="text-xl md:text-2xl font-bold text-gray-900">' . $stats['total_sessions'] . '</h3>
                     <p class="text-gray-600 text-xs md:text-sm">Total Participants</p>
                 </div>
             </div>
         </div>

         <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-4 md:p-6 group hover:shadow-xl transition-all duration-200 cursor-help" title="Percentage of participants who completed the entire survey">
             <div class="flex items-center">
                 <div class="p-2 md:p-3 bg-green-100 rounded-lg group-hover:bg-green-200 transition-colors">
                     <i class="fas fa-check-circle text-green-600 text-lg md:text-xl"></i>
                 </div>
                 <div class="ml-3 md:ml-4">
                     <h3 class="text-xl md:text-2xl font-bold text-gray-900">' . $stats['completion_rate'] . '%</h3>
                     <p class="text-gray-600 text-xs md:text-sm">Completion Rate</p>
                 </div>
             </div>
         </div>

         <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-4 md:p-6 group hover:shadow-xl transition-all duration-200 cursor-help" title="Number of participants who fully completed the survey">
             <div class="flex items-center">
                 <div class="p-2 md:p-3 bg-purple-100 rounded-lg group-hover:bg-purple-200 transition-colors">
                     <i class="fas fa-poll text-purple-600 text-lg md:text-xl"></i>
                 </div>
                 <div class="ml-3 md:ml-4">
                     <h3 class="text-xl md:text-2xl font-bold text-gray-900">' . $stats['completed_sessions'] . '</h3>
                     <p class="text-gray-600 text-xs md:text-sm">Completed Surveys</p>
                 </div>
             </div>
         </div>

         <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-4 md:p-6 group hover:shadow-xl transition-all duration-200 cursor-help" title="Total number of question responses collected">
             <div class="flex items-center">
                 <div class="p-2 md:p-3 bg-orange-100 rounded-lg group-hover:bg-orange-200 transition-colors">
                     <i class="fas fa-chart-bar text-orange-600 text-lg md:text-xl"></i>
                 </div>
                 <div class="ml-3 md:ml-4">
                     <h3 class="text-xl md:text-2xl font-bold text-gray-900">' . $stats['total_responses'] . '</h3>
                     <p class="text-gray-600 text-xs md:text-sm">Total Responses</p>
                 </div>
             </div>
         </div>
    ';

    $content .= '

     <div class="flex flex-col sm:flex-row gap-3 md:gap-4 mb-8">
         <a href="' . BASE_PATH . '/surveys/' . $survey['id'] . '/link"
             class="inline-flex items-center justify-center px-4 md:px-6 py-3 border border-transparent text-sm md:text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-sm hover:shadow-md">
             <i class="fas fa-share mr-2"></i>Share Survey
         </a>
         <a href="' . BASE_PATH . '/surveys/' . $survey['id'] . '/participants"
             class="inline-flex items-center justify-center px-4 md:px-6 py-3 border border-gray-300 text-sm md:text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-sm hover:shadow-md">
             <i class="fas fa-users mr-2"></i>View Participants
         </a>
         <a href="' . BASE_PATH . '/surveys/' . $survey['id'] . '/edit"
             class="inline-flex items-center justify-center px-4 md:px-6 py-3 border border-gray-300 text-sm md:text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-sm hover:shadow-md">
             <i class="fas fa-edit mr-2"></i>Edit Survey
         </a>
         <button type="button" onclick="exportToCSV()"
             class="inline-flex items-center justify-center px-4 md:px-6 py-3 border border-gray-300 text-sm md:text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-sm hover:shadow-md">
             <i class="fas fa-download mr-2"></i>Export CSV
         </button>
         <a href="' . BASE_PATH . '/dashboard"
             class="inline-flex items-center justify-center px-4 md:px-6 py-3 border border-gray-300 text-sm md:text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-sm hover:shadow-md">
             <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
         </a>
      </div>

      <!-- Analytics Overview -->
      <div class="bg-white rounded-xl shadow-xl border border-gray-100 p-4 md:p-6 mb-8">
          <div class="mb-6">
              <h2 class="text-2xl font-bold text-gray-900 mb-2">Analytics Overview</h2>
              <p class="text-gray-600">Comprehensive insights into your survey performance</p>
          </div>

          <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">' . $stats_content . '</div>

          <!-- Overall Insights Charts -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
              <div class="bg-white border border-gray-200 rounded-lg p-4">
                  <h3 class="text-lg font-semibold text-gray-900 mb-4">Overall Response Distribution</h3>
                  <div class="h-64">
                      <canvas id="overall-distribution-chart"></canvas>
                  </div>
              </div>

              <div class="bg-white border border-gray-200 rounded-lg p-4">
                  <h3 class="text-lg font-semibold text-gray-900 mb-4">Completion Funnel</h3>
                  <div class="h-64">
                      <canvas id="completion-funnel-chart"></canvas>
                  </div>
              </div>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <div class="bg-white border border-gray-200 rounded-lg p-4">
                  <h3 class="text-lg font-semibold text-gray-900 mb-4">Response Timeline</h3>
                  <div class="h-64">
                      <canvas id="response-timeline-chart"></canvas>
                  </div>
              </div>

              <div class="bg-white border border-gray-200 rounded-lg p-4">
                  <h3 class="text-lg font-semibold text-gray-900 mb-4">Question Performance</h3>
                  <div class="h-64">
                      <canvas id="question-performance-chart"></canvas>
                  </div>
              </div>
          </div>
       </div>

    <script>
        // Response distribution data from PHP
        const responseDistributions = ' . json_encode($response_distributions) . ';

        // Render overall insights charts
        document.addEventListener("DOMContentLoaded", function() {
            renderOverallDistributionChart();
            renderCompletionFunnelChart();
            renderResponseTimelineChart();
            renderQuestionPerformanceChart();
        });

        function renderOverallDistributionChart() {
            const canvas = document.getElementById("overall-distribution-chart");
            if (!canvas) return;

            const ctx = canvas.getContext("2d");
            const distributions = ' . json_encode($response_distributions) . ';

            // Aggregate all scale question responses
            const scaleData = {1: 0, 2: 0, 3: 0, 4: 0, 5: 0};
            let hasScaleData = false;

            Object.values(distributions).forEach(distribution => {
                if (distribution.type === "scale") {
                    Object.entries(distribution.data).forEach(([rating, count]) => {
                        scaleData[rating] += count;
                        if (count > 0) hasScaleData = true;
                    });
                }
            });

            if (!hasScaleData) {
                // No scale data available
                ctx.font = "14px Arial";
                ctx.fillStyle = "#6b7280";
                ctx.textAlign = "center";
                ctx.fillText("No scale questions with responses", canvas.width / 2, canvas.height / 2);
                return;
            }

            new Chart(ctx, {
                type: "bar",
                data: {
                    labels: ["1", "2", "3", "4", "5"],
                    datasets: [{
                        label: "Total Responses",
                        data: Object.values(scaleData),
                        backgroundColor: [
                            "rgba(239, 68, 68, 0.7)",
                            "rgba(245, 158, 11, 0.7)",
                            "rgba(234, 179, 8, 0.7)",
                            "rgba(132, 204, 22, 0.7)",
                            "rgba(34, 197, 94, 0.7)"
                        ],
                        borderColor: [
                            "rgba(239, 68, 68, 1)",
                            "rgba(245, 158, 11, 1)",
                            "rgba(234, 179, 8, 1)",
                            "rgba(132, 204, 22, 1)",
                            "rgba(34, 197, 94, 1)"
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const value = context.parsed.y || context.parsed;
                                    const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                    return `${context.label} star rating: ${value} responses (${percentage}%)`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                precision: 0
                            }
                        }
                    }
                }
            });
        }

        function renderCompletionFunnelChart() {
            const canvas = document.getElementById("completion-funnel-chart");
            if (!canvas) return;

            const ctx = canvas.getContext("2d");
            const stats = ' . json_encode($stats) . ';

            new Chart(ctx, {
                type: "doughnut",
                data: {
                    labels: ["Completed", "Started (Not Completed)"],
                    datasets: [{
                        data: [stats.completed_sessions, stats.total_sessions - stats.completed_sessions],
                        backgroundColor: [
                            "rgba(34, 197, 94, 0.8)",
                            "rgba(239, 68, 68, 0.8)"
                        ],
                        borderColor: [
                            "rgba(34, 197, 94, 1)",
                            "rgba(239, 68, 68, 1)"
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: "bottom"
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((context.parsed / total) * 100);
                                    return `${context.label}: ${context.parsed} participants (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }

        function renderResponseTimelineChart() {
            const canvas = document.getElementById("response-timeline-chart");
            if (!canvas) return;

            const ctx = canvas.getContext("2d");
            const timelineData = ' . json_encode($response_timeline ?? []) . ';

            if (timelineData.length === 0) {
                // No data available
                ctx.font = "14px Arial";
                ctx.fillStyle = "#6b7280";
                ctx.textAlign = "center";
                ctx.fillText("No timeline data available", canvas.width / 2, canvas.height / 2);
                return;
            }

            new Chart(ctx, {
                type: "line",
                data: {
                    labels: timelineData.map(item => new Date(item.response_date).toLocaleDateString()),
                    datasets: [{
                        label: "Daily Responses",
                        data: timelineData.map(item => parseInt(item.daily_responses)),
                        borderColor: "rgba(59, 130, 246, 1)",
                        backgroundColor: "rgba(59, 130, 246, 0.1)",
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                precision: 0
                            }
                        }
                    }
                }
            });
        }

        function renderQuestionPerformanceChart() {
            const canvas = document.getElementById("question-performance-chart");
            if (!canvas) return;

            const ctx = canvas.getContext("2d");
            const questionAverages = ' . json_encode($question_averages) . ';

            // Sort questions by response count
            const sortedQuestions = questionAverages
                .filter(q => q.response_count > 0)
                .sort((a, b) => b.response_count - a.response_count)
                .slice(0, 10); // Top 10

            new Chart(ctx, {
                type: "bar",
                data: {
                    labels: sortedQuestions.map(q => q.code),
                    datasets: [{
                        label: "Response Count",
                        data: sortedQuestions.map(q => q.response_count),
                        backgroundColor: "rgba(147, 51, 234, 0.7)",
                        borderColor: "rgba(147, 51, 234, 1)",
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: "y",
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                precision: 0
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `${context.label}: ${context.parsed} responses`;
                                }
                            }
                        }
                    }
                }
            });
        }

        // CSV Export functionality
        function exportToCSV() {
            try {
                const surveyData = ' . json_encode($question_averages) . ';
                const surveyStats = ' . json_encode($stats) . ';
                const surveyInfo = ' . json_encode(['title' => $survey['title'], 'description' => $survey['description']]) . ';

                let csvContent = "data:text/csv;charset=utf-8,";

                // Add survey info header
                csvContent += "Survey Title,Description\\n";
                csvContent += `"${surveyInfo.title}","${surveyInfo.description}"\\n\\n`;

                // Add survey statistics
                csvContent += "Survey Statistics\\n";
                csvContent += "Metric,Value\\n";
                csvContent += `Total Participants,${surveyStats.total_sessions}\\n`;
                csvContent += `Completion Rate,${surveyStats.completion_rate}%\\n`;
                csvContent += `Completed Surveys,${surveyStats.completed_sessions}\\n`;
                csvContent += `Total Responses,${surveyStats.total_responses}\\n\\n`;

                // Add question results
                csvContent += "Question Results\\n";
                csvContent += "Question Code,Question Text,Type,Average Score,Response Count,First Response,Last Response\\n";

                surveyData.forEach(question => {
                    const avgScore = (question.type === "scale" && question.avg_score !== null && question.avg_score !== undefined) ? parseFloat(question.avg_score).toFixed(1) : "N/A";
                    csvContent += `"${question.code}","${question.text.replace(/"/g, \'""\')}","${question.type}","${avgScore}","${question.response_count}","${question.first_response || \'\'}","${question.last_response || \'\'}"\\n`;
                });

                // Create and trigger download
                const encodedUri = encodeURI(csvContent);
                const link = document.createElement("a");
                link.setAttribute("href", encodedUri);
                link.setAttribute("download", `${surveyInfo.title.replace(/[^a-z0-9]/gi, \'_\').toLowerCase()}_analytics.csv`);
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

            } catch (error) {
                alert(\'Error during CSV export: \' + error.message);
                console.error(\'CSV export error:\', error);
            }
        }
    </script>

    <style>
        /* Enhanced tooltip styles */
        [title]:hover::after {
            content: attr(title);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            white-space: nowrap;
            z-index: 1000;
            margin-bottom: 5px;
        }

        [title]:hover::before {
            content: "";
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            border: 5px solid transparent;
            border-top-color: rgba(0, 0, 0, 0.8);
            margin-bottom: -5px;
            z-index: 1000;
        }

        /* Mobile responsiveness improvements */
        @media (max-width: 640px) {
            .grid-cols-2 {
                grid-template-columns: 1fr;
            }

            .flex-col {
                align-items: stretch;
            }

            .space-x-8 > * + * {
                margin-left: 1rem;
            }
        }

        /* Progress bar animation */
        @keyframes progress-fill {
            from { width: 0%; }
            to { width: var(--progress-width); }
        }

        .progress-bar {
            animation: progress-fill 1s ease-out forwards;
        }
    </style>
';

include __DIR__ . '/../layouts/researcher.php';
?>