@extends('layouts.admin')

@section('content')
<div class="mb-10">
    <h2 class="text-4xl font-black tracking-tighter mb-2">ANNOUNCEMENTS</h2>
    <p class="text-gray-500 text-sm font-medium uppercase tracking-widest">Global Broadcast System</p>
</div>

<div class="max-w-2xl">
    <div class="bg-card p-8 rounded-3xl border border-border">
        <form action="{{ route('admin.announce.send') }}" method="POST">
            @csrf
            <div class="mb-8">
                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-4">Message Content</label>
                <textarea 
                    name="message" 
                    rows="5" 
                    class="w-full bg-white/5 border border-border rounded-2xl p-6 text-white focus:border-white focus:ring-0 transition-all placeholder:text-gray-700 text-sm font-medium"
                    placeholder="Broadcast your message to all users..."
                >{{ \Illuminate\Support\Facades\Cache::get('global_announcement') }}</textarea>
            </div>

            <div class="flex gap-4">
                <button type="submit" name="action" value="send" class="flex-1 bg-white text-black font-black text-xs uppercase tracking-[0.2em] py-4 rounded-2xl hover:scale-[1.02] transition-all active:scale-95">
                    Broadcast
                </button>
                <button type="submit" name="action" value="clear" class="px-8 border border-border text-gray-500 hover:text-white hover:border-white font-black text-xs uppercase tracking-[0.2em] py-4 rounded-2xl transition-all">
                    Clear
                </button>
            </div>
        </form>
    </div>

    <div class="mt-8 p-6 border border-border rounded-2xl flex gap-4 items-center">
        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <p class="text-xs text-gray-500 font-medium italic">Announcements will appear as a high-visibility banner at the top of every page.</p>
    </div>
</div>
@endsection
