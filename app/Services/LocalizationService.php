<?php

namespace App\Services;

use App\Models\LocalizationDecision;
use App\Models\LocalizationJob;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class LocalizationService
{
    public function list()
    {
        return LocalizationDecision::withCount('jobs')->orderBy('created_at', 'desc')->paginate(15);
    }

    public function find(int $id)
    {
        return LocalizationDecision::with('jobs')->findOrFail($id);
    }

    public function create(array $data, array $uploadedFiles = []): LocalizationDecision
    {
        return DB::transaction(function () use ($data, $uploadedFiles) {
            $filePaths = [];
            foreach ($uploadedFiles as $file) {
                $filePaths[] = $file->store('localization/decisions', 'public');
            }

            $decision = LocalizationDecision::create([
                'saudi_percentage' => $data['saudi_percentage'],
                'files' => $filePaths,
            ]);

            if (isset($data['jobs']) && is_array($data['jobs'])) {
                foreach ($data['jobs'] as $job) {
                    if (!empty($job['occupation_code']) && !empty($job['job_title_ar'])) {
                        $decision->jobs()->create([
                            'occupation_code' => $job['occupation_code'],
                            'job_title_ar' => $job['job_title_ar'],
                            'job_title_en' => $job['job_title_en'] ?? null,
                        ]);
                    }
                }
            }

            return $decision;
        });
    }

    public function update(int $id, array $data, array $uploadedFiles = []): LocalizationDecision
    {
        $decision = LocalizationDecision::findOrFail($id);

        return DB::transaction(function () use ($decision, $data, $uploadedFiles) {
            $filePaths = $decision->files ?? [];
            foreach ($uploadedFiles as $file) {
                $filePaths[] = $file->store('localization/decisions', 'public');
            }

            $decision->update([
                'saudi_percentage' => $data['saudi_percentage'],
                'files' => $filePaths,
            ]);

            // Sync jobs: simple approach - delete and recreate for dynamic fields
            $decision->jobs()->delete();
            if (isset($data['jobs']) && is_array($data['jobs'])) {
                foreach ($data['jobs'] as $job) {
                    if (!empty($job['occupation_code']) && !empty($job['job_title_ar'])) {
                        $decision->jobs()->create([
                            'occupation_code' => $job['occupation_code'],
                            'job_title_ar' => $job['job_title_ar'],
                            'job_title_en' => $job['job_title_en'] ?? null,
                        ]);
                    }
                }
            }

            return $decision;
        });
    }

    public function delete(int $id): bool
    {
        $decision = LocalizationDecision::findOrFail($id);
        
        return DB::transaction(function () use ($decision) {
            if ($decision->files) {
                foreach ($decision->files as $file) {
                    Storage::disk('public')->delete($file);
                }
            }
            return (bool) $decision->delete();
        });
    }
}
