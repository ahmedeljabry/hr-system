<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AdminUserService
{
    /**
     * Update basic user information (name and email only).
     *
     * @param User $user
     * @param array $data
     * @return void
     */
    public function updateBasicInfo(User $user, array $data): void
    {
        $old = ['name' => $user->name, 'email' => $user->email];
        $user->update(['name' => $data['name'], 'email' => $data['email']]);
        Log::channel('daily')->info('ADMIN_ACTION', [
            'admin_id' => Auth::id(),
            'action' => 'user_edit',
            'target' => 'users',
            'record_id' => $user->id,
            'old' => $old,
            'new' => ['name' => $data['name'], 'email' => $data['email']],
        ]);
    }
}