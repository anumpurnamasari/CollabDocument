<x-app-layout>
    <div class="max-w-3xl mx-auto p-6">

        <div class="bg-white shadow rounded-lg p-6">

            <h1 class="text-2xl font-bold mb-6">
                Create Document
            </h1>

            <form action="{{ route('documents.store') }}"
                  method="POST">

                @csrf

                <div class="mb-4">
                    <label class="block mb-2">
                        Title
                    </label>

                    <input type="text"
                           name="title"
                           class="w-full border rounded p-2"
                           required>
                </div>

                <button class="bg-blue-500 text-white px-4 py-2 rounded">
                    Create
                </button>

            </form>

        </div>
    </div>
</x-app-layout>