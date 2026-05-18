import { Editor } from '@tiptap/core'
import StarterKit from '@tiptap/starter-kit'
import Placeholder from '@tiptap/extension-placeholder'

window.createEditor = (element, initialContent = '') => {
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