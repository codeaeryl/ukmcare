<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Models\Log;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index()
    {
        $medicines = Medicine::latest()->paginate(10);
        return view('admin.medicines.index', compact('medicines'));
    }

    public function create()
    {
        return view('admin.medicines.create');
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

        Log::create([
            'user_id' => auth()->id(),
            'activity' => 'Added new medicine: ' . $medicine->name,
            'date' => now(),
        ]);

        return redirect()->route('admin.medicines.index')->with('success', 'Medicine added successfully.');
    }

    public function edit(Medicine $medicine)
    {
        return view('admin.medicines.edit', compact('medicine'));
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

        Log::create([
            'user_id' => auth()->id(),
            'activity' => 'Updated medicine: ' . $medicine->name,
            'date' => now(),
        ]);

        return redirect()->route('admin.medicines.index')->with('success', 'Medicine updated successfully.');
    }

    public function destroy(Medicine $medicine)
    {
        $name = $medicine->name;
        $medicine->delete();

        Log::create([
            'user_id' => auth()->id(),
            'activity' => 'Deleted medicine: ' . $name,
            'date' => now(),
        ]);
        
        return redirect()->route('admin.medicines.index')->with('success', 'Medicine deleted successfully.');
    }
}
