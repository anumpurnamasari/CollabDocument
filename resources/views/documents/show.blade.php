<x-app-layout>
    <div class="max-w-5xl mx-auto p-6">

        <div class="bg-white shadow rounded-lg p-6">

            <h1 class="text-3xl font-bold mb-4">
                {{ $document->title }}
            </h1>

            <div class="border rounded p-4 min-h-[400px]">
                {!! $document->content !!}
            </div>

        </div>
    </div>
</x-app-layout>