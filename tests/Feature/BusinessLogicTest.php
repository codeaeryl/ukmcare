<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Schedule;
use App\Models\Registration;
use App\Models\Medicine;
use App\Models\MedicalRecord;
use App\Models\Prescription;
use App\Enums\Role;
use App\Enums\Gender;
use App\Enums\UserStatus;
use App\Enums\RegistrationStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class BusinessLogicTest extends TestCase
{
    use RefreshDatabase;

    public function test_inactive_doctor_is_not_listed_in_available_doctors()
    {
        // Create an active doctor
        $activeUser = User::factory()->create(['role' => Role::DOCTOR, 'status' => UserStatus::ACTIVE]);
        $activeDoctor = Doctor::create([
            'id' => 'DOC-0001',
            'nik' => '1234567890123456',
            'sip' => 'SIP/2026/001',
            'str' => 'STR/2026/001',
            'full_name' => 'Dr. Active',
            'specialist' => 'General Practitioner',
            'phone' => '081234567890',
            'is_bpjs' => true,
            'user_id' => $activeUser->id,
        ]);

        Schedule::create([
            'doctor_id' => $activeDoctor->id,
            'schedule_day' => 'Monday',
            'start_hour' => '08:00:00',
            'end_hour' => '12:00:00',
            'quota' => 20,
        ]);

        // Create an inactive doctor
        $inactiveUser = User::factory()->create(['role' => Role::DOCTOR, 'status' => UserStatus::INACTIVE]);
        $inactiveDoctor = Doctor::create([
            'id' => 'DOC-0002',
            'nik' => '1234567890123457',
            'sip' => 'SIP/2026/002',
            'str' => 'STR/2026/002',
            'full_name' => 'Dr. Inactive',
            'specialist' => 'General Practitioner',
            'phone' => '081234567891',
            'is_bpjs' => true,
            'user_id' => $inactiveUser->id,
        ]);

        Schedule::create([
            'doctor_id' => $inactiveDoctor->id,
            'schedule_day' => 'Monday',
            'start_hour' => '08:00:00',
            'end_hour' => '12:00:00',
            'quota' => 20,
        ]);

        // Login as patient
        $patientUser = User::factory()->create(['role' => Role::PATIENT]);
        $patient = Patient::create([
            'id' => 'MRN-20260001',
            'nik' => '3201012345678901',
            'full_name' => $patientUser->name,
            'dob' => '1990-01-01',
            'gender' => Gender::MALE,
            'user_id' => $patientUser->id,
        ]);

        $response = $this->actingAs($patientUser)->get(route('patient.appointments.create'));

        $response->assertStatus(200);
        $response->assertSee('Dr. Active');
        $response->assertDontSee('Dr. Inactive');
    }

    public function test_deleting_user_with_active_records_fails_gracefully()
    {
        // Admin user
        $admin = User::factory()->create(['role' => Role::ADMIN]);

        // Patient user with record
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

        $schedule = Schedule::create([
            'doctor_id' => $doctor->id,
            'schedule_day' => 'Monday',
            'start_hour' => '08:00:00',
            'end_hour' => '12:00:00',
            'quota' => 20,
        ]);

        Registration::create([
            'patient_id' => $patient->id,
            'schedule_id' => $schedule->id,
            'queue_number' => 1,
            'time_slot' => '08:00 - 08:20',
            'status' => RegistrationStatus::REGISTERED,
            'registration_date' => Carbon::now()->addDays(7)->toDateString(),
        ]);

        // Attempt delete patient user
        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $patientUser->id));

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $patientUser->id]);
    }

    public function test_deleting_medicine_with_prescriptions_fails_gracefully()
    {
        $pharmacist = User::factory()->create(['role' => Role::PHARMACIST]);

        $medicine = Medicine::create([
            'name' => 'Paracetamol',
            'stock' => 100,
            'price' => 5000,
            'unit' => 'tablet',
        ]);

        // Create referencing prescription
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

        $schedule = Schedule::create([
            'doctor_id' => $doctor->id,
            'schedule_day' => 'Monday',
            'start_hour' => '08:00:00',
            'end_hour' => '12:00:00',
            'quota' => 20,
        ]);

        $registration = Registration::create([
            'patient_id' => $patient->id,
            'schedule_id' => $schedule->id,
            'queue_number' => 1,
            'time_slot' => '08:00 - 08:20',
            'status' => RegistrationStatus::REGISTERED,
            'registration_date' => Carbon::now()->addDays(7)->toDateString(),
        ]);

        $record = MedicalRecord::create([
            'registration_id' => $registration->id,
            'doctor_id' => $doctor->id,
            'diagnosis' => 'Fever',
            'record_date' => Carbon::now()->toDateString(),
        ]);

        Prescription::create([
            'medical_record_id' => $record->id,
            'medicine_id' => $medicine->id,
            'quantity' => 10,
            'dosage' => '3x1',
        ]);

        // Attempt delete medicine
        $response = $this->actingAs($pharmacist)->delete(route('pharmacist.medicines.destroy', $medicine->id));

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('medicines', ['id' => $medicine->id]);
    }

    public function test_booking_past_time_slot_fails()
    {
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

        // Get the current day of week
        $todayDay = Carbon::now()->format('l');

        $schedule = Schedule::create([
            'doctor_id' => $doctor->id,
            'schedule_day' => $todayDay,
            'start_hour' => '08:00:00',
            'end_hour' => '12:00:00',
            'quota' => 20,
        ]);

        // Mock test time to be after the slot (e.g. 10:00:00)
        Carbon::setTestNow(Carbon::today()->setTime(10, 0, 0));

        $response = $this->actingAs($patientUser)->post(route('patient.appointments.store'), [
            'schedule_id' => $schedule->id,
            'registration_date' => Carbon::today()->toDateString(),
            'time_slot' => '08:00 - 08:20',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'The selected time slot has already passed for today.');

        Carbon::setTestNow(); // Reset Carbon time mocking
    }

    public function test_cancelling_past_appointment_fails()
    {
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

        $schedule = Schedule::create([
            'doctor_id' => $doctor->id,
            'schedule_day' => 'Monday',
            'start_hour' => '08:00:00',
            'end_hour' => '12:00:00',
            'quota' => 20,
        ]);

        // Registration date is in the past
        $registration = Registration::create([
            'patient_id' => $patient->id,
            'schedule_id' => $schedule->id,
            'queue_number' => 1,
            'time_slot' => '08:00 - 08:20',
            'status' => RegistrationStatus::REGISTERED,
            'registration_date' => Carbon::now()->subDays(1)->toDateString(),
        ]);

        $response = $this->actingAs($patientUser)->post(route('patient.appointments.cancel', $registration->id));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'You cannot cancel an appointment that has already started or passed.');
        
        $this->assertEquals(RegistrationStatus::REGISTERED, $registration->fresh()->status);
    }

    public function test_admin_cannot_change_their_own_role()
    {
        $admin = User::factory()->create(['role' => Role::ADMIN, 'status' => UserStatus::ACTIVE]);

        $response = $this->actingAs($admin)->put(route('admin.users.update', $admin->id), [
            'role' => Role::PATIENT->value,
            'status' => UserStatus::ACTIVE->value,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'You cannot change your own role.');
        $this->assertEquals(Role::ADMIN, $admin->fresh()->role);
    }

    public function test_admin_cannot_deactivate_their_own_account()
    {
        $admin = User::factory()->create(['role' => Role::ADMIN, 'status' => UserStatus::ACTIVE]);

        $response = $this->actingAs($admin)->put(route('admin.users.update', $admin->id), [
            'role' => Role::ADMIN->value,
            'status' => UserStatus::INACTIVE->value,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'You cannot deactivate your own account.');
        $this->assertEquals(UserStatus::ACTIVE, $admin->fresh()->status);
    }

    public function test_admin_cannot_delete_their_own_account()
    {
        $admin = User::factory()->create(['role' => Role::ADMIN, 'status' => UserStatus::ACTIVE]);

        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $admin->id));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'You cannot delete your own account.');
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }
}
