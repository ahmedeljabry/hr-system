<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AdminUserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(private AdminUserService $adminUserService) {}

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        if ($user->employee) {
            return redirect()->route('admin.employees.edit', $user->employee->id);
        }

        if ($user->client) {
            return redirect()->route('admin.clients.show', $user->client_id);
        }

        return redirect()->route('admin.dashboard');
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
        ]);

        $this->adminUserService->updateProfile($user, $validated);

        return back()->with('success', __('messages.user_updated') ?? 'User updated successfully.');
    }
}