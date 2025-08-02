<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientsController extends Controller
{
    public function index(Request $request)
    {
        $patients = Patient::where('name', 'like', '%' . $request->input('search') . '%')
            ->orWhere('phone', 'like', '%' . $request->input('search') . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('dashboard.patients', ['patients' => $patients, 'search' => $request->input('search')]);
    }

    public function create()
    {
        return view('dashboard.create-patients');
    }

    public function details($id)
    {
        $patient = Patient::findOrFail($id);

        return view('dashboard.patient-details', ['patient' => $patient]);
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            "name" => "required|string|max:255",
            "age" => "required|integer|min:0",
            "occupation" => "nullable|string|max:255",
            "gender" => "required|in:male,female,other",
            "phone" => "required|string|max:20",
            "address" => "nullable|string|max:500",
            "religion" => "nullable|string|max:100",
            "blood_group" => "nullable|string|max:10",
            "date_of_birth" => "required|date",
            "profile_picture" => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048",
            "report_images" => "nullable|array",
            "report_images.*" => "image|mimes:jpeg,png,jpg,gif|max:2048",
        ]);

        if ($request->hasFile('profile_picture')) {
            $profilePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            $validatedData['profile_picture'] = '/storage/' . $profilePath;
        }

        if ($request->hasFile('report_images')) {
            $reportPaths = [];
            foreach ($request->file('report_images') as $image) {
                $path = $image->store('report_images', 'public');
                $reportPaths[] = '/storage/' . $path;
            }

            $validatedData['report_images'] = json_encode($reportPaths);
        }

        $validatedData['doctor_id'] = Auth::user()->id;

        Patient::create($validatedData);

        return redirect()->back()->with('success', 'Patients created successfully.');
    }

    public function edit($id)
    {
        $patient = Patient::findOrFail($id);

        return view('dashboard.patient-edit', ['patient' => $patient]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            "name" => "required|string|max:255",
            "age" => "required|integer|min:0",
            "occupation" => "nullable|string|max:255",
            "gender" => "required|in:male,female,other",
            "phone" => "required|string|max:20",
            "address" => "nullable|string|max:500",
            "religion" => "nullable|string|max:100",
            "blood_group" => "nullable|string|max:10",
            "date_of_birth" => "required|date",
            "profile_picture" => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048",
            "report_images" => "nullable|array",
            "report_images.*" => "image|mimes:jpeg,png,jpg,gif|max:2048",
        ]);

        if ($request->hasFile('profile_picture')) {
            $profilePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            $validatedData['profile_picture'] = '/storage/' . $profilePath;
        }

        if ($request->hasFile('report_images')) {
            $reportPaths = [];
            foreach ($request->file('report_images') as $image) {
                $path = $image->store('report_images', 'public');
                $reportPaths[] = '/storage/' . $path;
            }

            $validatedData['report_images'] = json_encode($reportPaths);
        }

        $patient = Patient::findOrFail($id);

        $patient->update($validatedData);

        return redirect()->back()->with('success', 'Patients created successfully.');
    }

    public function delete($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();

        return redirect()->back()->with('success', 'Patient deleted successfully.');
    }

    public function prescriptions(Request $request, $id)
    {
        $search = $request->query('search');

        $patient = Patient::findOrFail($id);

        $prescriptions = $patient->prescriptions()
            ->when($search, function ($query) use ($search) {
                $query->where('complaints', 'like', '%' . $search . '%');
            })
            ->paginate(10);

        return view('dashboard.prescriptions', compact('patient', 'prescriptions', 'search'));
    }
}
