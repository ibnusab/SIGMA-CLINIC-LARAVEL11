<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use Illuminate\Http\Request;

class ClinicController extends Controller
{
    public function index()
    {
        $clinics = Clinic::withCount('doctors')->paginate(10);
        return view('clinics.index', compact('clinics'));
    }

    public function create()
    {
        return view('clinics.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:clinics,code',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        Clinic::create($validated);

        return redirect()->route('clinics.index')
            ->with('success', 'Poli / Klinik ' . $validated['name'] . ' berhasil ditambahkan.');
    }

    public function edit(Clinic $clinic)
    {
        return view('clinics.edit', compact('clinic'));
    }

    public function update(Request $request, Clinic $clinic)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:clinics,code,' . $clinic->id,
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        $clinic->update($validated);

        return redirect()->route('clinics.index')
            ->with('success', 'Data Poli ' . $clinic->name . ' berhasil diperbarui.');
    }

    public function destroy(Clinic $clinic)
    {
        $name = $clinic->name;
        $clinic->delete();

        return redirect()->route('clinics.index')
            ->with('success', 'Data Poli ' . $name . ' berhasil dihapus.');
    }
}
