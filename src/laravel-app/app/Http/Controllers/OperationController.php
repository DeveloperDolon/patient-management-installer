<?php

namespace App\Http\Controllers;

use App\Models\Operation;
use Illuminate\Http\Request;

class OperationController extends Controller
{
    //
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $operations = Operation::where('name', 'like', '%' . $search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('dashboard.operation.index', compact([
            'operations',
            'search'
        ]));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Operation::create($request->only('name'));

        return redirect()->route('dashboard.operations')->with('success', 'Operation created successfully.');
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $operation = Operation::findOrFail($id);

        $operation->update($request->only('name'));

        return redirect()->route('dashboard.operations')->with('success', 'Operation updated successfully.');
    }

    public function delete($id)
    {
        Operation::findOrFail($id)->delete();

        return redirect()->route('dashboard.operations')->with('success', 'Operation deleted successfully.');
    }
}
