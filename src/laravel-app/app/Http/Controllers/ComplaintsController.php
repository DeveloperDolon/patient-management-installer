<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintsController extends Controller
{
    public function index(Request $request)
    {
        $complaints = Complaint::where('complaint', 'like', '%' . $request->input('search', '') . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('dashboard.complaints', [
            'complaints' => $complaints,
            'search' => $request->input('search', ''),
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'complaint' => 'required|string|max:255',
        ]);

        Complaint::create($validatedData);

        return redirect()->back()->with('success', 'Complaint created successfully.');
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'complaint' => 'required|string|max:255',
        ]);

        $complaint = Complaint::findOrFail($id);
        $complaint->update($validatedData);

        return redirect()->back()->with('success', 'Complaint updated successfully.');
    }

    public function delete($id)
    {
        $complaint = Complaint::findOrFail($id);
        $complaint->delete();

        return redirect()->back()->with('success', 'Complaint deleted successfully.');
    }
}
