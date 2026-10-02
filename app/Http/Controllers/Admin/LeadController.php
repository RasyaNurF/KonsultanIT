<?php

namespace App\Http\Controllers\Admin;

use App\Enums\LeadStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LeadUpdateRequest;
use App\Models\ProjectInquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LeadController extends Controller
{
    public function index(Request $request): View
    {
        $leads = ProjectInquiry::query()
            ->search($request->string('q')->toString())
            ->withStatus($request->string('status')->toString())
            ->when($request->filled('project_type'), fn ($query) => $query->where('project_type', $request->string('project_type')->toString()))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $counts = ProjectInquiry::query()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.leads.index', [
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Leads'],
            ],
            'searchPlaceholder' => 'Cari nama, email, atau perusahaan…',
            'searchAction' => route('admin.leads.index'),
            'leads' => $leads,
            'statuses' => LeadStatus::cases(),
            'projectTypes' => ProjectInquiry::PROJECT_TYPES,
            'counts' => $counts,
        ]);
    }

    public function show(ProjectInquiry $lead): View
    {
        return view('admin.leads.show', [
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Leads', 'url' => route('admin.leads.index')],
                ['label' => $lead->name],
            ],
            'lead' => $lead,
            'statuses' => LeadStatus::cases(),
        ]);
    }

    public function update(LeadUpdateRequest $request, ProjectInquiry $lead): RedirectResponse
    {
        $wasNew = $lead->status === LeadStatus::Baru;

        $lead->update([
            ...$request->validated(),
            'handled_by' => auth()->id(),
            'handled_at' => $wasNew ? now() : $lead->handled_at,
        ]);

        return redirect()->route('admin.leads.show', $lead)->with('success', 'Lead berhasil diperbarui.');
    }

    public function destroy(ProjectInquiry $lead): RedirectResponse
    {
        $lead->delete();

        return redirect()->route('admin.leads.index')->with('success', 'Lead berhasil dihapus.');
    }

    public function export(Request $request): StreamedResponse
    {
        $leads = ProjectInquiry::query()
            ->search($request->string('q')->toString())
            ->withStatus($request->string('status')->toString())
            ->when($request->filled('project_type'), fn ($query) => $query->where('project_type', $request->string('project_type')->toString()))
            ->latest()
            ->get();

        $filename = 'leads-kit-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($leads): void {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Nama', 'Email', 'Perusahaan', 'Telepon', 'Jenis Proyek', 'Budget', 'Detail', 'Status', 'Tanggal']);

            foreach ($leads as $lead) {
                fputcsv($handle, [
                    $lead->name,
                    $lead->email,
                    $lead->company,
                    $lead->phone,
                    $lead->project_type,
                    $lead->budget_range,
                    $lead->project_detail,
                    $lead->status->label(),
                    $lead->created_at?->format('Y-m-d H:i'),
                ]);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
