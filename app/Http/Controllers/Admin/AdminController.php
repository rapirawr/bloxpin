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
        try {
            $user->is_verified = !$user->is_verified;
            $user->save();
            return back()->with('success', 'Status verifikasi user diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal update: Sepertinya Anda perlu menjalankan "php artisan migrate" di terminal.');
        }
    }

    /**
     * Toggle Shadowban
     */
    public function toggleShadowban(User $user)
    {
        try {
            $user->is_shadowbanned = !$user->is_shadowbanned;
            $user->save();
            return back()->with('success', 'Status shadowban user diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal update: Sepertinya Anda perlu menjalankan "php artisan migrate" di terminal.');
        }
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
     * Show Announcement Page
     */
    public function announcement()
    {
        $current = \App\Models\Announcement::active()->latest()->first();
        $history = \App\Models\Announcement::latest()->paginate(10);
        return view('admin.announcement', compact('current', 'history'));
    }

    /**
     * Send Global Announcement
     */
    public function sendAnnounce(Request $request)
    {
        if ($request->action === 'clear') {
            \App\Models\Announcement::where('is_active', true)->update(['is_active' => false]);
            return back()->with('success', 'Semua pengumuman aktif telah dinonaktifkan.');
        }

        $request->validate([
            'message' => 'required|string|max:500',
            'duration' => 'required|string'
        ]);

        // Deactivate previous ones
        \App\Models\Announcement::where('is_active', true)->update(['is_active' => false]);

        $ends_at = null;
        if ($request->duration !== 'permanent') {
            $ends_at = match($request->duration) {
                '1h' => now()->addHour(),
                '1d' => now()->addDay(),
                '1w' => now()->addWeek(),
                default => null
            };
        }

        \App\Models\Announcement::create([
            'message' => $request->message,
            'is_active' => true,
            'ends_at' => $ends_at
        ]);

        return back()->with('success', 'Pengumuman baru berhasil disiarkan!');
    }

    /**
     * Admin Force Reset Password
     */
    public function resetPassword(Request $request, \App\Models\User $user)
    {
        $request->validate([
            'password' => 'required|string|min:8',
        ]);

        $user->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        return back()->with('success', 'Password user ' . $user->name . ' berhasil direset!');
    }

    /**
     * Delete Announcement from History
     */
    public function deleteAnnounce(\App\Models\Announcement $announcement)
    {
        $announcement->delete();
        return back()->with('success', 'History pengumuman berhasil dihapus.');
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
