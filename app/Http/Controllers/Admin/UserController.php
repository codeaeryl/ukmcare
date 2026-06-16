<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Enums\Role;
use App\Enums\Gender;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rules\Enum;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['patient', 'doctor']);

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(10)->withQueryString();
        
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
            'role' => ['required', 'string', new Enum(Role::class)],
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
            ]);
        }

        $user = \App\Factories\UserFactory::make(Role::from($request->role))->createUser($request->all());

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
            'role' => ['required', 'string', new Enum(Role::class)],
        ]);

        if ($user->id === auth()->id()) {
            if ($user->role->value !== $request->role) {
                return back()->with('error', 'You cannot change your own role.');
            }
        }

        $user->role = Role::from($request->role);
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User account updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        try {
            $user->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->with('error', 'This user cannot be deleted because they have associated records (schedules, registrations, medical records, or bills) in the system.');
        }

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
