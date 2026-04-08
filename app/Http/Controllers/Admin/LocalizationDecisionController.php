<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\LocalizationService;
use Illuminate\Http\Request;

class LocalizationDecisionController extends Controller
{
    public function __construct(private LocalizationService $localizationService)
    {
    }

    public function index(Request $request)
    {
        $decisions = $this->localizationService->list();
        if ($request->wantsJson()) {
            return response()->json($decisions);
        }
        return view('admin.localization.index', compact('decisions'));
    }

    public function create()
    {
        return view('admin.localization.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'saudi_percentage' => 'required|numeric|min:0|max:100',
            'jobs' => 'required|array|min:1',
            'jobs.*.occupation_code' => 'required|string',
            'jobs.*.job_title_ar' => 'required|string',
            'files' => 'nullable|array',
            'files.*' => 'file|mimes:jpeg,png,pdf,doc,docx|max:10240',
        ]);

        $this->localizationService->create($request->all(), $request->file('files', []));

        return redirect()->route('admin.localization.index')->with('success', __('Localization decision created successfully.'));
    }

    public function edit($id)
    {
        $decision = $this->localizationService->find($id);
        return view('admin.localization.edit', compact('decision'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'saudi_percentage' => 'required|numeric|min:0|max:100',
            'jobs' => 'required|array|min:1',
            'jobs.*.occupation_code' => 'required|string',
            'jobs.*.job_title_ar' => 'required|string',
            'files' => 'nullable|array',
            'files.*' => 'file|mimes:jpeg,png,pdf,doc,docx|max:10240',
        ]);

        $this->localizationService->update($id, $request->all(), $request->file('files', []));

        return redirect()->route('admin.localization.index')->with('success', __('Localization decision updated successfully.'));
    }

    public function destroy($id)
    {
        $this->localizationService->delete($id);
        return redirect()->route('admin.localization.index')->with('success', __('Localization decision deleted successfully.'));
    }
}
