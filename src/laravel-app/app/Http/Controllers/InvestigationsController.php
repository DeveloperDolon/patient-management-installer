<?php

namespace App\Http\Controllers;

use App\Models\Investigation;
use Illuminate\Http\Request;

class InvestigationsController extends Controller
{
    public function index(Request $request)
    {
        $investigations = Investigation::where('investigation', 'like', '%' . $request->input('search') . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('dashboard.investigations', ['investigations' => $investigations, 'search' => $request->input('search')]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'investigation' => 'required|string|max:255',
            'type' => 'required|in:routine_test,special_test'
        ]);

        Investigation::create($validatedData);

        return redirect()->back()->with('success', 'Investigation created successful!');
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'investigation' => 'required|string|max:255',
            'type' => 'required|in:routine_test,special_test'
        ]);

        $investigation = Investigation::findOrFail($id);

        $investigation->update($validatedData);

        return redirect()->back()->with('success', 'Investigation updated successful!');
    }

     public function delete($id)
    {
        $investigation = Investigation::findOrFail($id);
        $investigation->delete();

        return redirect()->back()->with('success', 'Investigation deleted successfully.');
    }
}
