<?php


namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function index()
    {
        $banks = Bank::all();
        return view('backend.bank.index', compact('banks'));
    }

    public function create()
    {
        return view('backend.bank.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        Bank::create($request->all());
        return redirect()->route('banks.index')->with('success', 'Bank created successfully!');
    }

    public function edit(Bank $bank)
    {
        return view('backend.bank.edit', compact('bank'));
    }

    public function update(Request $request, Bank $bank)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $bank->update($request->all());
        return redirect()->route('banks.index')->with('success', 'Bank updated successfully!');
    }

    public function destroy(Bank $bank)
    {
        $bank->delete();
        return redirect()->route('banks.index')->with('success', 'Bank deleted successfully!');
    }
}
