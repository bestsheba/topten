<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AttributeController extends Controller
{
    public function index()
    {
        $attributes = Attribute::with('values')->get();
        return view('admin.attributes.index', compact('attributes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'values' => 'required|array',
            'values.*' => 'string|max:255'
        ]);
        
        $attribute = Attribute::create([
            'name' => $request->name,
            'slug' => \Str::slug($request->name)
        ]);
        
        foreach ($request->values as $value) {
            $attribute->values()->create(['value' => $value]);
        }
        
        return redirect()->route('admin.attributes.index')->with('success', 'Attribute created successfully!');
    }

    public function update(Request $request, Attribute $attribute)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'values' => 'required|array',
            'values.*' => 'string|max:255',
            'value_ids' => 'sometimes|array',
            'value_ids.*' => 'exists:attribute_values,id'
        ]);
        
        $attribute->update(['name' => $request->name]);
        
        // Update existing values
        if ($request->has('value_ids')) {
            foreach ($request->value_ids as $index => $id) {
                AttributeValue::where('id', $id)
                    ->update(['value' => $request->values[$index]]);
            }
        }
        
        // Add new values
        $existingValuesCount = $request->has('value_ids') ? count($request->value_ids) : 0;
        $newValues = array_slice($request->values, $existingValuesCount);
        
        foreach ($newValues as $value) {
            $attribute->values()->create(['value' => $value]);
        }
        
        return redirect()->route('admin.attributes.index')->with('success', 'Attribute updated successfully!');
    }

    public function destroy(Attribute $attribute)
    {
        $attribute->delete();
        return redirect()->route('admin.attributes.index')->with('success', 'Attribute deleted successfully!');
    }
    
    public function destroyValue(AttributeValue $value)
    {
        $value->delete();
        return back()->with('success', 'Value deleted successfully!');
    }
}