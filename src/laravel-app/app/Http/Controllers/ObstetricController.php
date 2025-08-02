<?php

namespace App\Http\Controllers;

use App\Models\Obstetric;
use Illuminate\Http\Request;

class ObstetricController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $obstetrics = Obstetric::where('name', 'like', '%' . $search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('dashboard.obstetric.index', compact([
            'obstetrics',
            'search',
        ]));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
        ]);

        Obstetric::create($request->only('name', 'type'));

        return redirect()->route('dashboard.obstetrics')->with('success', 'Obstetric created successfully.');
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
        ]);

        $obstetric = Obstetric::findOrFail($id);

        $obstetric->update($request->only('name', 'type'));

        return redirect()->route('dashboard.obstetrics')->with('success', 'Obstetric updated successfully.');
    }

    public function delete($id)
    {
        Obstetric::findOrFail($id)->delete();

        return redirect()->route('dashboard.obstetrics')->with('success', 'Obstetric deleted successfully.');
    }
}
