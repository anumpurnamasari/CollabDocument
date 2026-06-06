import { Editor } from '@tiptap/core'
import StarterKit from '@tiptap/starter-kit'
import Placeholder from '@tiptap/extension-placeholder'
import Collaboration from '@tiptap/extension-collaboration'
import CollaborationCaret from '@tiptap/extension-collaboration-caret'
import * as Y from 'yjs'
import { HocuspocusProvider } from '@hocuspocus/provider'

window.createCollaborativeEditor = ({ element, documentId, user, initialContent = '' }) => {
    const ydoc = new Y.Doc()

    const provider = new HocuspocusProvider({
        url: 'ws://192.168.234.215:1234',
        name: `document-${documentId}`,
        document: ydoc,
    })

    provider.setAwarenessField('user', user)

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
                user,
            }),
            Placeholder.configure({
                placeholder: 'Start typing...',
            }),
        ],
        content: initialContent || '<p></p>',
    })

    return { editor, provider }
}