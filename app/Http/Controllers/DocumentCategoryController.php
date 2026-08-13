<?php

namespace App\Http\Controllers;

use App\Models\DocumentCategory;
use Illuminate\Http\Request;

class DocumentCategoryController extends Controller
{
    public function index()
    {
        $categories = DocumentCategory::where('company_id', auth()->user()->company_id)
            ->withCount('documents')
            ->get();
        return view('documents.categories', compact('categories'));
    }

    public function create()
    {
        return view('documents.categories-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
        ]);

        DocumentCategory::create([
            'company_id' => auth()->user()->company_id,
            'name' => $request->name,
            'icon' => $request->icon ?? 'fa-file',
            'color' => $request->color ?? '#6B7280',
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('documents.categories')
            ->with('success', 'Category created successfully!');
    }

    public function edit(DocumentCategory $category)
    {
        return view('documents.categories-edit', compact('category'));
    }

    public function update(Request $request, DocumentCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
        ]);

        $category->update([
            'name' => $request->name,
            'icon' => $request->icon ?? 'fa-file',
            'color' => $request->color ?? '#6B7280',
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('documents.categories')
            ->with('success', 'Category updated successfully!');
    }

    public function destroy(DocumentCategory $category)
    {
        if ($category->documents()->count() > 0) {
            return redirect()->route('documents.categories')
                ->with('error', 'Cannot delete category with documents. Remove documents first.');
        }
        
        $category->delete();
        return redirect()->route('documents.categories')
            ->with('success', 'Category deleted successfully!');
    }
}