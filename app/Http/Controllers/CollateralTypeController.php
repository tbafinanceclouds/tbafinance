<?php

namespace App\Http\Controllers;

use App\Models\CollateralType;
use Illuminate\Http\Request;

class CollateralTypeController extends Controller
{
    public function index()
    {
        $types = CollateralType::where('company_id', auth()->user()->company_id)
            ->withCount('collaterals')
            ->get();
        return view('collaterals.types', compact('types'));
    }

    public function create()
    {
        return view('collaterals.types-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
        ]);

        CollateralType::create([
            'company_id' => auth()->user()->company_id,
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $request->icon ?? 'fa-building',
            'color' => $request->color ?? '#6B7280',
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('collaterals.types')
            ->with('success', 'Collateral type created successfully!');
    }

    public function edit(CollateralType $type)
    {
        return view('collaterals.types-edit', compact('type'));
    }

    public function update(Request $request, CollateralType $type)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
        ]);

        $type->update([
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $request->icon ?? 'fa-building',
            'color' => $request->color ?? '#6B7280',
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('collaterals.types')
            ->with('success', 'Collateral type updated successfully!');
    }

    public function destroy(CollateralType $type)
    {
        if ($type->collaterals()->count() > 0) {
            return redirect()->route('collaterals.types')
                ->with('error', 'Cannot delete type with collateral records.');
        }

        $type->delete();
        return redirect()->route('collaterals.types')
            ->with('success', 'Collateral type deleted successfully!');
    }
}