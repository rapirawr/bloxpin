@extends('layouts.admin')

@section('content')
<div class="mb-10">
    <h2 class="text-4xl font-black tracking-tighter mb-2 italic uppercase">OPERATIONS CENTER</h2>
    <p class="text-gray-500 text-sm font-medium uppercase tracking-[0.2em] flex items-center gap-2">
        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
        Live Platform Analytics
    </p>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
    <div class="bg-card p-6 rounded-2xl border border-border group hover:border-white transition-all duration-300 relative overflow-hidden">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </div>
        <div class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">Total Agents</div>
        <div class="text-4xl font-black italic">{{ number_format($stats['users_count']) }}</div>
    </div>

    <div class="bg-card p-6 rounded-2xl border border-border group hover:border-white transition-all duration-300 relative overflow-hidden">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h14a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        </div>
        <div class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">Total Visuals</div>
        <div class="text-4xl font-black italic">{{ number_format($stats['photos_count']) }}</div>
    </div>

    <div class="bg-card p-6 rounded-2xl border border-border group hover:border-white transition-all duration-300 relative overflow-hidden">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
        </div>
        <div class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">Total Boards</div>
        <div class="text-4xl font-black italic">{{ number_format($stats['boards_count']) }}</div>
    </div>

    <div class="bg-card p-6 rounded-2xl border border-border group hover:border-white transition-all duration-300 relative overflow-hidden">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
        </div>
        <div class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">Engagements</div>
        <div class="text-4xl font-black italic">{{ number_format($stats['comments_count']) }}</div>
    </div>
</div>

<!-- Charts Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
    <!-- Growth Trend -->
    <div class="bg-card p-8 rounded-3xl border border-border shadow-2xl">
        <div class="flex items-center justify-between mb-8">
            <h3 class="font-black text-xs uppercase tracking-[0.3em] text-gray-500">Growth Velocity (7D)</h3>
            <div class="flex gap-4">
                <div class="flex items-center gap-2 text-[10px] font-bold text-pinterest uppercase tracking-widest">
                    <span class="w-2 h-2 bg-pinterest rounded-full"></span> Photos
                </div>
                <div class="flex items-center gap-2 text-[10px] font-bold text-white uppercase tracking-widest">
                    <span class="w-2 h-2 bg-white rounded-full"></span> Users
                </div>
            </div>
        </div>
        <div class="h-[300px]">
            <canvas id="growthChart"></canvas>
        </div>
    </div>

    <!-- Distribution -->
    <div class="bg-card p-8 rounded-3xl border border-border shadow-2xl">
        <h3 class="font-black text-xs uppercase tracking-[0.3em] text-gray-500 mb-8 text-center">Data Distribution</h3>
        <div class="h-[300px] flex items-center justify-center">
            <canvas id="distributionChart"></canvas>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Recent Activity -->
    <div class="bg-card border border-border rounded-3xl overflow-hidden shadow-2xl">
        <div class="p-8 border-b border-border flex justify-between items-center">
            <h3 class="font-black text-xs uppercase tracking-[0.3em]">Recent Agents</h3>
            <a href="{{ route('admin.users') }}" class="text-[10px] font-black hover:text-pinterest transition-colors tracking-widest">DECRYPT ALL</a>
        </div>
        <div class="divide-y divide-border">
            @foreach($stats['latest_users'] as $user)
            <div class="p-6 flex items-center justify-between hover:bg-white/[0.02] transition-colors group">
                <div class="flex items-center gap-5">
                    <img src="{{ $user->avatar_url }}" class="w-12 h-12 rounded-full border border-border grayscale group-hover:grayscale-0 transition-all">
                    <div>
                        <div class="font-black text-sm tracking-tight">{{ $user->name }}</div>
                        <div class="text-[10px] text-gray-500 font-bold tracking-widest uppercase">@ {{ $user->username }}</div>
                    </div>
                </div>
                <span class="text-[10px] font-black text-gray-600 uppercase tracking-tighter">{{ $user->created_at->diffForHumans() }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Latest Photos -->
    <div class="bg-card border border-border rounded-3xl overflow-hidden shadow-2xl">
        <div class="p-8 border-b border-border flex justify-between items-center">
            <h3 class="font-black text-xs uppercase tracking-[0.3em]">Latest Visuals</h3>
            <a href="{{ route('admin.photos') }}" class="text-[10px] font-black hover:text-pinterest transition-colors tracking-widest">MODERATE</a>
        </div>
        <div class="divide-y divide-border">
            @foreach($stats['latest_photos'] as $photo)
            <div class="p-6 flex items-center gap-5 hover:bg-white/[0.02] transition-colors group">
                <img src="{{ $photo->thumbnail_url }}" class="w-14 h-14 rounded-xl object-cover border border-border grayscale group-hover:grayscale-0 transition-all">
                <div class="flex-1 min-w-0">
                    <div class="font-black text-sm truncate tracking-tight uppercase italic">{{ $photo->title }}</div>
                    <div class="text-[10px] text-gray-500 font-bold uppercase tracking-[0.2em]">by {{ $photo->user->name }}</div>
                </div>
                <span class="text-[10px] font-black text-gray-600 uppercase tracking-tighter whitespace-nowrap">{{ $photo->created_at->diffForHumans() }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctxGrowth = document.getElementById('growthChart').getContext('2d');
        const ctxDist = document.getElementById('distributionChart').getContext('2d');

        // Chart.js Default Config for Dark Theme
        Chart.defaults.color = '#555';
        Chart.defaults.font.family = "'Outfit', sans-serif";

        new Chart(ctxGrowth, {
            type: 'line',
            data: {
                labels: {!! json_encode($stats['chart']['labels']) !!},
                datasets: [
                    {
                        label: 'Photos',
                        data: {!! json_encode($stats['chart']['photos']) !!},
                        borderColor: '#E60023',
                        backgroundColor: 'rgba(230, 0, 35, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 4,
                        pointRadius: 0,
                        pointHoverRadius: 6,
                    },
                    {
                        label: 'Users',
                        data: {!! json_encode($stats['chart']['users']) !!},
                        borderColor: '#FFFFFF',
                        backgroundColor: 'rgba(255, 255, 255, 0.05)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 4,
                        pointRadius: 0,
                        pointHoverRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: { color: 'rgba(255, 255, 255, 0.05)' },
                        ticks: { stepSize: 1 }
                    },
                    x: { grid: { display: false } }
                }
            }
        });

        new Chart(ctxDist, {
            type: 'doughnut',
            data: {
                labels: ['Photos', 'Users', 'Boards', 'Comments'],
                datasets: [{
                    data: [{{ $stats['photos_count'] }}, {{ $stats['users_count'] }}, {{ $stats['boards_count'] }}, {{ $stats['comments_count'] }}],
                    backgroundColor: ['#E60023', '#FFFFFF', '#333333', '#111111'],
                    borderColor: '#000000',
                    borderWidth: 10,
                    hoverOffset: 20
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '80%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            font: { size: 10, weight: 'bold' },
                            usePointStyle: true
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
