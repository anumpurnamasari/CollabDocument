<x-app-layout>
    <div class="max-w-5xl mx-auto p-6">
        <div class="bg-white shadow rounded-lg p-6">

            <form id="document-form"
                  action="{{ route('documents.update', $document) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="flex justify-between items-center mb-6">

                    <input type="text"
                           id="title"
                           name="title"
                           value="{{ old('title', $document->title) }}"
                           class="w-full text-3xl font-bold border-0 border-b focus:ring-0"
                           required>

                    <div id="save-status"
                         class="ml-4 text-sm text-gray-500 whitespace-nowrap">

                        Saved

                    </div>

                </div>

                <input type="hidden"
                       name="content"
                       id="content"
                       value="{{ old('content', $document->content) }}">

                <div id="editor"
                     class="border rounded-lg p-4 min-h-[500px]">
                </div>

            </form>

        </div>
    </div>

    <script type="module">

        const editorElement = document.querySelector('#editor')

        const hiddenInput = document.querySelector('#content')

        const titleInput = document.querySelector('#title')

        const form = document.querySelector('#document-form')

        const saveStatus = document.querySelector('#save-status')

        let saveTimer = null

        const editor = window.createEditor(
            editorElement,
            @json($document->content ?? '')
        )

        hiddenInput.value = editor.getHTML()

        const autosave = async () => {

            saveStatus.innerText = 'Saving...'

            const formData = new FormData(form)

            await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-HTTP-Method-Override': 'PUT',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: formData,
            })

            saveStatus.innerText = 'Saved'
        }

        const queueAutosave = () => {

            clearTimeout(saveTimer)

            saveTimer = setTimeout(() => {
                autosave()
            }, 2000)
        }

        editor.on('update', () => {

            hiddenInput.value = editor.getHTML()

            saveStatus.innerText = 'Unsaved'

            queueAutosave()
        })

        titleInput.addEventListener('input', () => {

            saveStatus.innerText = 'Unsaved'

            queueAutosave()
        })

    </script>

</x-app-layout>