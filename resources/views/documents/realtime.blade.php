<x-app-layout>
    <div class="max-w-5xl mx-auto p-6">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold">{{ $document->title }}</h1>
                    <p class="text-sm text-gray-500">Realtime mode</p>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('documents.edit', $document) }}"
                       class="bg-gray-200 px-4 py-2 rounded">
                        Back to Editor
                    </a>

                    <a href="{{ route('documents.history', $document) }}"
                       class="bg-yellow-400 text-white px-4 py-2 rounded">
                        History
                    </a>
                </div>
            </div>

            <div
                id="realtime-editor"
                class="tiptap border rounded-lg p-4 min-h-[500px]"
                data-document-id="{{ $document->id }}"
                data-user-name="{{ auth()->user()->name }}"
                data-user-color="#3b82f6"
            ></div>
        </div>
    </div>

    @vite(['resources/css/app.css', 'resources/js/realtime.js'])
</x-app-layout>