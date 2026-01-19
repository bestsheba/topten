<?php

namespace App\Http\Controllers;

use App\Models\Tailor;
use Illuminate\Http\Request;

class TailorController extends Controller
{
    public function index()
    {
        $tailors = Tailor::latest()->paginate(10);
        return view('admin.tailors.index', compact('tailors'));
    }

    public function create()
    {
        return view('admin.tailors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Tailor::create($request->only('name'));

        return redirect()->route('admin.tailors.index')
            ->with('success', 'Tailor added successfully');
    }

    public function edit(Tailor $tailor)
    {
        return view('admin.tailors.edit', compact('tailor'));
    }

    public function update(Request $request, Tailor $tailor)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $tailor->update($request->only('name'));

        return redirect()->route('admin.tailors.index')
            ->with('success', 'Tailor updated successfully');
    }

    public function destroy(Tailor $tailor)
    {
        $tailor->delete();

        return back()->with('success', 'Tailor deleted successfully');
    }
}
