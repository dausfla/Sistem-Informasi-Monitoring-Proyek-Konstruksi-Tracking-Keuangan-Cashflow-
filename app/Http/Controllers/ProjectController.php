<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectUnit;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->input('category');
        $status = $request->input('status');
        $search = $request->input('search');

        $query = Project::withCount(['projectUnits', 'cashflows', 'projectDocuments']);

        if ($category && in_array($category, ['rbk_land', 'non_rbk_land'])) {
            $query->where('category', $category);
        }

        if ($status && in_array($status, ['planning', 'on_progress', 'completed', 'on_hold'])) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where('name', 'ILIKE', "%{$search}%");
        }

        $projects = $query->latest()->paginate(10)->withQueryString();

        return view('projects.index', compact('projects', 'category', 'status', 'search'));
    }

    public function show($id)
    {
        $project = Project::with([
            'projectUnits',
            'cashflows' => function ($q) {
                $q->latest('transaction_date');
            },
            'projectDocuments.uploader'
        ])->findOrFail($id);

        $expensesByCategory = [
            'material' => (float) $project->cashflows->where('type', 'expense')->where('category', 'material')->sum('amount'),
            'labor' => (float) $project->cashflows->where('type', 'expense')->where('category', 'labor')->sum('amount'),
            'operational' => (float) $project->cashflows->where('type', 'expense')->where('category', 'operational')->sum('amount'),
            'others' => (float) $project->cashflows->where('type', 'expense')->where('category', 'others')->sum('amount'),
        ];

        return view('projects.show', compact('project', 'expensesByCategory'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:rbk_land,non_rbk_land',
            'status' => 'required|in:planning,on_progress,completed,on_hold',
            'planned_progress' => 'required|numeric|min:0|max:100',
            'actual_progress' => 'required|numeric|min:0|max:100',
            'total_contract_value' => 'required|numeric|min:0',
        ]);

        $project = Project::create($validated);

        return redirect()->route('projects.show', $project->id)
            ->with('success', 'Proyek berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:rbk_land,non_rbk_land',
            'status' => 'required|in:planning,on_progress,completed,on_hold',
            'planned_progress' => 'required|numeric|min:0|max:100',
            'actual_progress' => 'required|numeric|min:0|max:100',
            'total_contract_value' => 'required|numeric|min:0',
        ]);

        $project->update($validated);

        return redirect()->route('projects.show', $project->id)
            ->with('success', 'Data & Progres Proyek berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Proyek berhasil dihapus.');
    }

    public function storeUnit(Request $request, $projectId)
    {
        $project = Project::findOrFail($projectId);

        $validated = $request->validate([
            'block_name' => 'required|string|max:100',
            'unit_number' => 'required|string|max:100',
        ]);

        $project->projectUnits()->create($validated);

        return back()->with('success', 'Unit kavling/blok berhasil ditambahkan.');
    }
}
