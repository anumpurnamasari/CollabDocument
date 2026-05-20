<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentCollaborator;
use App\Models\DocumentRevision;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function index()
    {
        $ownedDocuments = Auth::user()
            ->documents()
            ->latest()
            ->get();

        $sharedDocuments = Auth::user()
            ->sharedDocuments()
            ->with('user')
            ->latest('documents.created_at')
            ->get();

        return view('documents.index', compact(
            'ownedDocuments',
            'sharedDocuments'
        ));
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

        return redirect()->route(
            'documents.edit',
            $document
        );
    }

    public function show(Document $document)
    {
        $this->ensureAccess($document);

        $document->load([
            'user',
            'collaborators.user',
        ]);

        return view('documents.show', compact(
            'document'
        ));
    }

    public function edit(Document $document)
    {
        $this->ensureAccess($document);

        return view('documents.edit', compact(
            'document'
        ));
    }

    public function update(
        Request $request,
        Document $document
    ) {
        $this->ensureAccess($document);

        $validated = $request->validate([
            'title' => ['required', 'max:255'],
            'content' => ['nullable', 'string'],
        ]);

        /*
        =========================================
        SAVE REVISION
        =========================================
        */

        if (
            $document->title !== $validated['title'] ||

            $document->content !==
            ($validated['content'] ?? '')
        ) {

            DocumentRevision::create([
                'document_id' => $document->id,
                'user_id' => Auth::id(),

                'title' => $document->title,

                'content' => $document->content,
            ]);
        }

        /*
        =========================================
        UPDATE DOCUMENT
        =========================================
        */

        $document->update([
            'title' => $validated['title'],

            'content' =>
                $validated['content'] ?? '',
        ]);

        /*
        =========================================
        JSON RESPONSE
        =========================================
        */

        if ($request->expectsJson()) {

            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()->route(
            'documents.edit',
            $document
        );
    }

    public function history(Document $document)
    {
        $this->ensureAccess($document);

        $revisions = $document->revisions()
            ->latest()
            ->get();

        return view('documents.history', compact(
            'document',
            'revisions'
        ));
    }

    public function restore(
        Document $document,
        DocumentRevision $revision
    ) {
        $this->ensureOwner($document);

        if (
            $revision->document_id !==
            $document->id
        ) {
            abort(404);
        }

        /*
        =========================================
        SAVE CURRENT STATE BEFORE RESTORE
        =========================================
        */

        DocumentRevision::create([
            'document_id' => $document->id,

            'user_id' => Auth::id(),

            'title' => $document->title,

            'content' => $document->content,
        ]);

        /*
        =========================================
        RESTORE REVISION
        =========================================
        */

        $document->update([
            'title' => $revision->title,

            'content' => $revision->content,
        ]);

        return redirect()->route(
            'documents.edit',
            $document
        );
    }

    public function share(
        Request $request,
        Document $document
    ) {
        $this->ensureOwner($document);

        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where(
            'email',
            $validated['email']
        )->first();

        /*
        =========================================
        USER NOT FOUND
        =========================================
        */

        if (! $user) {

            return back()->withErrors([
                'email' =>
                    'User tidak ditemukan.',
            ]);
        }

        /*
        =========================================
        OWNER CANNOT SHARE TO SELF
        =========================================
        */

        if (
            $user->id ===
            $document->user_id
        ) {

            return back()->withErrors([
                'email' =>
                    'Itu owner document ini.',
            ]);
        }

        /*
        =========================================
        CREATE / UPDATE COLLABORATOR
        =========================================
        */

        DocumentCollaborator::updateOrCreate(
            [
                'document_id' => $document->id,

                'user_id' => $user->id,
            ],
            [
                'permission' => 'edit',
            ]
        );

        return back()->with(
            'success',
            'Document shared successfully.'
        );
    }

    public function destroy(Document $document)
    {
        $this->ensureOwner($document);

        $document->delete();

        return redirect()
            ->route('documents.index')
            ->with(
                'success',
                'Document deleted successfully.'
            );
    }

    /*
    =========================================
    ACCESS CONTROL
    =========================================
    */

    private function ensureAccess(
        Document $document
    ): void {

        $userId = Auth::id();

        $allowed =

            $document->user_id === $userId ||

            $document->collaborators()
                ->where('user_id', $userId)
                ->exists();

        abort_unless($allowed, 403);
    }

    private function ensureOwner(
        Document $document
    ): void {

        abort_unless(
            $document->user_id === Auth::id(),
            403
        );
    }
}