@extends('layouts.app')

@section('content')
    <div class="w-full">
        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex justify-between text-sm font-medium text-gray-500 mb-2">
                <span>Module {{ $moduleId }} of {{ $totalModules }}, Group {{ $page }} of {{ $totalPages }}</span>
                <span>{{ round((($moduleId - 1 + ($page - 1) / $totalPages) / $totalModules) * 100) }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ (($moduleId - 1 + ($page - 1) / $totalPages) / $totalModules) * 100 }}%"></div>
            </div>
        </div>

            <form method="POST" action="/survey?module={{ $moduleId }}" class="bg-white shadow-lg rounded-lg overflow-hidden">
                <input type="hidden" name="page" value="{{ $page }}">
            <div class="bg-blue-50 px-6 py-4 border-b border-blue-100">
                <h2 class="text-xl font-bold text-blue-900">{{ $module['title'] }}</h2>
                <h3 class="text-lg font-semibold text-blue-800 mt-2">{{ $module['group'] }}</h3>
                <p class="text-sm text-blue-700 mt-1">Please rate the following statements on a scale from Strongly Disagree to Strongly Agree.</p>
            </div>

            <div class="p-6 space-y-6">
                @foreach ($module['questions'] as $id => $text)
                    <div class="pb-4 border-b border-gray-100 last:border-0">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <p class="font-medium text-gray-800 mb-3 md:mb-0 md:flex-1 md:pr-4"><span
                                    class="text-gray-400 text-xs mr-2">{{ $id }}</span>{{ $text }}</p>

                            <div class="flex flex-col md:flex-row md:space-x-4 md:space-y-0 space-y-2">
                                @php
                                    $labels = ['Strongly Disagree', 'Disagree', 'Neutral', 'Agree', 'Strongly Agree'];
                                @endphp
                                @for ($i = 1; $i <= 5; $i++)
                                    <label class="flex items-center cursor-pointer group">
                                        <input type="radio" name="{{ $id }}" value="{{ $i }}"
                                            class="peer sr-only">
                                        <div
                                            class="w-5 h-5 rounded border border-gray-300 flex items-center justify-center peer-checked:bg-blue-600 peer-checked:border-blue-600 transition-all group-hover:border-blue-400">
                                            <i class="fas fa-check text-white text-xs peer-checked:block hidden"></i>
                                        </div>
                                        <span class="ml-2 text-sm text-gray-700 peer-checked:text-blue-600">{{ $labels[$i-1] }}</span>
                                    </label>
                                @endfor
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="bg-gray-50 px-6 py-4 flex justify-between items-center">
                <div class="flex space-x-2">
                    @if ($moduleId > 1)
                        <a href="/survey?module={{ $moduleId - 1 }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded">Previous Module</a>
                    @endif
                    @if ($page > 1)
                        <a href="/survey?module={{ $moduleId }}&page={{ $page - 1 }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded">Previous Group</a>
                    @endif
                </div>

                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded shadow-sm transition-colors">
                    {{ $page == $totalPages ? ($moduleId == $totalModules ? 'Submit Survey' : 'Next Module') : 'Next Group' }}
                </button>
            </div>
        </form>
    </div>
@endsection
