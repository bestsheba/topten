<?php

namespace App\Http\Controllers;

use App\Models\ExpenseType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ExpenseCategoryController extends Controller
{
    public function index()
    {
        $types = ExpenseType::paginate(10);
        return view('admin.pages.expenses.type', compact('types'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        ExpenseType::create([
            'name' => $request->input('name'),
            'admin_id' => auth()->id(),
        ]);

        return back()->with('success', 'Expense type created successfully.');
    }

    public function update(Request $request, ExpenseType $expenseType)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:expense_types,name,' . $expenseType->id,
        ]);

        $expenseType->update($request->only('name'));

        return back()->with('success', 'Expense type updated successfully.');
    }

    public function destroy(ExpenseType $expenseType)
    {
        if ($expenseType->expenses()->exists()) {
            return back()->with('error', 'Cannot delete type that is in use.');
        }

        $expenseType->delete();

        return back()->with('success', 'Expense type deleted successfully.');
    }
}
