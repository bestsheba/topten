<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with('expenseType')
            ->latest()
            ->paginate(15);

        return view('admin.pages.expenses.index', compact('expenses'));
    }

    public function create()
    {
        $expenseTypes = ExpenseType::orderBy('name')->get();
        return view('admin.pages.expenses.create', compact('expenseTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'expense_type_id' => 'required|exists:expense_types,id',
            'date'            => 'required|date',
            'expense_name'    => 'required|string|max:255',
            'amount'          => 'required|numeric|min:0',
            'notes'           => 'nullable|string',
        ]);

        Expense::create([
            'admin_id'         => Auth::id(),
            'expense_type_id' => $request->expense_type_id,
            'date'            => $request->date,
            'expense_name'    => $request->expense_name,
            'amount'          => $request->amount,
            'notes'           => $request->notes,
        ]);

        return redirect()
            ->route('admin.expenses.index')
            ->with('success', 'Expense created successfully.');
    }

    public function edit(Expense $expense)
    {
        $expenseTypes = ExpenseType::orderBy('name')->get();
        return view('admin.pages.expenses.edit', compact('expense', 'expenseTypes'));
    }

    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'expense_type_id' => 'required|exists:expense_types,id',
            'date'            => 'required|date',
            'expense_name'    => 'required|string|max:255',
            'amount'          => 'required|numeric|min:0',
            'notes'           => 'nullable|string',
        ]);

        $expense->update($request->only([
            'expense_type_id',
            'date',
            'expense_name',
            'amount',
            'notes',
        ]));

        return redirect()
            ->route('admin.expenses.index')
            ->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()
            ->route('admin.expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }
}
