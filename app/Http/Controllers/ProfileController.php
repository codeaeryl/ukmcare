<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Update the patient specific profile information.
     */
    public function patientUpdate(Request $request): RedirectResponse
    {
        $request->validate([
            'blood_type' => ['nullable', 'string', 'in:A,B,AB,O'],
            'bpjs_number' => ['nullable', 'string', 'max:20'],
        ]);

        $patient = $request->user()->patient;
        
        if ($patient) {
            $data = [
                'blood_type' => $request->blood_type,
            ];

            // If bpjs_number is updated and different, set status to pending
            if ($request->bpjs_number !== $patient->bpjs_number) {
                $data['bpjs_number'] = $request->bpjs_number;
                $data['bpjs_status'] = $request->bpjs_number ? 'pending' : 'unverified';
            }

            $patient->update($data);
        }

        return Redirect::route('profile.edit')->with('status', 'patient-updated');
    }
}
