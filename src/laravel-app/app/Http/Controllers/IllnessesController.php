<?php

namespace App\Http\Controllers;

use App\Models\IllnessModel;
use Illuminate\Http\Request;

class IllnessesController extends Controller
{
    public function index(Request $request)
    {
        $illnesses = IllnessModel::where('illness', 'like', '%' . $request->input('search', '') . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('dashboard.illnesses-management', [
            'illnesses' => $illnesses,
            'search' => $request->input('search', ''),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'illness' => 'required|string|max:255',
        ]);

        IllnessModel::create([
            'illness' => $request->illness,
        ]);

        return redirect()->back()->with('success', 'Illness created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'illness' => 'required|string|max:255',
        ]);

        $illness = IllnessModel::findOrFail($id);

        $illness->update([
            'illness' => $request->illness,
        ]);

        return redirect()->back()->with('success', 'Illness updated successfully.');
    }

     public function delete($id)
    {
        $illness = IllnessModel::findOrFail($id);
        $illness->delete();

        return redirect()->back()->with('success', 'Illness deleted successfully.');
    }
}
