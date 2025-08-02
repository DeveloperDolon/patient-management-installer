<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicinesController extends Controller
{
    public function index(Request $request)
    {
        $medicines = Medicine::where('name', 'like', '%' . $request->input('search', '') . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('dashboard.medicines', [
            'medicines' => $medicines,
            'search' => $request->input('search', ''),
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Medicine::create($validatedData);

        return redirect()->back()->with('success', 'Medicine created successfully.');
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $medicine = Medicine::findOrFail($id);

        $medicine->update($validatedData);

        return redirect()->back()->with('success', 'Medicine updated successfully.');
    }

    public function delete($id)
    {
        $medicine = Medicine::findOrFail($id);
        $medicine->delete();

        return redirect()->back()->with('success', 'Medicine deleted successfully.');
    }
}
