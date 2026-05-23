<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class BpjsController extends Controller
{
    public function index()
    {
        $patients = Patient::with('user')
            ->whereIn('bpjs_status', ['pending', 'verified', 'rejected'])
            ->orderByRaw("CASE WHEN bpjs_status = 'pending' THEN 1 ELSE 2 END")
            ->latest()
            ->paginate(15);
            
        return view('admin.bpjs.index', compact('patients'));
    }

    public function update(Request $request, Patient $patient)
    {
        $request->validate([
            'status' => 'required|in:verified,rejected',
        ]);

        $patient->update([
            'bpjs_status' => $request->status
        ]);

        return back()->with('success', "BPJS status for {$patient->full_name} has been updated to {$request->status}.");
    }
}
