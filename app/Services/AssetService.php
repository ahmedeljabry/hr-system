<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\Employee;
use Illuminate\Support\Collection;

class AssetService
{
    /**
     * Assign asset to employee.
     */
    public function assignAsset(Asset $asset, Employee $employee): bool
    {
        return $asset->update(['employee_id' => $employee->id, 'assigned_date' => now()]);
    }

    /**
     * Return asset from employee.
     */
    public function returnAsset(Asset $asset): bool
    {
        return $asset->update(['employee_id' => null, 'returned_date' => now()]);
    }

    /**
     * Get all assets for an employee.
     */
    public function getAssetsForEmployee(Employee $employee): Collection
    {
        return Asset::where('employee_id', $employee->id)->get();
    }
}
