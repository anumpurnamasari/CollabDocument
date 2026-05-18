import * as Y from 'yjs'
import { HocuspocusProvider } from '@hocuspocus/provider'

document.addEventListener('DOMContentLoaded', () => {
    const editorElement = document.querySelector('#editor')
    const hiddenInput = document.querySelector('#content')
    const titleInput = document.querySelector('#title')
    const statusEl = document.querySelector('#save-status')
    const form = document.querySelector('#document-form')

    if (!editorElement || !hiddenInput || !titleInput || !statusEl || !form) {
        return
    }

    if (typeof window.createCollaborativeEditor !== 'function') {
        console.error('createCollaborativeEditor is not loaded')
        return
    }

    const documentId = Number(editorElement.dataset.documentId)
    const user = {
        name: editorElement.dataset.userName || 'User',
        color: editorElement.dataset.userColor || '#3b82f6',
    }

    let saveTimer = null
    let isSaving = false

    const markDirty = () => {
        statusEl.textContent = 'Unsaved changes'
        statusEl.classList.remove('text-green-600')
        statusEl.classList.add('text-yellow-600')
    }

    const markSaved = () => {
        statusEl.textContent = 'Saved'
        statusEl.classList.remove('text-yellow-600', 'text-red-600')
        statusEl.classList.add('text-green-600')
    }

    const autosave = async() => {
        if (isSaving) return

        isSaving = true
        statusEl.textContent = 'Saving...'
        statusEl.classList.remove('text-green-600', 'text-yellow-600', 'text-red-600')

        try {
            const formData = new FormData(form)

            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-HTTP-Method-Override': 'PUT',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: formData,
            })

            if (!response.ok) {
                throw new Error('Autosave failed')
            }

            markSaved()
        } catch (error) {
            console.error(error)
            statusEl.textContent = 'Save failed'
            statusEl.classList.add('text-red-600')
        } finally {
            isSaving = false
        }
    }

    const { editor, provider } = window.createCollaborativeEditor({
        element: editorElement,
        documentId,
        user,
    })

    hiddenInput.value = editor.getHTML()
    markSaved()

    editor.on('update', () => {
        hiddenInput.value = editor.getHTML()
        markDirty()

        clearTimeout(saveTimer)
        saveTimer = setTimeout(() => {
            autosave()
        }, 2000)
    })

    titleInput.addEventListener('input', () => {
        markDirty()

        clearTimeout(saveTimer)
        saveTimer = setTimeout(() => {
            autosave()
        }, 2000)
    })

    window.addEventListener('beforeunload', () => {
        provider.destroy()
        editor.destroy()
    })
})