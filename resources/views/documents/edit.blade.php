<x-app-layout>
    <div class="max-w-5xl mx-auto p-6">

        <div class="bg-white shadow rounded-lg p-6">

            <div class="flex justify-between items-center mb-6">

                <div class="w-full">

                    <input
                        type="text"
                        id="title"
                        value="{{ $document->title }}"
                        class="w-full text-3xl font-bold border-0 border-b focus:ring-0"
                    >

                    <div class="mt-2 flex items-center gap-3">

                        <div id="save-status"
                             class="text-sm text-green-600">
                            Saved
                        </div>

                        <div id="active-count"
                             class="text-sm text-gray-500">
                            1 user online
                        </div>

                    </div>
                </div>

                <div class="flex gap-2 ml-4">

                    <a href="{{ route('documents.history', $document) }}"
                       class="bg-gray-200 px-4 py-2 rounded">
                        History
                    </a>

                    <a href="{{ route('documents.index') }}"
                       class="bg-blue-500 text-white px-4 py-2 rounded">
                        Back
                    </a>

                </div>

            </div>

            <div class="mb-4">

                <div id="active-users"
                     class="flex flex-wrap gap-2">
                </div>

            </div>

            <div id="editor"
                 class="tiptap border rounded-lg p-4 min-h-[500px]">
            </div>

        </div>
    </div>

    <script type="module">

        document.addEventListener('DOMContentLoaded', () => {

            const editorElement = document.querySelector('#editor')

            const titleInput = document.querySelector('#title')

            const saveStatus = document.querySelector('#save-status')

            const activeUsersEl = document.querySelector('#active-users')

            const activeCountEl = document.querySelector('#active-count')

            if (
                !editorElement ||
                typeof window.createCollaborativeEditor !== 'function'
            ) {
                console.error('Collaborative editor not ready')
                return
            }

            const initialContent =
                @json($document->content ?? '<p></p>')

            const { editor, provider } =
                window.createCollaborativeEditor({

                    element: editorElement,

                    documentId: {{ $document->id }},

                    user: {
                        name: @json(auth()->user()->name),
                        color: '#3b82f6',
                    },

                    initialContent,
                })

            /*
            ==========================================
            FIX ISI DOCUMENT HILANG
            ==========================================
            */

            setTimeout(() => {

                const currentText =
                    editor.getText().trim()

                const currentHtml =
                    editor.getHTML().trim()

                const isEmpty =
                    currentText === '' ||
                    currentHtml === '<p></p>'

                if (
                    isEmpty &&
                    initialContent &&
                    initialContent !== '<p></p>'
                ) {

                    editor.commands.setContent(
                        initialContent,
                        false
                    )
                }

            }, 500)

            let saveTimer = null

            let isSaving = false

            const setSaved = () => {

                saveStatus.innerText = 'Saved'

                saveStatus.className =
                    'text-sm text-green-600'
            }

            const setDirty = () => {

                saveStatus.innerText = 'Unsaved'

                saveStatus.className =
                    'text-sm text-yellow-600'
            }

            const setSaving = () => {

                saveStatus.innerText = 'Saving...'

                saveStatus.className =
                    'text-sm text-gray-500'
            }

            const autosave = async () => {

                if (isSaving) return

                isSaving = true

                setSaving()

                try {

                    const response = await fetch(
                        "{{ route('documents.update', $document) }}",
                        {
                            method: 'POST',

                            headers: {
                                'Content-Type': 'application/json',

                                'X-CSRF-TOKEN':
                                    document.querySelector(
                                        'meta[name="csrf-token"]'
                                    ).content,

                                'X-Requested-With':
                                    'XMLHttpRequest',

                                'Accept':
                                    'application/json',

                                'X-HTTP-Method-Override':
                                    'PUT',
                            },

                            body: JSON.stringify({

                                title: titleInput.value,

                                content: editor.getHTML(),
                            }),
                        }
                    )

                    if (!response.ok) {
                        throw new Error('Autosave failed')
                    }

                    setSaved()

                } catch (error) {

                    console.error(error)

                    saveStatus.innerText = 'Save failed'

                    saveStatus.className =
                        'text-sm text-red-600'

                } finally {

                    isSaving = false
                }
            }

            const queueAutosave = () => {

                clearTimeout(saveTimer)

                setDirty()

                saveTimer = setTimeout(() => {
                    autosave()
                }, 2000)
            }

            editor.on('update', () => {
                queueAutosave()
            })

            titleInput.addEventListener('input', () => {
                queueAutosave()
            })

            const renderActiveUsers = () => {

                const states =
                    provider.awareness.getStates()

                const users = []

                states.forEach((state) => {

                    if (state.user) {
                        users.push(state.user)
                    }
                })

                activeUsersEl.innerHTML = ''

                users.forEach((user) => {

                    const badge =
                        document.createElement('span')

                    badge.className =
                        'px-3 py-1 rounded-full text-white text-sm'

                    badge.style.backgroundColor =
                        user.color || '#3b82f6'

                    badge.innerText =
                        user.name || 'User'

                    activeUsersEl.appendChild(badge)
                })

                activeCountEl.innerText =
                    `${users.length} user online`
            }

            renderActiveUsers()

            provider.awareness.on(
                'change',
                renderActiveUsers
            )

            window.addEventListener(
                'beforeunload',
                () => {

                    clearTimeout(saveTimer)

                    provider.destroy()

                    editor.destroy()
                }
            )
        })

    </script>
</x-app-layout>