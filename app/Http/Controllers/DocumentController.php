<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\Member;
use App\Models\Loan;
use App\Models\Guarantor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $companyId = auth()->user()->company_id;

        $query = Document::with(['category', 'uploader', 'verifier'])
            ->where('company_id', $companyId);

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by related type
        if ($request->filled('related_type')) {
            $query->where('related_type', 'App\\Models\\' . ucfirst($request->related_type));
        }

        // Filter by verification status
        if ($request->filled('status')) {
            if ($request->status === 'verified') {
                $query->where('is_verified', true);
            } elseif ($request->status === 'unverified') {
                $query->where('is_verified', false);
            }
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $documents = $query->latest()->paginate(20);
        $categories = DocumentCategory::where('company_id', $companyId)->active()->get();

        return view('documents.index', compact('documents', 'categories'));
    }

    public function create(Request $request)
    {
        $companyId = auth()->user()->company_id;
        $categories = DocumentCategory::where('company_id', $companyId)->active()->get();
        
        // Get related data if provided
        $relatedType = $request->related_type;
        $relatedId = $request->related_id;
        $related = null;

        if ($relatedType && $relatedId) {
            $modelMap = [
                'member' => Member::class,
                'loan' => Loan::class,
                'guarantor' => Guarantor::class,
            ];
            
            if (isset($modelMap[$relatedType])) {
                $related = $modelMap[$relatedType]::find($relatedId);
            }
        }

        return view('documents.create', compact('categories', 'related', 'relatedType', 'relatedId'));
    }

    public function store(Request $request)
    {
        // Log the request for debugging
        Log::info('Document upload attempt', [
            'category_id' => $request->category_id,
            'related_type' => $request->related_type,
            'related_id' => $request->related_id,
            'has_file' => $request->hasFile('document'),
            'all_data' => $request->all()
        ]);

        // ✅ FIXED: Make related_id optional
        $request->validate([
            'category_id' => 'required|exists:document_categories,id',
            'related_type' => 'required|in:member,loan,guarantor',
            'related_id' => 'nullable|integer|min:1',
            'document' => 'required|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,txt|max:20480',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'expires_date' => 'nullable|date|after:today',
        ]);

        // ✅ FIXED: Only check related record if related_id is provided
        $modelMap = [
            'member' => Member::class,
            'loan' => Loan::class,
            'guarantor' => Guarantor::class,
        ];

        $modelClass = $modelMap[$request->related_type];
        $related = null;

        if ($request->filled('related_id')) {
            $related = $modelClass::where('company_id', auth()->user()->company_id)
                ->find($request->related_id);

            if (!$related) {
                return redirect()->back()
                    ->with('error', 'Related record not found.')
                    ->withInput();
            }
        }

        // Handle file upload
        try {
            $file = $request->file('document');
            
            if (!$file) {
                return redirect()->back()
                    ->with('error', 'No file was uploaded.')
                    ->withInput();
            }

            $originalName = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            $mimeType = $file->getMimeType();
            $fileType = $file->getClientOriginalExtension();

            // Generate unique filename
            $fileName = time() . '_' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $fileType;
            
            // ✅ FIXED: Use general folder if no related_id
            if ($request->filled('related_id')) {
                $path = 'documents/' . $request->related_type . 's/' . $request->related_id;
            } else {
                $path = 'documents/general';
            }
            
            // Store file
            $storedPath = $file->storeAs($path, $fileName, 'public');

            if (!$storedPath) {
                return redirect()->back()
                    ->with('error', 'Failed to store file. Please check storage permissions.')
                    ->withInput();
            }

            // Create document record
            $document = Document::create([
                'company_id' => auth()->user()->company_id,
                'category_id' => $request->category_id,
                'related_type' => $modelClass,
                'related_id' => $request->related_id,
                'name' => $request->name ?? $originalName,
                'file_path' => $storedPath,
                'file_name' => $originalName,
                'file_size' => $fileSize,
                'mime_type' => $mimeType,
                'file_type' => $fileType,
                'description' => $request->description,
                'upload_date' => now(),
                'expires_date' => $request->expires_date,
                'uploaded_by' => auth()->id(),
            ]);

            Log::info('Document uploaded successfully', ['id' => $document->id, 'path' => $storedPath]);

            return redirect()->route('documents.show', $document)
                ->with('success', 'Document uploaded successfully!');

        } catch (\Exception $e) {
            Log::error('Document upload error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Upload failed: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Document $document)
    {
        $document->load(['category', 'uploader', 'verifier', 'related']);
        return view('documents.show', compact('document'));
    }

    public function edit(Document $document)
    {
        $categories = DocumentCategory::where('company_id', auth()->user()->company_id)
            ->active()
            ->get();
        return view('documents.edit', compact('document', 'categories'));
    }

    public function update(Request $request, Document $document)
    {
        $request->validate([
            'category_id' => 'required|exists:document_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'expires_date' => 'nullable|date|after:today',
        ]);

        $document->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'expires_date' => $request->expires_date,
        ]);

        return redirect()->route('documents.show', $document)
            ->with('success', 'Document updated successfully!');
    }

    public function destroy(Document $document)
    {
        // Delete file from storage
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return redirect()->route('documents.index')
            ->with('success', 'Document deleted successfully!');
    }

    public function download(Document $document)
    {
        $filePath = storage_path('app/public/' . $document->file_path);
        
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File not found.');
        }

        return response()->download($filePath, $document->file_name);
    }

    public function preview(Document $document)
    {
        $filePath = storage_path('app/public/' . $document->file_path);
        
        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File not found.'], 404);
        }

        return response()->file($filePath);
    }

    public function verify(Document $document)
    {
        $document->verify(auth()->id());

        return redirect()->route('documents.show', $document)
            ->with('success', 'Document verified successfully!');
    }

    public function unverify(Document $document)
    {
        $document->unverify();

        return redirect()->route('documents.show', $document)
            ->with('success', 'Document unverified.');
    }

    public function attachments($type, $id)
    {
        $modelMap = [
            'member' => Member::class,
            'loan' => Loan::class,
            'guarantor' => Guarantor::class,
        ];

        if (!isset($modelMap[$type])) {
            abort(404);
        }

        $related = $modelMap[$type]::where('company_id', auth()->user()->company_id)
            ->findOrFail($id);

        $documents = $related->documents()->with(['category', 'uploader'])->get();

        return view('documents.attachments', compact('related', 'documents', 'type'));
    }
}