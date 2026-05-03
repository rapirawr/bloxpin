@extends('layouts.admin')

@section('content')
<div class="mb-10">
    <h2 class="text-4xl font-black tracking-tighter mb-2">USER MANAGEMENT</h2>
    <p class="text-gray-500 text-sm font-medium uppercase tracking-widest">Access Control & Moderation</p>
</div>

<div class="bg-card rounded-2xl border border-border overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-white/[0.02] border-b border-border text-[10px] font-black text-gray-500 uppercase tracking-[0.2em]">
                <th class="p-6">Identity</th>
                <th class="p-6">Status</th>
                <th class="p-6 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-border">
            @foreach($users as $user)
            <tr class="hover:bg-white/[0.01] transition-colors group">
                <td class="p-6">
                    <div class="flex items-center gap-4">
                        <img src="{{ $user->avatar_url }}" class="w-10 h-10 rounded-full border border-border  group-hover:transition-all">
                        <div>
                            <div class="font-bold text-sm flex items-center gap-2">
                                {{ $user->name }}
                                @if($user->is_verified)
                                    <x-verified-badge size="w-3.5 h-3.5" checkSize="w-2 h-2" />
                                @endif
                            </div>
                            <div class="text-[10px] text-gray-500 font-medium tracking-tight">@ {{ $user->username }}</div>
                        </div>
                    </div>
                </td>
                <td class="p-6">
                    <div class="flex gap-2">
                        @if($user->is_admin)
                            <span class="px-2 py-0.5 bg-white text-black text-[9px] font-black uppercase tracking-tighter rounded">Developer</span>
                        @endif
                        @if($user->is_shadowbanned)
                            <span class="px-2 py-0.5 border border-white/20 text-gray-500 text-[9px] font-black uppercase tracking-tighter rounded">Shadow</span>
                        @endif
                    </div>
                </td>
                <td class="p-6 text-right space-x-2">
                    <!-- Actions with SVG Icons -->
                    <div class="inline-flex items-center gap-1">
                        <!-- Impersonate -->
                        <form action="{{ route('admin.users.impersonate', $user) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="p-2 hover:bg-white hover:text-black rounded-lg transition-all text-gray-500" title="Login as User">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </button>
                        </form>

                        <!-- Toggle Verified -->
                        <form id="toggle-verified-{{ $user->id }}" action="{{ route('admin.users.toggle-verified', $user) }}" method="POST" class="inline" @submit.prevent="window.appConfirm('Verifikasi User', 'Ubah status verifikasi untuk {{ $user->name }}?', () => document.getElementById('toggle-verified-{{ $user->id }}').submit(), 'Lanjutkan')">
                            @csrf
                            <button type="submit" class="p-2 hover:bg-white hover:text-black rounded-lg transition-all {{ $user->is_verified ? 'text-white bg-white/10' : 'text-gray-500' }}" title="Verify User">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </button>
                        </form>

                        <!-- Toggle Shadowban -->
                        <form id="toggle-shadowban-{{ $user->id }}" action="{{ route('admin.users.toggle-shadowban', $user) }}" method="POST" class="inline" @submit.prevent="window.appConfirm('Shadowban User', 'Ubah status shadowban untuk {{ $user->name }}?', () => document.getElementById('toggle-shadowban-{{ $user->id }}').submit(), 'Lanjutkan')">
                            @csrf
                            <button type="submit" class="p-2 hover:bg-white hover:text-black rounded-lg transition-all {{ $user->is_shadowbanned ? 'text-white bg-white/10' : 'text-gray-500' }}" title="Shadowban">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 14.12l4.242-4.242M3 3l18 18"></path></svg>
                            </button>
                        </form>

                        <!-- Toggle Admin -->
                        <form id="toggle-admin-{{ $user->id }}" action="{{ route('admin.users.toggle-admin', $user) }}" method="POST" class="inline" @submit.prevent="window.appConfirm('Toggle Admin', 'Ubah hak akses admin untuk {{ $user->name }}?', () => document.getElementById('toggle-admin-{{ $user->id }}').submit(), 'Lanjutkan')">
                            @csrf
                            <button type="submit" class="p-2 hover:bg-white hover:text-black rounded-lg transition-all {{ $user->is_admin ? 'text-white bg-white/10' : 'text-gray-500' }}" title="Make Admin">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                            </button>
                        </form>

                        <!-- Reset Password -->
                        <button onclick="adminResetPassword({{ $user->id }}, '{{ $user->name }}')" class="p-2 hover:bg-white hover:text-black rounded-lg transition-all text-gray-500" title="Reset Password">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                        </button>

                        <!-- Delete -->
                        <form id="reset-form-{{ $user->id }}" action="{{ route('admin.users.reset-password', $user) }}" method="POST" class="hidden">
                            @csrf
                            <input type="hidden" name="password" id="reset-input-{{ $user->id }}">
                        </form>

                        <form id="delete-user-{{ $user->id }}" action="{{ route('admin.users.delete', $user) }}" method="POST" class="inline" @submit.prevent="window.appConfirm('Hapus User', 'Yakin ingin menghapus user ini?', () => document.getElementById('delete-user-{{ $user->id }}').submit(), 'Hapus')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 hover:bg-red-500 hover:text-white rounded-lg transition-all text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-8">
    {{ $users->links() }}
</div>

<script>
function adminResetPassword(userId, userName) {
    window.appPrompt(
        'Reset Password', 
        `Masukkan password baru untuk ${userName}:`, 
        (newPassword) => {
            if (newPassword && newPassword.length >= 8) {
                document.getElementById(`reset-input-${userId}`).value = newPassword;
                document.getElementById(`reset-form-${userId}`).submit();
            } else if (newPassword) {
                window.showToast("Password minimal 8 karakter!", "error");
            }
        }, 
        '', 
        'Password Baru (min 8 karakter)'
    );
}
</script>
@endsection
