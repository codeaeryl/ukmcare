<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Registration;
use App\Models\Payment;
use App\Models\Log;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role->value === 'admin') {
            $totalPatients = Patient::count();
            $totalDoctors = Doctor::count();
            $totalAppointments = Registration::count();
            $totalRevenue = Payment::sum('paid_amount');

            $recentActivities = Log::with('user')->latest()->take(5)->get();
            $upcomingVisits = Registration::with('patient', 'schedule.doctor')
                ->where('status', 'registered')
                ->whereDate('registration_date', '>=', now())
                ->orderBy('registration_date', 'asc')
                ->take(5)
                ->get();

            return view('dashboard', compact('totalPatients', 'totalDoctors', 'totalAppointments', 'totalRevenue', 'recentActivities', 'upcomingVisits'));
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
