<?php

namespace App\Http\Controllers;

use App\Models\MenstrualDiseas;
use Illuminate\Http\Request;

class MenstrualDiseasesController extends Controller
{
    public function index()
    {
        $menstrualDiseases = MenstrualDiseas::where('name', 'like', '%' . request('search', '') . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('dashboard.menstrual-diseases', [
            'menstrualDiseases' => $menstrualDiseases,
            'search' => request('search', ''),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        MenstrualDiseas::create([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Menstrual disease created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $menstrualDisease = MenstrualDiseas::findOrFail($id);

        $menstrualDisease->update([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Menstrual disease updated successfully.');
    }

     public function delete($id)
    {
        $disease = MenstrualDiseas::findOrFail($id);
        $disease->delete();

        return redirect()->back()->with('success', 'Disease deleted successfully.');
    }
}
