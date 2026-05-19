<x-app-layout>
    <div class="max-w-5xl mx-auto p-6">
        <div class="bg-white shadow rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-2">Dashboard</h1>
            <p class="text-gray-600 mb-6">Pilih aksi di bawah untuk mulai kerja.</p>

            <div class="grid gap-4 md:grid-cols-2">
                <a href="{{ route('documents.create') }}"
                   class="block rounded-lg border p-5 hover:bg-gray-50">
                    <div class="text-lg font-semibold">Create Document</div>
                    <div class="text-sm text-gray-500">Buat dokumen baru.</div>
                </a>

                <a href="{{ route('documents.index') }}"
                   class="block rounded-lg border p-5 hover:bg-gray-50">
                    <div class="text-lg font-semibold">Open Documents</div>
                    <div class="text-sm text-gray-500">Lihat semua dokumen.</div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>