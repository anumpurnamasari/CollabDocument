<x-app-layout>
    <div class="max-w-5xl mx-auto p-6">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-2xl font-bold">{{ $document->title }}</h1>
                    <p class="text-sm text-gray-500">Realtime collaboration test</p>
                </div>

                <a href="{{ route('documents.edit', $document) }}"
                   class="bg-gray-200 px-4 py-2 rounded">
                    Back to Editor
                </a>
            </div>

            <div class="mb-4 rounded-lg border bg-gray-50 p-4">
                <div class="mb-2 flex items-center justify-between">
                    <p class="font-semibold">Active users</p>
                    <p id="active-count" class="text-sm text-gray-500">0 users online</p>
                </div>

                <div id="active-users" class="flex flex-wrap gap-2">
                    <span class="text-sm text-gray-500">Loading users...</span>
                </div>
            </div>

            <div
                id="editor"
                class="tiptap border rounded-lg p-4 min-h-[500px]"
                data-document-id="{{ $document->id }}"
                data-user-name="{{ auth()->user()->name }}"
                data-user-color="#3b82f6"
            ></div>
        </div>
    </div>

    <script type="module">
        document.addEventListener('DOMContentLoaded', () => {
            const editorElement = document.querySelector('#editor')
            const activeUsersEl = document.querySelector('#active-users')
            const activeCountEl = document.querySelector('#active-count')

            if (!editorElement || typeof window.createCollaborativeEditor !== 'function') {
                console.error('Collaborative editor not ready')
                return
            }

            const escapeHtml = (unsafe) => {
                return String(unsafe)
                    .replaceAll('&', '&amp;')
                    .replaceAll('<', '&lt;')
                    .replaceAll('>', '&gt;')
                    .replaceAll('"', '&quot;')
                    .replaceAll("'", '&#039;')
            }

            const { editor, provider } = window.createCollaborativeEditor({
                element: editorElement,
                documentId: {{ $document->id }},
                user: {
                    name: @json(auth()->user()->name),
                    color: '#3b82f6',
                },
                initialContent: @json($document->content ?? ''),
            })

            const renderActiveUsers = () => {
                const states = provider.awareness.getStates()
                const users = []

                states.forEach((state, clientId) => {
                    if (state.user) {
                        users.push({
                            clientId,
                            ...state.user,
                        })
                    }
                })

                if (users.length === 0) {
                    activeUsersEl.innerHTML = '<span class="text-sm text-gray-500">No active collaborators yet</span>'
                    activeCountEl.textContent = '0 users online'
                    return
                }

                activeUsersEl.innerHTML = users.map((user) => {
                    const name = escapeHtml(user.name || 'User')
                    const color = user.color || '#3b82f6'

                    return `
                        <span
                            class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium text-white"
                            style="background-color: ${color};"
                        >
                            ${name}
                        </span>
                    `
                }).join('')

                activeCountEl.textContent = `${users.length} user${users.length === 1 ? '' : 's'} online`
            }

            renderActiveUsers()
            provider.awareness.on('change', renderActiveUsers)

            window.addEventListener('beforeunload', () => {
                provider.awareness.off('change', renderActiveUsers)
                provider.destroy()
                editor.destroy()
            })
        })
    </script>
</x-app-layout>