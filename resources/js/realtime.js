import { Editor } from '@tiptap/core'
import StarterKit from '@tiptap/starter-kit'
import Placeholder from '@tiptap/extension-placeholder'
import Collaboration from '@tiptap/extension-collaboration'
import CollaborationCaret from '@tiptap/extension-collaboration-caret'
import * as Y from 'yjs'
import { HocuspocusProvider } from '@hocuspocus/provider'

document.addEventListener('DOMContentLoaded', () => {
    const editorElement = document.querySelector('#realtime-editor')

    if (!editorElement) return

    const documentId = Number(editorElement.dataset.documentId)
    const user = {
        name: editorElement.dataset.userName || 'User',
        color: editorElement.dataset.userColor || '#3b82f6',
    }

    const ydoc = new Y.Doc()

    const provider = new HocuspocusProvider({
        url: 'ws://127.0.0.1:1234',
        name: `document-${documentId}`,
        document: ydoc,
    })

    provider.setAwarenessField('user', user)

    const editor = new Editor({
        element: editorElement,
        editable: true,
        extensions: [
            StarterKit.configure({
                undoRedo: false,
            }),
            Collaboration.configure({
                document: ydoc,
            }),
            CollaborationCaret.configure({
                provider,
                user,
            }),
            Placeholder.configure({
                placeholder: 'Start typing...',
            }),
        ],
        content: '',
    })

    window.addEventListener('beforeunload', () => {
        provider.destroy()
        editor.destroy()
    })
})