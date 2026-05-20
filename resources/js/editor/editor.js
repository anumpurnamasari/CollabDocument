import { Editor } from '@tiptap/core'
import StarterKit from '@tiptap/starter-kit'
import Placeholder from '@tiptap/extension-placeholder'
import Collaboration from '@tiptap/extension-collaboration'
import CollaborationCaret from '@tiptap/extension-collaboration-caret'
import * as Y from 'yjs'
import { HocuspocusProvider } from '@hocuspocus/provider'

function createBaseEditor(element, initialContent = '') {
    return new Editor({
        element,
        editable: true,
        extensions: [
            StarterKit,
            Placeholder.configure({
                placeholder: 'Start typing...',
            }),
        ],
        content: initialContent || '<p></p>',
    })
}

window.createEditor = (element, initialContent = '') => {
    return createBaseEditor(element, initialContent)
}

window.createCollaborativeEditor = ({
    element,
    documentId,
    user = {},
    initialContent = '',
}) => {
    const ydoc = new Y.Doc()

    const currentUser = {
        name: user.name || 'User',
        color: user.color || '#3b82f6',
    }

    const provider = new HocuspocusProvider({
        url: 'ws://127.0.0.1:1234',
        name: `document-${documentId}`,
        document: ydoc,
    })

    provider.setAwarenessField('user', currentUser)

    const editor = new Editor({
        element,
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
                user: currentUser,
            }),
            Placeholder.configure({
                placeholder: 'Start typing...',
            }),
        ],
        content: initialContent || '<p></p>',
    })

    return { editor, provider }
}