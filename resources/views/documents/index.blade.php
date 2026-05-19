<x-app-layout>
    <div class="max-w-6xl mx-auto p-6">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">My Documents</h1>

            <a href="{{ route('documents.create') }}"
               class="bg-blue-500 text-white px-4 py-2 rounded">
                New Document
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Owned by Me</h2>

                <div class="space-y-4">
                    @forelse ($ownedDocuments as $document)
                        <div class="border-b pb-4 flex justify-between items-center">
                            <div>
                                <a href="{{ route('documents.show', $document) }}"
                                   class="text-lg font-semibold text-blue-600">
                                    {{ $document->title }}
                                </a>

                                <p class="text-sm text-gray-500">
                                    Created: {{ $document->created_at->diffForHumans() }}
                                </p>
                            </div>

                            <div class="flex gap-2">
                                <a href="{{ route('documents.show', $document) }}"
                                   class="bg-gray-200 px-4 py-2 rounded text-sm">
                                    Open
                                </a>

                                <a href="{{ route('documents.edit', $document) }}"
                                   class="bg-blue-500 text-white px-4 py-2 rounded text-sm">
                                    Edit
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500">Belum ada document.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Shared With Me</h2>

                <div class="space-y-4">
                    @forelse ($sharedDocuments as $document)
                        <div class="border-b pb-4 flex justify-between items-center">
                            <div>
                                <a href="{{ route('documents.show', $document) }}"
                                   class="text-lg font-semibold text-blue-600">
                                    {{ $document->title }}
                                </a>

                                <p class="text-sm text-gray-500">
                                    Owner: {{ $document->user->name ?? 'Unknown' }}
                                </p>
                            </div>

                            <div class="flex gap-2">
                                <a href="{{ route('documents.show', $document) }}"
                                   class="bg-gray-200 px-4 py-2 rounded text-sm">
                                    Open
                                </a>

                                <a href="{{ route('documents.edit', $document) }}"
                                   class="bg-blue-500 text-white px-4 py-2 rounded text-sm">
                                    Edit
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500">Belum ada shared document.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>