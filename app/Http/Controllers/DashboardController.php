<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role->value === 'admin') {
            // For admin, we can just return the default dashboard view
            // In a real app, we might fetch stats like Total Patients, Appointments, etc.
            return view('dashboard');
        } elseif ($user->role->value === 'patient') {
            $patient = $user->patient;
            if (!$patient) {
                return view('patient.dashboard', ['missingProfile' => true]);
            }
            
            // Load patient's registrations and related schedule/doctor
            $patient->load(['registrations' => function($query) {
                $query->orderBy('registration_date', 'desc')->take(5);
            }, 'registrations.schedule.doctor', 'registrations.medicalRecord']);

            $totalVisits = $patient->registrations()->where('status', 'completed')->count();
            
            $upcomingAppointment = $patient->registrations()
                ->where('status', '!=', 'cancelled')
                ->where('registration_date', '>=', now()->startOfDay())
                ->orderBy('registration_date', 'asc')
                ->first();

            return view('patient.dashboard', compact('patient', 'totalVisits', 'upcomingAppointment'));
        } elseif ($user->role->value === 'doctor') {
            // Placeholder for doctor dashboard
            return view('dashboard');
        }

        return view('dashboard');
    }
}
