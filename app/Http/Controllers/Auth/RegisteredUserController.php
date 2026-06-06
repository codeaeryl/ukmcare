<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Patient;
use App\Enums\Role;
use App\Enums\Gender;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'nik' => ['required', 'string', 'size:16', 'unique:patients,nik'],
            'pob' => ['nullable', 'string', 'max:50'],
            'dob' => ['required', 'date'],
            'gender' => ['required', 'string', 'in:male,female'],
            'address' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:15'],
            'bpjs_number' => ['nullable', 'string', 'max:20', 'unique:patients,bpjs_number'],
        ]);

        $user = DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => Role::PATIENT,
            ]);

            $latestPatient = Patient::orderBy('created_at', 'desc')->first();
            $nextIdNumber = 1;
            if ($latestPatient && preg_match('/-(\d+)/', $latestPatient->id, $matches)) {
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
                'bpjs_number' => $request->bpjs_number,
                'bpjs_status' => $request->bpjs_number ? 'pending' : 'unverified',
            ]);

            return $user;
        });

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
