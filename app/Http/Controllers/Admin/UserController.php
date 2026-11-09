<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'role' => ['nullable', Rule::in(['admin', 'staff'])],
        ]);
        $search = trim($filters['search'] ?? '');

        $users = User::query()
            ->when($search !== '', fn ($query) => $query->where(
                fn ($query) => $query
                    ->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%')
            ))
            ->when(
                $filters['role'] ?? null,
                fn ($query, string $role) => $query->where('role', $role)
            )
            ->orderBy('name')
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'search' => $search,
            'selectedRole' => $filters['role'] ?? '',
        ]);
    }

    public function show(User $user): View
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', Rule::in(['admin', 'staff'])],
        ]);
        $actor = $request->user();

        if ($actor->is($user)) {
            throw ValidationException::withMessages([
                'role' => 'You cannot change your own administrator role.',
            ]);
        }

        DB::transaction(function () use ($user, $validated): void {
            $account = User::query()
                ->lockForUpdate()
                ->findOrFail($user->id);
            $account->role = $validated['role'];
            $account->save();

            if (
                $validated['role'] !== 'admin'
                && User::query()
                    ->where('role', 'admin')
                    ->lockForUpdate()
                    ->count() === 0
            ) {
                throw ValidationException::withMessages([
                    'role' => 'The last administrator account cannot be demoted.',
                ]);
            }
        });

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'User role updated successfully.');
    }
}
