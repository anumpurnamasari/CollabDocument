<x-app-layout>
    <div class="max-w-5xl mx-auto p-6">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">
                My Documents
            </h1>

            <a href="{{ route('documents.create') }}"
               class="bg-blue-500 text-white px-4 py-2 rounded">
                New Document
            </a>
        </div>

        <div class="bg-white shadow rounded-lg p-6">

            @forelse ($documents as $document)

                <div class="border-b py-4">

                    <a href="{{ route('documents.show', $document) }}"
                       class="text-lg font-semibold text-blue-600">
                        {{ $document->title }}
                    </a>

                    <p class="text-sm text-gray-500">
                        Created:
                        {{ $document->created_at->diffForHumans() }}
                    </p>

                </div>

            @empty

                <p>No documents yet.</p>

            @endforelse

        </div>
    </div>
</x-app-layout>