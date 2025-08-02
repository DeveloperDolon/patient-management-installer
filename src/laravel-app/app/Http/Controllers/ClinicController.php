<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClinicController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $clinics = Clinic::where('doctor_id', Auth::user()->id)
            ->orwhere('clinic_name', 'like', '%' . $search . '%')
            ->orWhere('clinic_address', 'like', '%' . $search . '%')
            ->orWhere('clinic_phone', 'like', '%' . $search . '%')
            ->orWhere('clinic_email', 'like', '%' . $search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('dashboard.clinic.index', compact(['search', 'clinics']));
    }

    public function create()
    {
        return view('dashboard.clinic.create');
    }

    public function store(Request $request)
    {
        $clinic = $request->validate([
            'clinic_name' => 'required|string|max:255',
            'clinic_address' => 'required|string|max:255',
            'clinic_phone' => 'required|string|max:15',
            'clinic_email' => 'nullable|email|max:255',
            'visit_time' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $clinic['logo'] = '/storage/' . $request->file('logo')->store('clinics', 'public');
        } else {
            $clinic['logo'] = null;
        }

        $clinic['doctor_id'] = Auth::user()->id;

        Clinic::create($clinic);

        return redirect()->route('dashboard.clinics')->with('success', 'Clinic added successfully.');
    }

    public function show($id)
    {
        $clinic = Clinic::findOrFail($id);
        return view('dashboard.clinic.show', compact('clinic'));
    }

    public function edit($id)
    {
        $clinic = Clinic::findOrFail($id);
        return view('dashboard.clinic.edit', compact('clinic'));
    }

    public function update(Request $request, $id)
    {
        $clinic = Clinic::findOrFail($id);

        $data = $request->validate([
            'clinic_name' => 'required|string|max:255',
            'clinic_address' => 'required|string|max:255',
            'clinic_phone' => 'required|string|max:15',
            'clinic_email' => 'nullable|email|max:255',
            'visit_time' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $data['logo'] = '/storage/' . $request->file('logo')->store('clinics', 'public');
        }

        $clinic->update($data);

        return redirect()->route('dashboard.clinics')->with('success', 'Clinic updated successfully.');
    }

    public function delete($id)
    {
        $clinic = Clinic::findOrFail($id);
        $clinic->delete();

        return redirect()->route('dashboard.clinics')->with('success', 'Clinic deleted successfully.');
    }

    public function toggleStatus(Request $request, $id)
    {
        $doctorId = Auth::user()->id;
        $request->validate([
            'is_active' => 'required|boolean',
        ]);

        if ($request->input('is_active')) {
            Clinic::where('doctor_id', $doctorId)->orWhere('is_active', true)->update(['is_active' => false]);
        }

        $clinic = Clinic::where('doctor_id', $doctorId)->where('id', $id)->first();

        if ($clinic) {
            $clinic->is_active = $request->input('is_active');
            $clinic->save();
            return redirect()->route('dashboard.clinics')->with('success', 'Clinic status updated successfully.');
        } else {
            return redirect()->route('dashboard.clinics')->with('error', 'Clinic not found.');
        }
    }
}
