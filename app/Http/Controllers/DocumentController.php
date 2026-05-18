<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentRevision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Auth::user()->documents()->latest()->get();

        return view('documents.index', compact('documents'));
    }

    public function create()
    {
        return view('documents.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'max:255'],
        ]);

        $document = Document::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'content' => '',
        ]);

        return redirect()->route('documents.edit', $document);
    }

    public function show(Document $document)
    {
        return view('documents.show', compact('document'));
    }

    public function edit(Document $document)
    {
        return view('documents.edit', compact('document'));
    }

    public function update(Request $request, Document $document)
    {
        $validated = $request->validate([
            'title' => ['required', 'max:255'],
            'content' => ['nullable', 'string'],
        ]);

        // Simpan revision lama sebelum update
        if (
            $document->title !== $validated['title'] ||
            $document->content !== ($validated['content'] ?? '')
        ) {

            DocumentRevision::create([
                'document_id' => $document->id,
                'user_id' => Auth::id(),
                'title' => $document->title,
                'content' => $document->content,
            ]);
        }

        // Update document
        $document->update([
            'title' => $validated['title'],
            'content' => $validated['content'] ?? '',
        ]);

        // Untuk autosave AJAX
        if ($request->expectsJson()) {

            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()->route('documents.edit', $document);
    }

    public function history(Document $document)
    {
        $revisions = $document->revisions()->latest()->get();

        return view('documents.history', compact('document', 'revisions'));
    }

    public function restore(Document $document, DocumentRevision $revision)
    {
        if ($revision->document_id !== $document->id) {
            abort(404);
        }

        // Simpan current version sebelum restore
        DocumentRevision::create([
            'document_id' => $document->id,
            'user_id' => Auth::id(),
            'title' => $document->title,
            'content' => $document->content,
        ]);

        // Restore revision
        $document->update([
            'title' => $revision->title,
            'content' => $revision->content,
        ]);

        return redirect()->route('documents.edit', $document);
    }

    public function destroy(Document $document)
    {
        $document->delete();

        return redirect()->route('documents.index');
    }
}