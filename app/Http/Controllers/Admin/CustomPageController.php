<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomPage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CustomPageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!userCan('view pages')) {
            abort(403);
        }
        $data['custom_pages'] = CustomPage::ordered()->get();
        $data['groups'] = \App\Models\CustomPageGroup::ordered()->withCount('pages')->get();
        return view('admin.pages.custom-pages.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!userCan('create page')) {
            abort(403);
        }
        return view('admin.pages.custom-pages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!userCan('create page')) {
            abort(403);
        }
        $validatedData = $request->validate([
            'title' => 'required|string|max:255|unique:custom_pages,title',
            'description' => 'required|string',
            'custom_page_group_id' => 'nullable|exists:custom_page_groups,id',
            'link' => 'nullable|url|max:255'
        ]);

        $nextOrder = (CustomPage::max('sort_order') ?? 0) + 1;

        CustomPage::create([
            'title' => $validatedData['title'],
            'slug' => Str::slug($validatedData['title']),
            'description' => $validatedData['description'],
            'sort_order' => $nextOrder,
            'custom_page_group_id' => $validatedData['custom_page_group_id'] ?? null,
            'link' => $validatedData['link'] ?? null,
        ]);

        return redirect()->route('admin.custom-page.index')
                         ->with('success', 'Custom Page created successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (!userCan('edit page')) {
            abort(403);
        }
        $data['custom_page'] = CustomPage::find($id);
        return view('admin.pages.custom-pages.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CustomPage $customPage)
    {
        if (!userCan('edit page')) {
            abort(403);
        }
        $validatedData = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('custom_pages', 'title')->ignore($customPage->id)
            ],
            'description' => 'required|string',
            'custom_page_group_id' => 'nullable|exists:custom_page_groups,id',
            'link' => 'nullable|url|max:255'
        ]);

        $customPage->update([
            'title' => $validatedData['title'],
            'slug' => Str::slug($validatedData['title']),
            'description' => $validatedData['description'],
            'custom_page_group_id' => $validatedData['custom_page_group_id'] ?? null,
            'link' => $validatedData['link'] ?? null,
        ]);

        return redirect()->route('admin.custom-page.index')
                         ->with('success', 'Custom Page updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!userCan('delete page')) {
            abort(403);
        }
        CustomPage::find($id)->delete();

        return redirect()->route('admin.custom-page.index');
    }

    public function reorder(Request $request)
    {
        if (!userCan('edit page')) {
            abort(403);
        }
        $request->validate([
            'order' => 'required|array',
            'order.*.id' => 'required|integer|exists:custom_pages,id',
            'order.*.position' => 'required|integer'
        ]);

        foreach ($request->order as $item) {
            CustomPage::where('id', $item['id'])->update(['sort_order' => $item['position']]);
        }

        return response()->json(['status' => 'ok']);
    }
}
