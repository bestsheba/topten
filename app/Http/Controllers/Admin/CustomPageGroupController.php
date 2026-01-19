<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomPageGroup;
use Illuminate\Http\Request;

class CustomPageGroupController extends Controller
{
    public function index()
    {
        if (!userCan('view pages')) { abort(403); }
        $groups = CustomPageGroup::ordered()->withCount('pages')->get();
        return view('admin.pages.custom-page-groups.index', compact('groups'));
    }

    public function store(Request $request)
    {
        if (!userCan('create page')) { abort(403); }
        $validated = $request->validate(['name' => 'required|string|max:255']);
        $nextOrder = (CustomPageGroup::max('sort_order') ?? 0) + 1;
        CustomPageGroup::create(['name' => $validated['name'], 'sort_order' => $nextOrder]);
        return back()->with('success', 'Group created');
    }

    public function update(Request $request, CustomPageGroup $custom_page_group)
    {
        if (!userCan('edit page')) { abort(403); }
        $validated = $request->validate(['name' => 'required|string|max:255']);
        $custom_page_group->update(['name' => $validated['name']]);
        return back()->with('success', 'Group updated');
    }

    public function destroy(CustomPageGroup $custom_page_group)
    {
        if (!userCan('delete page')) { abort(403); }
        $custom_page_group->delete();
        return back()->with('success', 'Group deleted');
    }

    public function reorder(Request $request)
    {
        if (!userCan('edit page')) { abort(403); }
        $request->validate([
            'order' => 'required|array',
            'order.*.id' => 'required|integer|exists:custom_page_groups,id',
            'order.*.position' => 'required|integer'
        ]);
        foreach ($request->order as $item) {
            CustomPageGroup::where('id', $item['id'])->update(['sort_order' => $item['position']]);
        }
        return response()->json(['status' => 'ok']);
    }
}


