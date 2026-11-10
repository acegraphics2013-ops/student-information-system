<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
            'adminCount' => User::query()->where('role', 'admin')->count(),
        ]);
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::in(['admin', 'staff'])],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = DB::transaction(function () use ($validated): User {
            $user = new User;
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->role = $validated['role'];
            $user->password = $validated['password'];
            $user->save();

            return $user;
        });

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'User account created successfully.');
    }

    public function show(User $user): View
    {
        return view('admin.users.show', [
            'user' => $user,
            'adminCount' => User::query()->where('role', 'admin')->count(),
        ]);
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => [
                'sometimes',
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'role' => ['required', Rule::in(['admin', 'staff'])],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);
        $actor = $request->user();

        if ($actor->is($user) && $validated['role'] !== $user->role) {
            throw ValidationException::withMessages([
                'role' => 'You cannot change your own administrator role.',
            ]);
        }

        DB::transaction(function () use ($user, $validated): void {
            $account = User::query()
                ->lockForUpdate()
                ->findOrFail($user->id);
            if (array_key_exists('name', $validated)) {
                $account->name = $validated['name'];
            }
            if (array_key_exists('email', $validated)) {
                $account->email = $validated['email'];
            }
            $account->role = $validated['role'];
            if (! empty($validated['password'])) {
                $account->password = $validated['password'];
            }
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
            ->with('success', 'User account updated successfully.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($request->user()->is($user)) {
            throw ValidationException::withMessages([
                'account' => 'You cannot delete your own account.',
            ]);
        }

        $photoPath = $user->profile_photo_path;
        DB::transaction(function () use ($user): void {
            $account = User::query()
                ->lockForUpdate()
                ->findOrFail($user->id);

            if (
                $account->role === 'admin'
                && User::query()
                    ->where('role', 'admin')
                    ->lockForUpdate()
                    ->count() <= 1
            ) {
                throw ValidationException::withMessages([
                    'account' => 'The last administrator account cannot be deleted.',
                ]);
            }

            $account->tokens()->delete();
            DB::table('sessions')->where('user_id', $account->id)->delete();
            DB::table('password_reset_tokens')->where('email', $account->email)->delete();
            $account->delete();
        });
        if ($photoPath) {
            Storage::disk('public')->delete($photoPath);
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User account deleted successfully.');
    }
}
