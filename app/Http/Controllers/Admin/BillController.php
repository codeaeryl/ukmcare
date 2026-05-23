<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Registration;
use App\Models\Medicine;
use App\Models\Service;
use App\Models\Payment;
use App\Enums\BillStatus;
use App\Enums\RegistrationStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillController extends Controller
{
    public function index()
    {
        $bills = Bill::with(['registration.patient', 'payment'])->latest()->paginate(10);
        return view('admin.bills.index', compact('bills'));
    }

    public function create()
    {
        $registrations = Registration::where('status', RegistrationStatus::PENDING)
            ->whereDoesntHave('bill')
            ->with('patient')
            ->get();
            
        return view('admin.bills.create', compact('registrations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'registration_id' => 'required|exists:registrations,id|unique:bills,registration_id',
        ]);

        $registration = Registration::with('medicalRecord.prescriptions.medicine')->findOrFail($request->registration_id);

        DB::transaction(function () use ($registration) {
            $bill = Bill::create([
                'registration_id' => $registration->id,
                'date' => now(),
                'status' => BillStatus::PENDING,
            ]);

            if ($registration->medicalRecord && $registration->medicalRecord->prescriptions) {
                foreach ($registration->medicalRecord->prescriptions as $prescription) {
                    $bill->billMedicines()->create([
                        'medicine_id' => $prescription->medicine_id,
                        'quantity' => $prescription->quantity,
                        'price' => $prescription->medicine->price,
                    ]);
                }
            }

            $service = Service::firstOrCreate(['name' => 'Consultation Fee'], ['price' => 50000]);
            $bill->billServices()->create([
                'service_id' => $service->id,
                'quantity' => 1,
                'price' => $service->price,
            ]);
        });

        return redirect()->route('admin.bills.index')->with('success', 'Bill generated successfully.');
    }

    public function show(Bill $bill)
    {
        $bill->load(['registration.patient', 'billMedicines.medicine', 'billServices.service', 'payment']);
        
        $totalMedicines = $bill->billMedicines->sum(function($item) {
            return $item->quantity * $item->price;
        });
        
        $totalServices = $bill->billServices->sum(function($item) {
            return $item->quantity * $item->price;
        });
        
        $grandTotal = $totalMedicines + $totalServices;

        return view('admin.bills.show', compact('bill', 'grandTotal'));
    }

    public function pay(Request $request, Bill $bill)
    {
        if ($bill->status === BillStatus::COMPLETE) {
            return back()->with('error', 'Bill is already paid.');
        }

        $request->validate([
            'payment_method' => 'required|string',
            'amount_paid' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $bill) {
            Payment::create([
                'bill_id' => $bill->id,
                'payment_date' => now(),
                'paid_amount' => $request->amount_paid,
                'payment_method' => $request->payment_method,
            ]);

            $bill->update(['status' => BillStatus::COMPLETE]);
            $bill->registration->update(['status' => RegistrationStatus::COMPLETED]);
        });

        return redirect()->route('admin.bills.index')->with('success', 'Payment processed successfully.');
    }
}
