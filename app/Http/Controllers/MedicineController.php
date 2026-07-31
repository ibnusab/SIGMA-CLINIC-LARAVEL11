<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Supplier;
use App\Models\StockMovement;
use App\Http\Requests\MedicineRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicineController extends Controller
{
    public function index(Request $request)
    {
        $query = Medicine::with('supplier');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->get('filter') === 'low_stock') {
            $query->whereRaw('stock <= min_stock');
        }

        $medicines = $query->paginate(10)->withQueryString();
        $suppliers = Supplier::all();

        return view('medicines.index', compact('medicines', 'suppliers'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        return view('medicines.create', compact('suppliers'));
    }

    public function store(MedicineRequest $request)
    {
        $validated = $request->validated();
        $medicine = Medicine::create($validated);

        // Record Initial Stock Movement
        if ($medicine->stock > 0) {
            StockMovement::create([
                'medicine_id' => $medicine->id,
                'type' => 'in',
                'quantity' => $medicine->stock,
                'reference' => 'Stok Awal',
                'notes' => 'Input data obat baru',
                'user_id' => Auth::id(),
            ]);
        }

        return redirect()->route('medicines.index')
            ->with('success', 'Data Obat ' . $medicine->name . ' berhasil ditambahkan.');
    }

    public function edit(Medicine $medicine)
    {
        $suppliers = Supplier::all();
        return view('medicines.edit', compact('medicine', 'suppliers'));
    }

    public function update(MedicineRequest $request, Medicine $medicine)
    {
        $validated = $request->validated();
        $oldStock = $medicine->stock;

        $medicine->update($validated);

        // Record Stock Adjustment
        $diff = $medicine->stock - $oldStock;
        if ($diff != 0) {
            StockMovement::create([
                'medicine_id' => $medicine->id,
                'type' => $diff > 0 ? 'in' : 'adjustment',
                'quantity' => abs($diff),
                'reference' => 'Penyesuaian Manual',
                'notes' => 'Update stok obat',
                'user_id' => Auth::id(),
            ]);
        }

        return redirect()->route('medicines.index')
            ->with('success', 'Data Obat ' . $medicine->name . ' berhasil diperbarui.');
    }

    public function destroy(Medicine $medicine)
    {
        $name = $medicine->name;
        $medicine->delete();

        return redirect()->route('medicines.index')
            ->with('success', 'Data Obat ' . $name . ' berhasil dihapus.');
    }
}
