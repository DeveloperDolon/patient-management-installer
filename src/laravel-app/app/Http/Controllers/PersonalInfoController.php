<?php

namespace App\Http\Controllers;

use App\Models\PersonalInfo;
use Illuminate\Http\Request;

class PersonalInfoController extends Controller
{
    public function index()
    {
        $infos = PersonalInfo::where('name', 'like', '%' . request('search', '') . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('dashboard.personal-info', [
            'infos' => $infos,
            'search' => request('search', ''),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        PersonalInfo::create([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Info created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $info = PersonalInfo::findOrFail($id);

        $info->update([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Info updated successfully.');
    }

    public function delete($id)
    {
        $info = PersonalInfo::findOrFail($id);
        $info->delete();

        return redirect()->back()->with('success', 'Info deleted successfully.');
    }
}
