<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display user profile with tabs (Dibuat, Disimpan, Board) - PUBLIC VIEW
     */
    public function show(User $user, Request $request): View
    {
        $tab = $request->input('tab', 'created');

        $data = ['user' => $user, 'tab' => $tab];

        switch ($tab) {
            case 'saved':
                // Photos pinned by this user
                $data['photos'] = $user->pins()
                    ->with(['photo.user', 'photo.tags'])
                    ->latest()
                    ->paginate(30)
                    ->through(fn ($pin) => $pin->photo);
                break;

            case 'boards':
                // User's boards
                $boardsQuery = $user->boards()->withCount('photos')->latest();

                // Hide private boards from non-owners
                if (!Auth::check() || Auth::id() !== $user->id) {
                    $boardsQuery->public();
                }

                $data['boards'] = $boardsQuery->get();
                break;

            case 'created':
            default:
                // Photos uploaded by this user
                $data['photos'] = $user->photos()
                    ->with('tags')
                    ->latest()
                    ->paginate(30);
                break;
        }

        // Stats
        $data['photosCount'] = $user->photos()->count();
        $data['boardsCount'] = $user->boards()->public()->count();
        $data['followersCount'] = $user->followers()->count();
        $data['followingCount'] = $user->following()->count();

        return view('profile.show', $data);
    }

    /**
     * Display the user's profile form (SETTINGS VIEW).
     */
    public function edit(Request $request): View
    {
        // Breeze default profile edit view
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:30|alpha_dash|unique:users,username,' . $user->id,
            'email' => 'required|string|lowercase|email|max:255|unique:users,email,' . $user->id,
            'bio' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'cover_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $user->fill([
            'name' => $validated['name'],
            'username' => strtolower($validated['username']),
            'email' => $validated['email'],
            'bio' => $validated['bio'] ?? null,
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Handle Avatar Upload
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('avatars');
        }

        // Handle Cover Photo Upload
        if ($request->hasFile('cover_photo')) {
            if ($user->cover_photo) {
                Storage::delete($user->cover_photo);
            }
            $user->cover_photo = $request->file('cover_photo')->store('covers');
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated')->with('success', 'Profil berhasil diperbarui!');
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

        // Delete associated files
        if ($user->avatar) Storage::delete($user->avatar);
        if ($user->cover_photo) Storage::delete($user->cover_photo);

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
