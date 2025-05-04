<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DocumentResource;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $documents = $user->documents;
        return DocumentResource::collection($documents);
    }

    public function all()
    {
        $documents = Document::all();
        return response()->json($documents);
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,docx,jpeg,png,jpg|max:10240',
        ]);

        $path = $request->file('file')->store('documents', 'public');

        $document = Document::create([
            'user_id' => Auth::user()->id,
            'name' => $request->file('file')->getClientOriginalName(),
            'file_path' => $path,
        ]);

        return response()->json($document, 201);
    }

    public function show(Document $document)
    {
        return response()->json($document);
    }

    public function destroy(Document $document)
    {
        Storage::delete('public/' . $document->file_path);
        $document->delete();
        return response()->json(['message' => 'Document deleted successfully'], 204);
    }
}
