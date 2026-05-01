<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use App\Models\User;
use App\Models\Board;
use App\Models\Comment;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Admin Dashboard Overview
     */
    public function dashboard()
    {
        $stats = [
            'users_count' => User::count(),
            'photos_count' => Photo::count(),
            'boards_count' => Board::count(),
            'comments_count' => Comment::count(),
            'latest_users' => User::latest()->take(5)->get(),
            'latest_photos' => Photo::with('user')->latest()->take(5)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    /**
     * User Management
     */
    public function users()
    {
        $users = User::latest()->paginate(20);
        return view('admin.users', compact('users'));
    }

    /**
     * Toggle Admin Status
     */
    public function toggleAdmin(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus status admin diri sendiri!');
        }

        $user->is_admin = !$user->is_admin;
        $user->save();

        return back()->with('success', 'Status admin user berhasil diperbarui.');
    }

    /**
     * Toggle Verified Badge
     */
    public function toggleVerified(User $user)
    {
        $user->is_verified = !$user->is_verified;
        $user->save();

        return back()->with('success', 'Status verifikasi user diperbarui.');
    }

    /**
     * Toggle Shadowban
     */
    public function toggleShadowban(User $user)
    {
        $user->is_shadowbanned = !$user->is_shadowbanned;
        $user->save();

        return back()->with('success', 'Status shadowban user diperbarui.');
    }

    /**
     * Impersonate User (Login as)
     */
    public function impersonate(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda sudah login sebagai akun ini.');
        }

        // Store original admin ID in session if you want to switch back later
        session(['impersonator_id' => auth()->id()]);
        
        auth()->loginUsingId($user->id);
        
        return redirect()->route('home')->with('success', "Sekarang Anda login sebagai {$user->name}");
    }

    /**
     * Delete User
     */
    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus diri sendiri!');
        }

        $user->delete();
        return back()->with('success', 'User berhasil dihapus selamanya.');
    }

    /**
     * Photo Management
     */
    public function photos()
    {
        $photos = Photo::with('user')->latest()->paginate(20);
        return view('admin.photos', compact('photos'));
    }

    /**
     * Delete Photo
     */
    public function deletePhoto(Photo $photo)
    {
        $photo->delete();
        return back()->with('success', 'Foto berhasil dihapus oleh Admin.');
    }
}
