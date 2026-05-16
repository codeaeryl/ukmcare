<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use Illuminate\Support\Facades\Auth;

class BillController extends Controller
{
    public function index()
    {
        $patient = Auth::user()->patient;

        if (!$patient) {
            return redirect()->route('dashboard')->with('error', 'Patient profile not found.');
        }

        $bills = Bill::with(['registration.schedule.doctor', 'services', 'medicines', 'payment'])
            ->whereHas('registration', function ($query) use ($patient) {
                $query->where('patient_id', $patient->id);
            })
            ->orderByDesc('date')
            ->get();

        return view('patient.bills.index', compact('bills'));
    }

    public function show(Bill $bill)
    {
        $patient = Auth::user()->patient;

        if (!$patient) {
            return redirect()->route('dashboard')->with('error', 'Patient profile not found.');
        }

        $bill->load(['registration.schedule.doctor', 'services', 'medicines', 'payment']);

        // Ensure the patient can only view their own bills
        if ($bill->registration->patient_id !== $patient->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('patient.bills.show', compact('bill'));
    }
}
