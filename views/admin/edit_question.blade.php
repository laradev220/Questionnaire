@extends('layouts.admin')

@section('page-title', 'Edit Question')

@section('content')
    <div class="bg-white rounded-xl shadow-xl border border-gray-100 p-8">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-6">Edit Question</h1>

            <form method="POST" action="/admin/questions/edit/{{ $question['id'] }}" class="space-y-6">
                <div>
                    <label for="module" class="block text-sm font-medium text-gray-700">Module</label>
                    <input type="text" name="module" id="module" value="{{ $question['module'] }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="group" class="block text-sm font-medium text-gray-700">Group</label>
                    <input type="text" name="group" id="group" value="{{ $question['group'] }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700">Code</label>
                    <input type="text" name="code" id="code" value="{{ $question['code'] }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="text" class="block text-sm font-medium text-gray-700">Text</label>
                    <textarea name="text" id="text" rows="4" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ $question['text'] }}</textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="/admin/questions" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded font-semibold">Cancel</a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-semibold">Update Question</button>
                </div>
            </form>
    </div>
@endsection