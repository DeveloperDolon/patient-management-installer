<?php

namespace App\Http\Controllers;

use App\Models\SpecialProcedure;
use Illuminate\Http\Request;

class SpecialProceduresController extends Controller
{
    public function index(Request $request)
    {
        $specialProcedures = SpecialProcedure::where('procedure', 'like', '%' . $request->input('search') . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view(
            'dashboard.special-procedures',
            [
                'specialProcedures' => $specialProcedures,
                'search' => $request->input('search')
            ]
        );
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'procedure' => 'required|string|max:255'
        ]);

        SpecialProcedure::create($validatedData);

        return redirect()->back()->with('success', 'Procedure created successful!');
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'procedure' => 'required|string|max:255'
        ]);

        $procedure = SpecialProcedure::findOrFail($id);
        $procedure->update($validatedData);

        return redirect()->back()->with('success', 'Procedure updated successful!');
    }

    public function delete($id)
    {
        $specialProcedure = SpecialProcedure::findOrFail($id);
        $specialProcedure->delete();

        return redirect()->back()->with('success', 'Special procedure deleted successfully.');
    }
}
