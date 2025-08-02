<?php

namespace App\Http\Controllers;

use App\Models\Examination;
use Illuminate\Http\Request;

class ExaminationsController extends Controller
{
    public function index(Request $request)
    {
        $examinations = Examination::where('examination', 'like', '%' . $request->input('search', '') . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('dashboard.examinations', [
            'examinations' => $examinations,
            'search' => $request->input('search', ''),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'examination' => 'required|string|max:255',
            'type' => 'required|string|in:general,dermatological,systemic',
        ]);

        Examination::create([
            'examination' => $request->examination,
            'type' => $request->type,
        ]);

        return redirect()->back()->with('success', 'Examination created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'examination' => 'required|string|max:255',
            'type' => 'required|string|in:general,dermatological,systemic',
        ]);

        $examination = Examination::findOrFail($id);
        $examination->update([
            'examination' => $request->examination,
            'type' => $request->type,
        ]);

        return redirect()->back()->with('success', 'Examination updated successfully.');
    }

     public function delete($id)
    {
        $examination = Examination::findOrFail($id);
        $examination->delete();

        return redirect()->back()->with('success', 'Examination deleted successfully.');
    }
}
