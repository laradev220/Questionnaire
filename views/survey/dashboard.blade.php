@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto py-10">
        <div class="bg-white rounded-xl shadow-xl border border-gray-100 p-8">
            <h1 class="text-3xl font-extrabold text-gray-900 mb-4">Welcome, {{ $_SESSION['participant_name'] ?? 'Participant' }}
            </h1>
            <p class="text-gray-600 mb-8 max-w-2xl">
                Thank you for participating in our research on Sustainable Human Resource Management and Organizational
                Resilience.
                The survey consists of 6 core modules and should take approximately 10-15 minutes to complete.
            </p>

            <div class="bg-blue-50 rounded-lg p-6 border-l-4 border-blue-500 mb-8 flex items-start space-x-4">
                <i class="fa-solid fa-circle-info text-blue-500 text-xl flex-shrink-0 mt-0.5"></i>
                <div>
                    <h3 class="font-semibold text-blue-900">Instructions</h3>
                    <p class="text-blue-700 text-sm mt-1">
                        Please use the 5-point Likert scale to rate each statement (1 = Strongly Disagree, 5 = Strongly
                        Agree).
                        You can save your progress and return later. Your current progress will determine where you start.
                    </p>
                </div>
            </div>

            @php
                $currentModule = $session['current_module'] ?? 1;
                $moduleCount = 6; // Total modules based on the schema
                if ($session['is_completed'] ?? false) {
                    $progress = 100;
                    $currentModule = $moduleCount;
                } else {
                    $progress = round((($currentModule - 1) / $moduleCount) * 100);
                }
            @endphp

            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Your Progress</h3>
                <div class="flex justify-between text-sm font-medium text-gray-500 mb-2">
                    <span>Module {{ $currentModule }} of {{ $moduleCount }}</span>
                    <span>{{ $progress }}% Complete</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-500"
                        style="width: {{ $progress }}%"></div>
                </div>
            </div>

            @if ($session['is_completed'] ?? false)
                <div class="bg-green-100 text-green-800 p-4 rounded-lg mb-4 font-medium">
                    <i class="fa-solid fa-check-circle mr-2"></i>Survey Completed. Thank you!
                </div>
                <a href="/thank-you" class="text-blue-600 hover:text-blue-800 font-medium">View Final Confirmation</a>
            @else
                <a href="/survey?module={{ $currentModule }}"
                    class="inline-flex items-center justify-center space-x-2 bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors shadow-md hover:shadow-lg">
                    <span>{{ $currentModule > 1 ? 'Continue Survey' : 'Start Questionnaire' }}</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            @endif
        </div>
    </div>
@endsection
