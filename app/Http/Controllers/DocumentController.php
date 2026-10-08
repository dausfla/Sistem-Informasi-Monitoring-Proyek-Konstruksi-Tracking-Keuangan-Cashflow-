<?php

namespace App\Http\Controllers;

use App\Models\ProjectDocument;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $projectId = $request->input('project_id');
        $type = $request->input('type');
        $status = $request->input('status');

        $query = ProjectDocument::with(['project', 'uploader']);

        if ($projectId) {
            $query->where('project_id', $projectId);
        }

        if ($type && in_array($type, ['rab', 'ded', 'kurva_s', 'invoice', 'kwitansi', 'sp', 'pks', 'ppjb', 'bast'])) {
            $query->where('type', $type);
        }

        if ($status && in_array($status, ['draft', 'approved', 'pending_payment', 'paid'])) {
            $query->where('status', $status);
        }

        // Restricted Access Users can only view approved, paid, or pending_payment documents
        if (auth()->user() && auth()->user()->isRestrictedAccess()) {
            $query->whereIn('status', ['approved', 'pending_payment', 'paid']);
        }

        $documents = $query->latest()->paginate(12)->withQueryString();
        $projects = Project::orderBy('name')->get();

        return view('documents.index', compact('documents', 'projects', 'projectId', 'type', 'status'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'type' => 'required|in:rab,ded,kurva_s,invoice,kwitansi,sp,pks,ppjb,bast',
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx,xlsx|max:10240', // Max 10MB
            'status' => 'required|in:draft,approved,pending_payment,paid',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $file->getClientOriginalName());
            $filePath = $file->storeAs('documents', $filename, 'public');
        } else {
            $filePath = 'documents/default_document.pdf';
        }

        ProjectDocument::create([
            'project_id' => $validated['project_id'],
            'type' => $validated['type'],
            'title' => $validated['title'],
            'file_path' => $filePath,
            'status' => $validated['status'],
            'uploaded_by' => auth()->id(),
        ]);

        return back()->with('success', 'Dokumen proyek berhasil diunggah.');
    }

    public function updateStatus(Request $request, $id)
    {
        $doc = ProjectDocument::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:draft,approved,pending_payment,paid',
        ]);

        $doc->update(['status' => $validated['status']]);

        return back()->with('success', 'Status dokumen ' . strtoupper($doc->type) . ' berhasil diperbarui ke ' . strtoupper(str_replace('_', ' ', $validated['status'])));
    }

    public function download($id)
    {
        $doc = ProjectDocument::findOrFail($id);

        if (Storage::disk('public')->exists($doc->file_path)) {
            return Storage::disk('public')->download($doc->file_path, $doc->title . '.' . pathinfo($doc->file_path, PATHINFO_EXTENSION));
        }

        // Fallback simulated file response for demo seed files
        $content = "RBK TRACKER DOCUMENT HUB\n===========================\nTitle: " . $doc->title . "\nType: " . strtoupper($doc->type) . "\nStatus: " . strtoupper($doc->status) . "\nProject: " . ($doc->project ? $doc->project->name : 'N/A') . "\nUploaded By: " . ($doc->uploader ? $doc->uploader->name : 'System') . "\nDate: " . $doc->created_at->format('Y-m-d H:i:s');
        
        return response($content)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="' . str_slug($doc->title) . '.txt"');
    }
}
