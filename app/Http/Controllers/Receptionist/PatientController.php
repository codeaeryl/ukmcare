<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Patient;
use App\Enums\Role;
use App\Enums\Gender;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rules\Enum;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        $patients = $query->latest()->paginate(10)->withQueryString();

        return view('receptionist.patients.index', compact('patients'));
    }

    public function create()
    {
        $genders = Gender::cases();
        return view('receptionist.patients.create', compact('genders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'nik' => ['required', 'string', 'size:16', 'unique:patients,nik'],
            'pob' => ['nullable', 'string', 'max:50'],
            'dob' => ['required', 'date'],
            'gender' => ['required', 'string', new Enum(Gender::class)],
            'address' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:15'],
            'blood_type' => ['nullable', 'string', 'max:3'],
            'bpjs_number' => ['nullable', 'string', 'max:20', 'unique:patients,bpjs_number'],
        ]);

        DB::transaction(function () use ($request) {
            $defaultPassword = str_replace('-', '', $request->dob); // YYYYMMDD

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($defaultPassword),
                'role' => Role::PATIENT,
            ]);

            $latestPatient = Patient::lockForUpdate()->orderBy('created_at', 'desc')->first();
            $nextIdNumber = 1;
            if ($latestPatient && preg_match('/(\d+)$/', $latestPatient->id, $matches)) {
                $nextIdNumber = intval($matches[1]) + 1;
            }
            $mrn = 'MRN-' . date('Y') . str_pad($nextIdNumber, 4, '0', STR_PAD_LEFT);

            Patient::create([
                'id' => $mrn,
                'user_id' => $user->id,
                'nik' => $request->nik,
                'full_name' => $request->name,
                'pob' => $request->pob,
                'dob' => $request->dob,
                'gender' => Gender::from($request->gender),
                'address' => $request->address,
                'phone' => $request->phone,
                'blood_type' => $request->blood_type,
                'bpjs_number' => $request->bpjs_number,
            ]);
        });

        return redirect()->route('receptionist.patients.index')->with('success', 'Patient registered successfully.');
    }

    public function show($id)
    {
        $patient = Patient::with(['user', 'registrations.schedule.doctor', 'registrations.medicalRecord'])->findOrFail($id);
        return view('receptionist.patients.show', compact('patient'));
    }

    public function edit($id)
    {
        $patient = Patient::with('user')->findOrFail($id);
        $genders = Gender::cases();
        return view('receptionist.patients.edit', compact('patient', 'genders'));
    }

    public function update(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);
        $user = $patient->user;

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'nik' => ['required', 'string', 'size:16', 'unique:patients,nik,'.$patient->id],
            'pob' => ['nullable', 'string', 'max:50'],
            'dob' => ['required', 'date'],
            'gender' => ['required', 'string', new Enum(Gender::class)],
            'address' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:15'],
            'blood_type' => ['nullable', 'string', 'max:3'],
            'bpjs_number' => ['nullable', 'string', 'max:20', 'unique:patients,bpjs_number,'.$patient->id],
        ]);

        DB::transaction(function () use ($request, $patient, $user) {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            $patient->update([
                'nik' => $request->nik,
                'full_name' => $request->name,
                'pob' => $request->pob,
                'dob' => $request->dob,
                'gender' => Gender::from($request->gender),
                'address' => $request->address,
                'phone' => $request->phone,
                'blood_type' => $request->blood_type,
                'bpjs_number' => $request->bpjs_number,
            ]);
        });

        return redirect()->route('receptionist.patients.index')->with('success', 'Patient updated successfully.');
    }

    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        $user = $patient->user;

        try {
            DB::transaction(function() use ($patient, $user) {
                $patient->delete();
                if ($user) {
                    $user->delete();
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->with('error', 'This patient cannot be deleted because they have associated records in the system.');
        }

        return redirect()->route('receptionist.patients.index')->with('success', 'Patient deleted successfully.');
    }
}
