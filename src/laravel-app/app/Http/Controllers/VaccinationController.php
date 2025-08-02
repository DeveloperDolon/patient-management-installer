<?php

namespace App\Http\Controllers;

use App\Models\Vaccination;
use Illuminate\Http\Request;

class VaccinationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $vaccinations = Vaccination::where('name', 'like', '%' . $search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('dashboard.vaccination.index', compact([
            'vaccinations',
            'search',
        ]));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
        ]);

        Vaccination::create($request->only('name', 'type'));

        return redirect()->route('dashboard.vaccinations')->with('success', 'Vaccination created successfully.');
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
        ]);

        $vaccination = Vaccination::findOrFail($id);

        $vaccination->update($request->only('name', 'type'));

        return redirect()->route('dashboard.vaccinations')->with('success', 'Vaccination updated successfully.');
    }

    public function delete($id)
    {
        Vaccination::findOrFail($id)->delete();

        return redirect()->route('dashboard.vaccinations')->with('success', 'Vaccination deleted successfully.');
    }
}
