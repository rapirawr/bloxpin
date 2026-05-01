@extends('layouts.admin')

@section('content')
<div class="mb-10 flex justify-between items-center">
    <div>
        <h2 class="text-3xl font-bold mb-2">User Management</h2>
        <p class="text-slate-400">Daftar semua penghuni Bloxpin.</p>
    </div>
</div>

<div class="glass rounded-3xl overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-slate-800/50 border-b border-slate-700">
            <tr>
                <th class="p-4 pl-8">User</th>
                <th class="p-4">Username</th>
                <th class="p-4">Status</th>
                <th class="p-4 text-right pr-8">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-800">
            @foreach($users as $user)
            <tr class="hover:bg-slate-800/30 transition-colors">
                <td class="p-4 pl-8">
                    <div class="flex items-center gap-3">
                        <img src="{{ $user->avatar_url }}" class="w-10 h-10 rounded-full object-cover">
                        <div>
                            <div class="font-semibold">{{ $user->name }}</div>
                            <div class="text-xs text-slate-500">{{ $user->email }}</div>
                        </div>
                    </div>
                </td>
                <td class="p-4 text-slate-400">@ {{ $user->username }}</td>
                <td class="p-4">
                    @if($user->is_admin)
                        <span class="px-3 py-1 bg-rose-500/20 text-rose-500 rounded-full text-xs font-bold border border-rose-500/30">Admin</span>
                    @else
                        <span class="px-3 py-1 bg-slate-700/50 text-slate-400 rounded-full text-xs">User</span>
                    @endif
                </td>
                <td class="p-4 text-right pr-8 space-x-2">
                    <form action="{{ route('admin.users.toggle-admin', $user) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-500 transition-colors" title="Toggle Admin">
                            ⭐
                        </button>
                    </form>
                    <form action="{{ route('admin.users.toggle-admin', $user) }}" method="POST" class="inline" onsubmit="return confirm('Hapus user ini selamanya?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-500 transition-colors" title="Delete User">
                            🗑️
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $users->links() }}
</div>
@endsection
