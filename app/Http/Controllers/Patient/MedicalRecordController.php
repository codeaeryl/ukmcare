<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use Illuminate\Support\Facades\Auth;

class MedicalRecordController extends Controller
{
    public function index()
    {
        $patient = Auth::user()->patient;

        if (!$patient) {
            return redirect()->route('dashboard')->with('error', 'Patient profile not found.');
        }

        $records = MedicalRecord::with(['doctor', 'prescriptions.medicine', 'registration'])
            ->whereHas('registration', function ($query) use ($patient) {
                $query->where('patient_id', $patient->id);
            })
            ->orderByDesc('record_date')
            ->get();

        return view('patient.records.index', compact('records'));
    }

    public function show(MedicalRecord $record)
    {
        $patient = Auth::user()->patient;

        if (!$patient) {
            return redirect()->route('dashboard')->with('error', 'Patient profile not found.');
        }

        // Ensure the patient can only view their own records
        $record->load(['doctor', 'prescriptions.medicine', 'registration.schedule']);

        if ($record->registration->patient_id !== $patient->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('patient.records.show', compact('record'));
    }
}
