<?php

namespace App\Http\Controllers;

use App\Models\ConcomitantDiseases;
use Illuminate\Http\Request;

class ConcomitantDiseasesController extends Controller
{
    public function index(Request $request)
    {
        $diseases = ConcomitantDiseases::where('name', 'like', '%' . $request->input('search') . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(9);
        
        return view('dashboard.concomitant-diseases', [
            'diseases' => $diseases,
            'search' => $request->input('search')
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:medical,surgical,gynae & obst'
        ]);

        ConcomitantDiseases::create($validatedData);

        return redirect()->back()->with('success', 'Disease created successfully.');
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:medical,surgical,gynae & obst'
        ]);

        $disease = ConcomitantDiseases::findOrFail($id);
        $disease->update($validatedData);

        return redirect()->back()->with('success', 'Disease updated successfully.');
    }

    public function delete($id)
    {
        $disease = ConcomitantDiseases::findOrFail($id);
        $disease->delete();

        return redirect()->back()->with('success', 'Disease deleted successfully.');
    }
}
