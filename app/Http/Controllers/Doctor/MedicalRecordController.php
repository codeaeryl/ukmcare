<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Models\Registration;
use App\Models\Medicine;
use App\Models\Prescription;
use App\Models\Bill;
use App\Models\Service;
use App\Enums\RegistrationStatus;
use App\Enums\BillStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MedicalRecordController extends Controller
{
    public function index()
    {
        $doctor = auth()->user()->doctor;
        
        if (!$doctor) {
            return redirect()->route('dashboard')->with('error', 'Doctor profile not found.');
        }

        $registrations = Registration::with(['patient', 'schedule'])
            ->whereHas('schedule', function($query) use ($doctor) {
                $query->where('doctor_id', $doctor->id);
            })
            ->where('status', RegistrationStatus::REGISTERED)
            ->orderBy('registration_date')
            ->orderBy('queue_number')
            ->get();

        return view('doctor.records.index', compact('registrations'));
    }

    public function create(Registration $registration)
    {
        $doctor = auth()->user()->doctor;
        if (!$doctor || $registration->schedule->doctor_id !== $doctor->id) {
            abort(403);
        }

        $registration->load('patient');
        $medicines = Medicine::where('stock', '>', 0)->get();
        return view('doctor.records.create', compact('registration', 'medicines'));
    }

    public function store(Request $request, Registration $registration)
    {
        $doctor = auth()->user()->doctor;
        if (!$doctor || $registration->schedule->doctor_id !== $doctor->id) {
            abort(403);
        }

        $request->validate([
            'diagnosis' => 'required|string',
            'treatment' => 'required|string',
            'medicines' => 'nullable|array',
            'medicines.*.id' => 'exists:medicines,id',
            'medicines.*.quantity' => 'integer|min:1',
        ]);

        if ($request->filled('medicines')) {
            foreach ($request->medicines as $med) {
                if (isset($med['id']) && isset($med['quantity'])) {
                    $medicine = Medicine::find($med['id']);
                    if ($medicine->stock < $med['quantity']) {
                        return back()->withErrors(['medicines' => "Not enough stock for {$medicine->name}. Available: {$medicine->stock}."])->withInput();
                    }
                }
            }
        }

        DB::transaction(function () use ($request, $registration) {
            $record = MedicalRecord::create([
                'registration_id' => $registration->id,
                'doctor_id' => $registration->schedule->doctor_id,
                'diagnosis' => $request->diagnosis,
                'action' => $request->treatment,
                'description' => $request->notes,
                'record_date' => now(),
            ]);

            if ($request->filled('medicines')) {
                foreach ($request->medicines as $med) {
                    if (isset($med['id']) && isset($med['quantity'])) {
                        Prescription::create([
                            'medical_record_id' => $record->id,
                            'medicine_id' => $med['id'],
                            'quantity' => $med['quantity'],
                            'dosage' => $med['instruction'] ?? '3x1 after meal',
                        ]);

                        $medicine = Medicine::find($med['id']);
                        $medicine->decrement('stock', $med['quantity']);
                    }
                }
            }

            // Auto-generate Bill or find existing
            $bill = Bill::firstOrCreate(
                ['registration_id' => $registration->id],
                [
                    'date' => now(),
                    'status' => BillStatus::PENDING,
                ]
            );

            if ($request->filled('medicines')) {
                foreach ($request->medicines as $med) {
                    if (isset($med['id']) && isset($med['quantity'])) {
                        $medicine = Medicine::find($med['id']);
                        $bill->billMedicines()->firstOrCreate(
                            ['medicine_id' => $med['id']],
                            [
                                'quantity' => $med['quantity'],
                                'price' => $medicine->price,
                            ]
                        );
                    }
                }
            }

            $service = Service::firstOrCreate(['name' => 'Consultation Fee'], ['price' => 50000]);
            $bill->billServices()->firstOrCreate(
                ['service_id' => $service->id],
                [
                    'quantity' => 1,
                    'price' => $service->price,
                ]
            );

            $registration->update(['status' => RegistrationStatus::PENDING]);
        });

        return redirect()->route('doctor.records.index')->with('success', 'Medical record saved successfully.');
    }

    public function history()
    {
        $doctor = auth()->user()->doctor;
        $records = MedicalRecord::with(['registration.patient'])
            ->where('doctor_id', $doctor->id)
            ->latest()
            ->paginate(10);

        return view('doctor.records.history', compact('records'));
    }

    public function show(MedicalRecord $record)
    {
        if ($record->doctor_id !== auth()->user()->doctor->id) {
            abort(403);
        }

        $record->load(['registration.patient', 'prescriptions.medicine']);
        return view('doctor.records.show', compact('record'));
    }
}
