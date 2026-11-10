<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function updatePhoto(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'profile_photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);
        $user = $request->user();
        $previousPhoto = $user->profile_photo_path;
        $photoPath = $validated['profile_photo']->store('profile-photos', 'public');

        if ($photoPath === false) {
            abort(500, 'The profile photo could not be saved.');
        }

        $user->profile_photo_path = $photoPath;
        $user->save();

        if ($previousPhoto) {
            Storage::disk('public')->delete($previousPhoto);
        }

        return Redirect::route('profile.edit')->with('status', 'profile-photo-updated');
    }

    public function deletePhoto(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
            $user->profile_photo_path = null;
            $user->save();
        }

        return Redirect::route('profile.edit')->with('status', 'profile-photo-deleted');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        $photoPath = DB::transaction(function () use ($user): ?string {
            $account = User::query()
                ->lockForUpdate()
                ->findOrFail($user->id);

            if ($account->role === 'admin') {
                $administrators = User::query()
                    ->where('role', 'admin')
                    ->lockForUpdate()
                    ->pluck('id');

                if ($administrators->count() <= 1) {
                    throw ValidationException::withMessages([
                        'account' => 'The last administrator account cannot be deleted.',
                    ]);
                }
            }

            $account->tokens()->delete();
            DB::table('sessions')->where('user_id', $account->id)->delete();
            DB::table('password_reset_tokens')->where('email', $account->email)->delete();
            $photoPath = $account->profile_photo_path;
            $account->delete();

            return $photoPath;
        });

        Auth::logout();
        if ($photoPath) {
            Storage::disk('public')->delete($photoPath);
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
