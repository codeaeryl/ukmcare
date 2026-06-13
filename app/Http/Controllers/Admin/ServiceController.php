<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::with('doctor')->latest()->paginate(10);
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        $doctors = Doctor::all();
        return view('admin.services.create', compact('doctors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('services')->where(function ($query) use ($request) {
                    return $query->where('doctor_id', $request->doctor_id);
                })
            ],
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'doctor_id' => 'nullable|exists:doctors,id',
        ]);

        Service::create($request->all());

        return redirect()->route('admin.services.index')->with('success', 'Service created successfully.');
    }

    public function edit(Service $service)
    {
        $doctors = Doctor::all();
        return view('admin.services.edit', compact('service', 'doctors'));
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('services')->ignore($service->id)->where(function ($query) use ($request) {
                    return $query->where('doctor_id', $request->doctor_id);
                })
            ],
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'doctor_id' => 'nullable|exists:doctors,id',
        ]);

        $service->update($request->all());

        return redirect()->route('admin.services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        // Check if service is associated with any bills
        if ($service->billServices()->exists()) {
            return redirect()->route('admin.services.index')->with('error', 'Cannot delete service because it has been used in one or more bills.');
        }

        $service->delete();

        return redirect()->route('admin.services.index')->with('success', 'Service deleted successfully.');
    }
}
