<?php

namespace App\Http\Controllers;

use App\Models\Cashflow;
use App\Models\Project;
use Illuminate\Http\Request;

class CashflowController extends Controller
{
    public function index(Request $request)
    {
        $projectId = $request->input('project_id');
        $type = $request->input('type');
        $category = $request->input('category');

        $query = Cashflow::with('project');

        if ($projectId) {
            $query->where('project_id', $projectId);
        }

        if ($type && in_array($type, ['income', 'expense'])) {
            $query->where('type', $type);
        }

        if ($category && in_array($category, ['material', 'labor', 'operational', 'others'])) {
            $query->where('category', $category);
        }

        $cashflows = $query->latest('transaction_date')->paginate(15)->withQueryString();
        $projects = Project::orderBy('name')->get();

        // Totals
        $allCashflows = Cashflow::when($projectId, fn($q) => $q->where('project_id', $projectId))->get();
        $totalIncome = (float) $allCashflows->where('type', 'income')->sum('amount');
        $totalExpense = (float) $allCashflows->where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        return view('cashflows.index', compact(
            'cashflows',
            'projects',
            'projectId',
            'type',
            'category',
            'totalIncome',
            'totalExpense',
            'balance'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'type' => 'required|in:income,expense',
            'category' => 'required|in:material,labor,operational,others',
            'amount' => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        Cashflow::create($validated);

        return back()->with('success', 'Transaksi keuangan berhasil dicatat.');
    }

    public function destroy($id)
    {
        $cashflow = Cashflow::findOrFail($id);
        $cashflow->delete();

        return back()->with('success', 'Transaksi keuangan berhasil dihapus.');
    }
}
