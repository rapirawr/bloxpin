@extends('layouts.admin')

@section('content')
<div class="mb-10">
    <h2 class="text-4xl font-black tracking-tighter mb-2">ANNOUNCEMENTS</h2>
    <p class="text-gray-500 text-sm font-medium uppercase tracking-widest">Global Broadcast System</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
    <!-- Form Section -->
    <div class="lg:col-span-2">
        <div class="bg-card p-8 rounded-3xl border border-border mb-10">
            <form action="{{ route('admin.announce.send') }}" method="POST">
                @csrf
                <div class="mb-8">
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-4">Message Content</label>
                    <textarea 
                        name="message" 
                        rows="4" 
                        class="w-full bg-white/5 border border-border rounded-2xl p-6 text-white focus:border-white focus:ring-0 transition-all placeholder:text-gray-700 text-sm font-medium"
                        placeholder="What's happening?"
                    >{{ $current ? $current->message : '' }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-6 mb-8">
                    <div>
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-4">Duration</label>
                        <select name="duration" class="w-full bg-white/5 border border-border rounded-xl px-4 py-3 text-sm text-white focus:border-white outline-none">
                            <option value="1h" class="bg-black">1 Hour</option>
                            <option value="1d" class="bg-black">1 Day</option>
                            <option value="1w" class="bg-black">1 Week</option>
                            <option value="permanent" class="bg-black" selected>Permanent</option>
                        </select>
                    </div>
                </div>

                <div class="flex gap-4">
                    <button type="submit" name="action" value="send" class="flex-1 bg-white text-black font-black text-xs uppercase tracking-[0.2em] py-4 rounded-2xl hover:scale-[1.02] transition-all active:scale-95">
                        Broadcast New
                    </button>
                    @if($current)
                    <button type="submit" name="action" value="clear" @click.prevent="window.appConfirm('Deaktivasi', 'Matikan semua pengumuman aktif?', () => { $el.closest('form').querySelector('button[value=clear]').type='submit'; $el.closest('form').submit(); })" class="px-8 border border-border text-gray-500 hover:text-white hover:border-white font-black text-xs uppercase tracking-[0.2em] py-4 rounded-2xl transition-all">
                        Deactivate All
                    </button>
                    @endif
                </div>
            </form>
        </div>

        <!-- History Table -->
        <div class="bg-card rounded-2xl border border-border overflow-hidden">
            <div class="p-6 border-b border-border">
                <h3 class="font-black text-xs uppercase tracking-widest">Broadcast History</h3>
            </div>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white/[0.02] border-b border-border text-[9px] font-black text-gray-500 uppercase tracking-widest">
                        <th class="p-6">Message</th>
                        <th class="p-6">Status</th>
                        <th class="p-6">Expires</th>
                        <th class="p-6 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($history as $item)
                    <tr class="hover:bg-white/[0.01] transition-colors">
                        <td class="p-6">
                            <p class="text-xs font-medium text-gray-300 truncate max-w-xs">{{ $item->message }}</p>
                            <span class="text-[8px] text-gray-600 uppercase font-black tracking-tighter">{{ $item->created_at->format('d M Y, H:i') }}</span>
                        </td>
                        <td class="p-6">
                            @if($item->is_active && (!$item->ends_at || $item->ends_at->isFuture()))
                                <span class="px-2 py-0.5 bg-white text-black text-[8px] font-black uppercase tracking-tighter rounded">Live</span>
                            @else
                                <span class="px-2 py-0.5 bg-white/5 text-gray-600 text-[8px] font-black uppercase tracking-tighter rounded">Ended</span>
                            @endif
                        </td>
                        <td class="p-6 text-[10px] text-gray-500 font-medium">
                            {{ $item->ends_at ? $item->ends_at->diffForHumans() : 'Never' }}
                        </td>
                        <td class="p-6 text-right">
                            <form action="{{ route('admin.announce.delete', $item) }}" method="POST" @submit.prevent="window.appConfirm('Hapus Riwayat', 'Yakin ingin menghapus riwayat pengumuman ini?', () => $el.submit(), 'Hapus')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-500 hover:text-red-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-10 text-center text-gray-600 text-xs font-medium italic">No broadcast history found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $history->links() }}
        </div>
    </div>

    <!-- Info Sidebar -->
    <div class="space-y-6">
        <div class="bg-card p-8 rounded-3xl border border-border">
            <h4 class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-6">Current Live</h4>
            @if($current)
                <div class="p-4 bg-white/5 rounded-2xl border border-white/5 mb-4">
                    <p class="text-sm font-bold text-white leading-relaxed mb-4">"{{ $current->message }}"</p>
                    <div class="flex items-center gap-2 text-[9px] font-black uppercase tracking-widest text-gray-500">
                        <div class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></div>
                        Ends: {{ $current->ends_at ? $current->ends_at->diffForHumans() : 'PERMANENT' }}
                    </div>
                </div>
            @else
                <p class="text-xs text-gray-600 italic">No active broadcast.</p>
            @endif
        </div>

        <div class="p-8 border border-border rounded-3xl">
            <h4 class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-4">System Guide</h4>
            <p class="text-xs text-gray-600 leading-relaxed font-medium">
                Setting a new announcement will automatically deactivate the previous one. Permanent broadcasts will stay until you manually clear them.
            </p>
        </div>
    </div>
</div>
@endsection
