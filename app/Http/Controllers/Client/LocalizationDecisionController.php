<?php

namespace App\Http\Controllers\Client;

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
        return view('client.localization.index', compact('decisions'));
    }

    public function show($id)
    {
        $decision = $this->localizationService->find($id);
        return view('client.localization.show', compact('decision'));
    }
}
