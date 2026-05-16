<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use App\Enums\Role;
use App\Enums\Gender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['patient', 'doctor'])->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::cases();
        $genders = Gender::cases();
        return view('admin.users.create', compact('roles', 'genders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string'],
        ]);

        if ($request->role === 'patient') {
            $request->validate([
                'nik_patient' => ['required', 'string', 'size:16', 'unique:patients,nik'],
                'pob' => ['nullable', 'string', 'max:50'],
                'dob' => ['required', 'date'],
                'gender' => ['required', 'string'],
                'address' => ['nullable', 'string', 'max:255'],
                'phone_patient' => ['nullable', 'string', 'max:15'],
                'blood_type' => ['nullable', 'string', 'max:3'],
                'bpjs_number' => ['nullable', 'string', 'max:20', 'unique:patients,bpjs_number'],
            ]);
        } elseif ($request->role === 'doctor') {
            $request->validate([
                'nik_doctor' => ['required', 'string', 'size:16', 'unique:doctors,nik'],
                'sip' => ['required', 'string', 'max:50', 'unique:doctors,sip'],
                'str' => ['required', 'string', 'max:50', 'unique:doctors,str'],
                'specialist' => ['required', 'string', 'max:50'],
                'phone_doctor' => ['nullable', 'string', 'max:15'],
                'is_bpjs' => ['boolean'],
                'is_active' => ['boolean'],
            ]);
        }

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => Role::from($request->role),
            ]);

            if ($request->role === 'patient') {
                $latestPatient = Patient::orderBy('created_at', 'desc')->first();
                $nextIdNumber = 1;
                if ($latestPatient && preg_match('/-(\d+)/', $latestPatient->id, $matches)) {
                    $nextIdNumber = intval($matches[1]) + 1;
                }
                $mrn = 'MRN-' . date('Y') . str_pad($nextIdNumber, 4, '0', STR_PAD_LEFT);

                Patient::create([
                    'id' => $mrn,
                    'user_id' => $user->id,
                    'nik' => $request->nik_patient,
                    'full_name' => $request->name,
                    'pob' => $request->pob,
                    'dob' => $request->dob,
                    'gender' => $request->gender,
                    'address' => $request->address,
                    'phone' => $request->phone_patient,
                    'blood_type' => $request->blood_type,
                    'bpjs_number' => $request->bpjs_number,
                ]);
            } elseif ($request->role === 'doctor') {
                $latestDoctor = Doctor::orderBy('created_at', 'desc')->first();
                $nextIdNumber = 1;
                if ($latestDoctor && preg_match('/-(\d+)/', $latestDoctor->id, $matches)) {
                    $nextIdNumber = intval($matches[1]) + 1;
                }
                $docId = 'DOC-' . str_pad($nextIdNumber, 4, '0', STR_PAD_LEFT);

                Doctor::create([
                    'id' => $docId,
                    'user_id' => $user->id,
                    'nik' => $request->nik_doctor,
                    'sip' => $request->sip,
                    'str' => $request->str,
                    'full_name' => $request->name,
                    'specialist' => $request->specialist,
                    'phone' => $request->phone_doctor,
                    'is_bpjs' => $request->has('is_bpjs'),
                    'is_active' => $request->has('is_active'),
                ]);
            }
        });

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function show($id)
    {
        $user = User::with(['patient.registrations.medicalRecord', 'doctor.medicalRecords'])->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::with(['patient', 'doctor'])->findOrFail($id);
        $roles = Role::cases();
        $genders = Gender::cases();
        return view('admin.users.edit', compact('user', 'roles', 'genders'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string'],
        ]);

        if ($request->role === 'patient') {
            $patientId = $user->patient ? $user->patient->id : 'null';
            $request->validate([
                'nik_patient' => ['required', 'string', 'size:16', 'unique:patients,nik,'.$patientId],
                'pob' => ['nullable', 'string', 'max:50'],
                'dob' => ['required', 'date'],
                'gender' => ['required', 'string'],
                'address' => ['nullable', 'string', 'max:255'],
                'phone_patient' => ['nullable', 'string', 'max:15'],
                'blood_type' => ['nullable', 'string', 'max:3'],
                'bpjs_number' => ['nullable', 'string', 'max:20', 'unique:patients,bpjs_number,'.$patientId],
            ]);
        } elseif ($request->role === 'doctor') {
            $doctorId = $user->doctor ? $user->doctor->id : 'null';
            $request->validate([
                'nik_doctor' => ['required', 'string', 'size:16', 'unique:doctors,nik,'.$doctorId],
                'sip' => ['required', 'string', 'max:50', 'unique:doctors,sip,'.$doctorId],
                'str' => ['required', 'string', 'max:50', 'unique:doctors,str,'.$doctorId],
                'specialist' => ['required', 'string', 'max:50'],
                'phone_doctor' => ['nullable', 'string', 'max:15'],
                'is_bpjs' => ['boolean'],
                'is_active' => ['boolean'],
            ]);
        }

        DB::transaction(function () use ($request, $user) {
            $user->name = $request->name;
            $user->email = $request->email;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            
            // Handle role change (e.g. admin -> patient)
            $oldRole = $user->role->value;
            $user->role = Role::from($request->role);
            $user->save();

            // If role changed to something else, we might want to delete the old profile or keep it.
            // For simplicity, we just delete the old profile if it doesn't match the new role.
            if ($oldRole !== $request->role) {
                if ($oldRole === 'patient' && $user->patient) {
                    $user->patient->delete();
                } elseif ($oldRole === 'doctor' && $user->doctor) {
                    $user->doctor->delete();
                }
            }

            if ($request->role === 'patient') {
                if (!$user->patient) {
                    $latestPatient = Patient::orderBy('created_at', 'desc')->first();
                    $nextIdNumber = 1;
                    if ($latestPatient && preg_match('/-(\d+)/', $latestPatient->id, $matches)) {
                        $nextIdNumber = intval($matches[1]) + 1;
                    }
                    $mrn = 'MRN-' . date('Y') . str_pad($nextIdNumber, 4, '0', STR_PAD_LEFT);

                    $user->patient()->create([
                        'id' => $mrn,
                        'nik' => $request->nik_patient,
                        'full_name' => $request->name,
                        'pob' => $request->pob,
                        'dob' => $request->dob,
                        'gender' => $request->gender,
                        'address' => $request->address,
                        'phone' => $request->phone_patient,
                        'blood_type' => $request->blood_type,
                        'bpjs_number' => $request->bpjs_number,
                    ]);
                } else {
                    $user->patient->update([
                        'nik' => $request->nik_patient,
                        'full_name' => $request->name,
                        'pob' => $request->pob,
                        'dob' => $request->dob,
                        'gender' => $request->gender,
                        'address' => $request->address,
                        'phone' => $request->phone_patient,
                        'blood_type' => $request->blood_type,
                        'bpjs_number' => $request->bpjs_number,
                    ]);
                }
            } elseif ($request->role === 'doctor') {
                if (!$user->doctor) {
                    $latestDoctor = Doctor::orderBy('created_at', 'desc')->first();
                    $nextIdNumber = 1;
                    if ($latestDoctor && preg_match('/-(\d+)/', $latestDoctor->id, $matches)) {
                        $nextIdNumber = intval($matches[1]) + 1;
                    }
                    $docId = 'DOC-' . str_pad($nextIdNumber, 4, '0', STR_PAD_LEFT);

                    $user->doctor()->create([
                        'id' => $docId,
                        'nik' => $request->nik_doctor,
                        'sip' => $request->sip,
                        'str' => $request->str,
                        'full_name' => $request->name,
                        'specialist' => $request->specialist,
                        'phone' => $request->phone_doctor,
                        'is_bpjs' => $request->has('is_bpjs'),
                        'is_active' => $request->has('is_active'),
                    ]);
                } else {
                    $user->doctor->update([
                        'nik' => $request->nik_doctor,
                        'sip' => $request->sip,
                        'str' => $request->str,
                        'full_name' => $request->name,
                        'specialist' => $request->specialist,
                        'phone' => $request->phone_doctor,
                        'is_bpjs' => $request->has('is_bpjs'),
                        'is_active' => $request->has('is_active'),
                    ]);
                }
            }
        });

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
