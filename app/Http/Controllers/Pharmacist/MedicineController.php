<?php

namespace App\Http\Controllers\Pharmacist;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index()
    {
        $medicines = Medicine::latest()->paginate(10);
        return view('pharmacist.medicines.index', compact('medicines'));
    }

    public function create()
    {
        return view('pharmacist.medicines.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'unit' => 'required|string|max:20',
        ]);

        $medicine = Medicine::create($request->all());

        return redirect()->route('pharmacist.medicines.index')->with('success', 'Medicine added successfully.');
    }

    public function edit(Medicine $medicine)
    {
        return view('pharmacist.medicines.edit', compact('medicine'));
    }

    public function update(Request $request, Medicine $medicine)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'unit' => 'required|string|max:20',
        ]);

        $medicine->update($request->all());

        return redirect()->route('pharmacist.medicines.index')->with('success', 'Medicine updated successfully.');
    }

    public function destroy(Medicine $medicine)
    {
        $name = $medicine->name;
        $medicine->delete();
        
        return redirect()->route('pharmacist.medicines.index')->with('success', 'Medicine deleted successfully.');
    }
}
