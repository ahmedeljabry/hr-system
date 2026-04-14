<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AdminUserService
{
    /**
     * Update user account information.
     */
    public function updateProfile(User $user, array $data): void
    {
        $old = ['name' => $user->name, 'email' => $user->email];
        $updateData = ['name' => $data['name'], 'email' => $data['email']];
        
        if (!empty($data['password'])) {
            $updateData['password'] = \Illuminate\Support\Facades\Hash::make($data['password']);
        }

        $user->update($updateData);

        Log::channel('daily')->info('ADMIN_ACTION', [
            'admin_id' => Auth::id(),
            'action' => 'user_edit',
            'target' => 'users',
            'record_id' => $user->id,
            'old' => $old,
            'updated_password' => !empty($data['password']),
        ]);
    }
}