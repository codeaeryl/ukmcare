<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Schedule;
use App\Models\Registration;
use App\Enums\Role;
use App\Enums\Gender;
use App\Enums\RegistrationStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class ReceptionistTest extends TestCase
{
    use RefreshDatabase;

    protected function getReceptionist()
    {
        return User::factory()->create([
            'role' => Role::RECEPTIONIST,
        ]);
    }

    public function test_receptionist_can_access_dashboard()
    {
        $receptionist = $this->getReceptionist();

        $response = $this->actingAs($receptionist)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Total Patients');
    }

    public function test_receptionist_can_register_new_patient()
    {
        $receptionist = $this->getReceptionist();

        $response = $this->actingAs($receptionist)->post(route('receptionist.patients.store'), [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'nik' => '1234567890123456',
            'pob' => 'Jakarta',
            'dob' => '1995-05-15',
            'gender' => Gender::MALE->value,
            'phone' => '0812345678',
            'blood_type' => 'O',
            'bpjs_number' => '1122334455',
            'address' => 'Test Address',
        ]);

        $response->assertRedirect(route('receptionist.patients.index'));
        $this->assertDatabaseHas('users', ['email' => 'john.doe@example.com']);
        $this->assertDatabaseHas('patients', ['nik' => '1234567890123456']);

        $user = User::where('email', 'john.doe@example.com')->first();
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('19950515', $user->password));
    }

    public function test_receptionist_can_schedule_walk_in_appointment()
    {
        $receptionist = $this->getReceptionist();

        $patientUser = User::factory()->create(['role' => Role::PATIENT]);
        $patient = Patient::create([
            'id' => 'MRN-20260001',
            'nik' => '3201012345678901',
            'full_name' => $patientUser->name,
            'dob' => '1990-01-01',
            'gender' => Gender::MALE,
            'user_id' => $patientUser->id,
        ]);

        $doctorUser = User::factory()->create(['role' => Role::DOCTOR]);
        $doctor = Doctor::create([
            'id' => 'DOC-0001',
            'nik' => '1234567890123456',
            'sip' => 'SIP/2026/001',
            'str' => 'STR/2026/001',
            'full_name' => 'Dr. House',
            'specialist' => 'General Practitioner',
            'phone' => '081234567890',
            'is_bpjs' => true,
            'user_id' => $doctorUser->id,
        ]);

        // Find date that falls on a Monday
        $nextMonday = Carbon::now()->next(Carbon::MONDAY)->toDateString();

        $schedule = Schedule::create([
            'doctor_id' => $doctor->id,
            'schedule_day' => 'Monday',
            'start_hour' => '08:00:00',
            'end_hour' => '12:00:00',
            'quota' => 20,
        ]);

        $response = $this->actingAs($receptionist)->post(route('receptionist.appointments.store'), [
            'patient_id' => $patient->id,
            'schedule_id' => $schedule->id,
            'registration_date' => $nextMonday,
            'time_slot' => '08:00 - 08:20',
        ]);

        $response->assertRedirect(route('receptionist.appointments.index'));
        $this->assertDatabaseHas('registrations', [
            'patient_id' => $patient->id,
            'schedule_id' => $schedule->id,
            'time_slot' => '08:00 - 08:20',
        ]);
    }
}
