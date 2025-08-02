<?php

namespace App\Http\Controllers;

use App\Models\AssociatedSite;
use Illuminate\Http\Request;

class AssociatedSitesController extends Controller
{
    public function index(Request $request)
    {

        $associatedSites = AssociatedSite::where('site', 'like', '%' . $request->input('search') . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('dashboard.associated-site', ['associatedSites' => $associatedSites, 'search' => $request->input('search')]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'site' => 'required|string|max:255'
        ]);

        AssociatedSite::create($validatedData);

        return redirect()->back()->with('success', 'Associated site created successful!');
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'site' => 'required|string|max:255'
        ]);

        $associatedSite = AssociatedSite::findOrFail($id);

        $associatedSite->update($validatedData);

        return redirect()->back()->with('success', 'Associated site updated successful!');
    }

    public function delete($id)
    {
        $associatedSite = AssociatedSite::findOrFail($id);

        $associatedSite->delete();

        return redirect()->back()->with('success', 'Associated site deleted successful!');
    }
}
