<x-app-layout>
    <div class="max-w-5xl mx-auto p-6">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-3xl font-bold">
                    {{ $document->title }}
                </h1>

                <div class="flex gap-2">
                    <a href="{{ route('documents.history', $document) }}"
                       class="bg-gray-200 px-4 py-2 rounded">
                        History
                    </a>

                    <a href="{{ route('documents.edit', $document) }}"
                       class="bg-blue-500 text-white px-4 py-2 rounded">
                        Edit
                    </a>
                </div>
            </div>

            <div class="border rounded p-4 min-h-[400px]">
                {!! $document->content !!}
            </div>
        </div>
    </div>
</x-app-layout>