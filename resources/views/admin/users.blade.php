@extends('layouts.admin')

@section('content')
<div class="mb-12 flex justify-between items-end">
    <div>
        <h2 class="text-5xl font-bold tracking-tight mb-4 text-white">User <span class="text-pinterest">Command</span></h2>
        <p class="text-gray-500 text-lg">Kelola status, verifikasi, dan akses seluruh user.</p>
    </div>
</div>

<div class="bg-card rounded-[2.5rem] border border-white/5 overflow-hidden shadow-2xl">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white/5 text-gray-400 uppercase text-[10px] tracking-[0.2em] font-bold">
                    <th class="p-8 pl-12">User Identity</th>
                    <th class="p-8">Status Flags</th>
                    <th class="p-8 text-right pr-12">God Commands</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @foreach($users as $user)
                <tr class="hover:bg-white/[0.02] transition-all duration-300 group">
                    <td class="p-8 pl-12">
                        <div class="flex items-center gap-6">
                            <div class="relative">
                                <img src="{{ $user->avatar_url }}" class="w-14 h-14 rounded-full object-cover ring-2 ring-white/5 group-hover:ring-pinterest transition-all duration-500">
                                @if($user->is_verified)
                                    <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center border-2 border-card text-[10px] shadow-lg">✅</div>
                                @endif
                            </div>
                            <div>
                                <div class="font-bold text-white text-xl flex items-center gap-2">
                                    {{ $user->name }}
                                    @if($user->is_admin)
                                        <span class="text-[10px] bg-rose-500/10 text-rose-500 px-2 py-0.5 rounded-md uppercase tracking-widest border border-rose-500/20">Staff</span>
                                    @endif
                                </div>
                                <div class="text-sm text-gray-500 font-medium">@ {{ $user->username }} — {{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="p-8">
                        <div class="flex gap-2">
                            @if($user->is_shadowbanned)
                                <span class="px-3 py-1 bg-gray-500/10 text-gray-500 rounded-lg text-[10px] font-bold uppercase border border-gray-500/20">Shadowbanned</span>
                            @endif
                            @if($user->is_verified)
                                <span class="px-3 py-1 bg-blue-500/10 text-blue-500 rounded-lg text-[10px] font-bold uppercase border border-blue-500/20">Verified</span>
                            @endif
                            @if(!$user->is_verified && !$user->is_shadowbanned)
                                <span class="px-3 py-1 bg-emerald-500/10 text-emerald-500 rounded-lg text-[10px] font-bold uppercase border border-emerald-500/20">Healthy</span>
                            @endif
                        </div>
                    </td>
                    <td class="p-8 text-right pr-12 space-x-3">
                        <!-- Impersonate -->
                        <form action="{{ route('admin.users.impersonate', $user) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="p-3 bg-white/5 hover:bg-white/10 text-white rounded-xl transition-all title='Login as User'">
                                👤
                            </button>
                        </form>

                        <!-- Toggle Verified -->
                        <form action="{{ route('admin.users.toggle-verified', $user) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="p-3 bg-white/5 hover:bg-blue-500/20 text-blue-500 rounded-xl transition-all {{ $user->is_verified ? 'ring-2 ring-blue-500' : '' }}" title="Toggle Verified">
                                ✅
                            </button>
                        </form>

                        <!-- Toggle Shadowban -->
                        <form action="{{ route('admin.users.toggle-shadowban', $user) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="p-3 bg-white/5 hover:bg-gray-500/20 text-gray-500 rounded-xl transition-all {{ $user->is_shadowbanned ? 'ring-2 ring-gray-500' : '' }}" title="Toggle Shadowban">
                                👻
                            </button>
                        </form>

                        <!-- Toggle Admin -->
                        <form action="{{ route('admin.users.toggle-admin', $user) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="p-3 bg-white/5 hover:bg-rose-500/20 text-rose-500 rounded-xl transition-all {{ $user->is_admin ? 'ring-2 ring-rose-500' : '' }}" title="Toggle Admin">
                                👑
                            </button>
                        </form>

                        <!-- Delete -->
                        <form action="{{ route('admin.users.delete', $user) }}" method="POST" class="inline" onsubmit="return confirm('Hapus user ini selamanya?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-3 bg-rose-500/10 hover:bg-rose-500 text-rose-500 hover:text-white rounded-xl transition-all">
                                🗑️
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-10">
    {{ $users->links() }}
</div>
@endsection
