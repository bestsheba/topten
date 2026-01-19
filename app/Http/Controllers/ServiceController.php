<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::paginate(20);

        return view('admin.pages.services.index', compact('services'));
    }
    public function create()
    {
        return view('admin.pages.services.create');
    }
    public function edit($id)
    {
        $service = Service::findOrFail($id);

        return view('admin.pages.services.edit', compact('service'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        Service::create([
            'name' => $request->name,
            'price' => $request->price,
        ]);

        return redirect()->route('admin.services.index')->with('success', 'Service created successfully.');
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $service = Service::findOrFail($id);
        $service->update([
            'name' => $request->name,
            'price' => $request->price,
        ]);

        return redirect()->route('admin.services.index')->with('success', 'Service updated successfully.');
    }
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return redirect()->route('admin.services.index')->with('success', 'Service deleted successfully.');
    }

}
