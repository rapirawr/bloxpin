@extends('layouts.admin')

@section('content')
<div class="mb-10">
    <h2 class="text-4xl font-black tracking-tighter mb-2 italic">CONTENT REPORTS</h2>
    <p class="text-gray-500 text-sm font-medium uppercase tracking-widest flex items-center gap-2">
        <span class="w-2 h-2 bg-red-500 rounded-full"></span>
        Pending Moderation Queue
    </p>
</div>

<div class="bg-card rounded-3xl border border-white/5 overflow-hidden shadow-2xl">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-white/[0.02] border-b border-white/5 text-[10px] font-black text-gray-500 uppercase tracking-[0.2em]">
                <th class="p-6">Target Photo</th>
                <th class="p-6">Reporter</th>
                <th class="p-6">Reason</th>
                <th class="p-6">Status</th>
                <th class="p-6 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
            @forelse($reports as $report)
            <tr class="hover:bg-white/[0.01] transition-colors group">
                <td class="p-6">
                    <div class="flex items-center gap-4">
                        @if($report->photo)
                            <img src="{{ $report->photo->thumbnail_url }}" class="w-12 h-12 rounded-lg object-cover border border-white/5">
                            <div class="min-w-0">
                                <div class="font-bold text-sm truncate w-40 italic uppercase tracking-tighter">{{ $report->photo->title }}</div>
                                <div class="text-[10px] text-gray-500 font-bold uppercase">by {{ $report->photo->user->name }}</div>
                            </div>
                        @else
                            <div class="text-red-500 text-xs font-bold italic uppercase">[Deleted Photo]</div>
                        @endif
                    </div>
                </td>
                <td class="p-6">
                    <div class="flex items-center gap-3">
                        <img src="{{ $report->user->avatar_url }}" class="w-8 h-8 rounded-full border border-white/5 transition-all">
                        <div class="text-xs font-bold">{{ $report->user->name }}</div>
                    </div>
                </td>
                <td class="p-6">
                    <div class="px-3 py-1 bg-red-500/10 text-red-500 text-[10px] font-black uppercase rounded-full inline-block mb-1">
                        {{ $report->reason }}
                    </div>
                    @if($report->description)
                        <div class="text-[10px] text-gray-500 max-w-[200px] truncate" title="{{ $report->description }}">
                            "{{ $report->description }}"
                        </div>
                    @endif
                </td>
                <td class="p-6 text-xs">
                    @if($report->status === 'pending')
                        <span class="text-yellow-500 font-black uppercase italic animate-pulse">Pending</span>
                    @elseif($report->status === 'resolved')
                        <span class="text-green-500 font-black uppercase italic">Resolved</span>
                    @else
                        <span class="text-gray-500 font-black uppercase italic">Dismissed</span>
                    @endif
                </td>
                <td class="p-6 text-right space-x-2">
                    @if($report->status === 'pending')
                        <form action="{{ route('admin.reports.resolve', $report) }}" method="POST" class="inline" @submit.prevent="window.appConfirm('Selesaikan Laporan', 'Tandai laporan ini sebagai SELESAI?', () => $el.submit(), 'Lanjutkan')">
                            @csrf
                            <input type="hidden" name="status" value="resolved">
                            <button type="submit" class="text-[10px] font-black text-green-500 hover:bg-green-500 hover:text-white px-3 py-1.5 rounded-lg border border-green-500/20 transition-all uppercase tracking-tighter">
                                Resolve
                            </button>
                        </form>
                        <form action="{{ route('admin.reports.resolve', $report) }}" method="POST" class="inline" @submit.prevent="window.appConfirm('Tolak Laporan', 'Tolak laporan ini?', () => $el.submit(), 'Tolak')">
                            @csrf
                            <input type="hidden" name="status" value="dismissed">
                            <button type="submit" class="text-[10px] font-black text-gray-500 hover:bg-white hover:text-black px-3 py-1.5 rounded-lg border border-white/5 transition-all uppercase tracking-tighter">
                                Dismiss
                            </button>
                        </form>
                    @endif
                    
                    @if($report->photo)
                        <a href="{{ route('photos.show', $report->photo->uid) }}" target="_blank" class="text-[10px] font-black text-pinterest hover:bg-pinterest hover:text-white px-3 py-1.5 rounded-lg border border-pinterest/20 transition-all uppercase tracking-tighter inline-block">
                            View
                        </a>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="p-20 text-center text-gray-600 italic font-medium uppercase tracking-widest text-sm">
                    No reports found. The platform is clean.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-8">
    {{ $reports->links() }}
</div>
@endsection
