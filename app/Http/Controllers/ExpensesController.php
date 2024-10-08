<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ExpensesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expenses = Expense::where('user_id', Auth::user()->id)->with([
            'category'
        ])->latest()->get();

        $categories = Category::get();

        return Inertia::render('User/ExpensesManagement/Index', [
            'expenses' => $expenses,
            'categories' => $categories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required',
            'amount' => 'required',
            'entryDate' => 'required'
        ]);


        Expense::create([
            'category_id' => $request->category,
            'amount' => $request->amount,
            'entry_date' => $request->entryDate,
            'user_id' => Auth::user()->id
        ]);


        return back()->with(['message_success' => 'Expenses Added Success']);
    
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $expense = Expense::find($id);

        $expense->update([
            'category_id' => $request->category,
            'amount' => $request->amount,
            'entry_date' => $request->entryDate
        ]);


        return back()->with([
            'message_success' => 'Expenses Updated!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $expense = Expense::find($id);

        $expense->delete();


        return back()->with([
            'message_success' => 'Expenses Deleted !'
        ]);
    }
}
