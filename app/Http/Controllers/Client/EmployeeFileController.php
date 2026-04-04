<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EmployeeFileController extends Controller
{
    public function show(int $employee, string $type): StreamedResponse
    {
        $user = auth()->user();
        $client = $user->client;

        if (!$client) {
            abort(403, __('messages.unauthorized'));
        }

        $emp = Employee::where('client_id', $client->id)->findOrFail($employee);

        $path = match ($type) {
            'national_id' => $emp->national_id_image,
            'contract' => $emp->contract_image,
            default => abort(404),
        };

        if (!$path || !Storage::disk('private')->exists($path)) {
            abort(404);
        }

        return Storage::disk('private')->download($path);
    }
}
