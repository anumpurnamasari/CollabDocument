@php use Illuminate\Support\Str; @endphp

<x-app-layout>
    <div class="max-w-5xl mx-auto p-6">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Version History</h1>

                <a href="{{ route('documents.edit', $document) }}"
                   class="bg-gray-200 px-4 py-2 rounded">
                    Back to Editor
                </a>
            </div>

            <div class="space-y-4">
                @forelse ($revisions as $revision)
                    <div class="border rounded-lg p-4">
                        <div class="flex justify-between items-center mb-2">
                            <div>
                                <p class="font-semibold">{{ $revision->title }}</p>
                                <p class="text-sm text-gray-500">
                                    {{ $revision->created_at->format('d M Y, H:i') }}
                                </p>
                            </div>

                            <form action="{{ route('documents.revisions.restore', [$document, $revision]) }}" method="POST">
                                @csrf
                                <button class="bg-blue-500 text-white px-4 py-2 rounded">
                                    Restore
                                </button>
                            </form>
                        </div>

                        <div class="border rounded p-3 bg-gray-50 text-sm">
                            {!! nl2br(e(Str::limit(strip_tags($revision->content ?? ''), 400))) !!}
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">Belum ada riwayat versi.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>