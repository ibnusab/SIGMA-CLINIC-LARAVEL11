<?php

namespace App\Http\Controllers;

use App\Models\Treatment;
use Illuminate\Http\Request;

class TreatmentController extends Controller
{
    public function index()
    {
        $treatments = Treatment::paginate(10);
        return view('treatments.index', compact('treatments'));
    }

    public function create()
    {
        return view('treatments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:treatments,code',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        Treatment::create($validated);

        return redirect()->route('treatments.index')
            ->with('success', 'Tindakan medis / Tarif berhasil ditambahkan.');
    }

    public function edit(Treatment $treatment)
    {
        return view('treatments.edit', compact('treatment'));
    }

    public function update(Request $request, Treatment $treatment)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:treatments,code,' . $treatment->id,
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        $treatment->update($validated);

        return redirect()->route('treatments.index')
            ->with('success', 'Data Tindakan berhasil diperbarui.');
    }

    public function destroy(Treatment $treatment)
    {
        $name = $treatment->name;
        $treatment->delete();

        return redirect()->route('treatments.index')
            ->with('success', 'Data Tindakan ' . $name . ' berhasil dihapus.');
    }
}
