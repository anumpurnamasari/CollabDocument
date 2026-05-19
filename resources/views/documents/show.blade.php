<x-app-layout>
    <div class="max-w-5xl mx-auto p-6">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h1 class="text-3xl font-bold">
                        {{ $document->title }}
                    </h1>

                    <p class="text-sm text-gray-500">
                        Owner: {{ $document->user->name ?? 'Unknown' }}
                    </p>
                </div>

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

            @if (auth()->id() === $document->user_id)
                <div class="mt-8 border-t pt-6">
                    <h2 class="text-xl font-semibold mb-4">Share Document</h2>

                    <form action="{{ route('documents.share', $document) }}" method="POST" class="flex gap-3">
                        @csrf

                        <input type="email"
                               name="email"
                               placeholder="Email collaborator"
                               class="w-full rounded border px-4 py-2"
                               required>

                        <button class="bg-green-500 text-white px-4 py-2 rounded">
                            Share
                        </button>
                    </form>

                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <div class="mt-4">
                        <p class="font-semibold mb-2">Collaborators:</p>
                        <div class="flex flex-wrap gap-2">
                            @forelse ($document->collaborators as $collaborator)
                                <span class="rounded-full bg-gray-100 px-3 py-1 text-sm">
                                    {{ $collaborator->user->name ?? 'Unknown' }} ({{ $collaborator->permission }})
                                </span>
                            @empty
                                <span class="text-sm text-gray-500">Belum ada collaborator.</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>