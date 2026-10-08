<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Cashflow;
use App\Models\ProjectDocument;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $selectedCategory = $request->input('category');
        $selectedProjectId = $request->input('project_id');

        $query = Project::with(['cashflows', 'projectUnits', 'projectDocuments']);

        if ($selectedCategory && in_array($selectedCategory, ['rbk_land', 'non_rbk_land'])) {
            $query->where('category', $selectedCategory);
        }

        if ($selectedProjectId) {
            $query->where('id', $selectedProjectId);
        }

        $projects = $query->get();
        $allProjectsList = Project::orderBy('name')->get();

        // Overview Metrics
        $totalProjects = $projects->count();
        $totalContractValue = $projects->sum('total_contract_value');
        $avgPlanned = $totalProjects > 0 ? $projects->avg('planned_progress') : 0;
        $avgActual = $totalProjects > 0 ? $projects->avg('actual_progress') : 0;
        $avgVariance = round($avgActual - $avgPlanned, 2);

        // Financials Aggregation
        $projectIds = $projects->pluck('id');
        $cashflows = Cashflow::whereIn('project_id', $projectIds)->get();

        $totalIncome = (float) $cashflows->where('type', 'income')->sum('amount');
        $totalExpense = (float) $cashflows->where('type', 'expense')->sum('amount');
        $netBalance = $totalIncome - $totalExpense;

        // Cashflow Expense Breakdown by Category
        $expensesByCategory = [
            'material' => (float) $cashflows->where('type', 'expense')->where('category', 'material')->sum('amount'),
            'labor' => (float) $cashflows->where('type', 'expense')->where('category', 'labor')->sum('amount'),
            'operational' => (float) $cashflows->where('type', 'expense')->where('category', 'operational')->sum('amount'),
            'others' => (float) $cashflows->where('type', 'expense')->where('category', 'others')->sum('amount'),
        ];

        // Kurva S & Progress Data
        $chartProjectLabels = $projects->pluck('name')->toArray();
        $chartPlannedData = $projects->pluck('planned_progress')->toArray();
        $chartActualData = $projects->pluck('actual_progress')->toArray();

        // Sample Material Weight Completion Data for Kurva S Table
        $materialWeights = [
            ['stage' => '1. Pekerjaan Persiapan & Pondasi', 'weight' => 15.0, 'planned' => 15.0, 'actual' => 15.0, 'status' => 'Selesai'],
            ['stage' => '2. Struktur Beton & Pembesian', 'weight' => 35.0, 'planned' => 35.0, 'actual' => 35.0, 'status' => 'Selesai'],
            ['stage' => '3. Dinding, Plesteran & Pasangan Bata', 'weight' => 20.0, 'planned' => 15.0, 'actual' => 18.5, 'status' => 'Progres'],
            ['stage' => '4. Rangka Atap & Penutup Atap', 'weight' => 15.0, 'planned' => 5.0, 'actual' => 5.0, 'status' => 'Progres'],
            ['stage' => '5. MEP (Mekanikal, Elektrikal, Plambing)', 'weight' => 10.0, 'planned' => 0.0, 'actual' => 0.0, 'status' => 'Belum'],
            ['stage' => '6. Finishing & Pengecatan', 'weight' => 5.0, 'planned' => 0.0, 'actual' => 0.0, 'status' => 'Belum'],
        ];

        // Recent Documents
        $recentDocuments = ProjectDocument::whereIn('project_id', $projectIds)
            ->with(['project', 'uploader'])
            ->latest()
            ->take(6)
            ->get();

        return view('dashboard.index', compact(
            'projects',
            'allProjectsList',
            'selectedCategory',
            'selectedProjectId',
            'totalProjects',
            'totalContractValue',
            'avgPlanned',
            'avgActual',
            'avgVariance',
            'totalIncome',
            'totalExpense',
            'netBalance',
            'expensesByCategory',
            'chartProjectLabels',
            'chartPlannedData',
            'chartActualData',
            'materialWeights',
            'recentDocuments'
        ));
    }
}
