@extends('layouts.admin')

@section('content')
<div class="mb-10">
    <h2 class="text-4xl font-black tracking-tighter mb-2 italic">SQL TERMINAL</h2>
    <p class="text-gray-500 text-sm font-medium uppercase tracking-widest flex items-center gap-2">
        <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
        Direct Database Access — Use with caution
    </p>
</div>

<div class="grid grid-cols-1 gap-6" x-data="{ 
    query: '',
    results: null,
    loading: false,
    error: null,
    history: [],
    
    execute() {
        if (!this.query.trim() || this.loading) return;
        
        this.loading = true;
        this.error = null;
        this.results = null;
        
        axios.post('{{ route('admin.sql.execute') }}', { 
            query: this.query 
        }, {
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
            .then(res => {
                this.results = res.data;
                if (!this.history.includes(this.query)) {
                    this.history.unshift(this.query);
                    if (this.history.length > 10) this.history.pop();
                }
            })
            .catch(err => {
                this.error = err.response?.data?.message || 'An unknown error occurred';
            })
            .finally(() => {
                this.loading = false;
            });
    },

    setQuery(q) {
        this.query = q;
        this.$nextTick(() => {
            this.$refs.textarea.focus();
        });
    }
}">
    <!-- Terminal Input -->
    <div class="bg-[#0D0D0D] rounded-2xl border border-white/10 shadow-2xl overflow-hidden">
        <div class="flex items-center justify-between px-4 py-3 bg-white/5 border-b border-white/10">
            <div class="flex gap-1.5">
                <div class="w-3 h-3 rounded-full bg-[#FF5F56]"></div>
                <div class="w-3 h-3 rounded-full bg-[#FFBD2E]"></div>
                <div class="w-3 h-3 rounded-full bg-[#27C93F]"></div>
            </div>
            <div class="text-[10px] font-mono text-gray-500 uppercase tracking-widest font-bold">PostgreSQL Console</div>
        </div>
        
        <div class="p-6">
            <div class="flex gap-4">
                <div class="text-pinterest font-mono font-bold text-lg pt-1">bloxpin=></div>
                <textarea 
                    x-ref="textarea"
                    x-model="query"
                    @keydown.ctrl.enter="execute()"
                    class="flex-1 bg-transparent border-none focus:ring-0 text-white font-mono text-lg p-0 resize-none min-h-[120px] placeholder-white/20"
                    placeholder="Enter your SQL query here... (Ctrl + Enter to execute)"
                ></textarea>
            </div>
            
            <div class="mt-6 flex justify-between items-center border-t border-white/5 pt-4">
                <div class="flex gap-4">
                    <button @click="setQuery('SELECT * FROM users LIMIT 10')" class="text-[10px] font-mono text-gray-500 hover:text-white transition-colors uppercase tracking-wider">Quick Select Users</button>
                    <button @click="setQuery('SELECT * FROM photos ORDER BY created_at DESC LIMIT 10')" class="text-[10px] font-mono text-gray-500 hover:text-white transition-colors uppercase tracking-wider">Latest Photos</button>
                    <button @click="query = ''" class="text-[10px] font-mono text-red-500/50 hover:text-red-500 transition-colors uppercase tracking-wider">Clear Console</button>
                </div>
                
                <button 
                    @click="execute()"
                    :disabled="loading || !query.trim()"
                    class="bg-white text-black px-6 py-2 rounded-lg font-black text-xs uppercase tracking-tighter hover:bg-pinterest hover:text-white transition-all active:scale-95 disabled:opacity-50 disabled:pointer-events-none flex items-center gap-2"
                >
                    <template x-if="loading">
                        <svg class="animate-spin h-3 w-3" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </template>
                    <span x-text="loading ? 'Executing...' : 'Run Query'"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Results Area -->
    <template x-if="error">
        <div class="bg-red-500/10 border border-red-500/20 rounded-2xl p-6 text-red-500 font-mono text-sm overflow-x-auto">
            <div class="font-bold mb-2 uppercase tracking-widest text-[10px]">Error Trace:</div>
            <pre x-text="error" class="whitespace-pre-wrap"></pre>
        </div>
    </template>

    <template x-if="results">
        <div class="space-y-6">
            <template x-if="results.type === 'select'">
                <div class="bg-card border border-border rounded-2xl overflow-hidden shadow-xl">
                    <div class="px-6 py-4 bg-white/[0.02] border-b border-border flex justify-between items-center">
                        <div class="text-[10px] font-black text-gray-500 uppercase tracking-widest">
                            Result Set (<span x-text="results.count"></span> rows)
                        </div>
                        <button class="text-[10px] font-black text-pinterest hover:underline uppercase tracking-widest">Export CSV</button>
                    </div>
                    <div class="overflow-x-auto max-h-[500px]">
                        <table class="w-full text-left border-collapse font-mono text-xs">
                            <thead>
                                <tr class="bg-white/[0.01] border-b border-border">
                                    <template x-for="column in results.columns">
                                        <th class="p-4 font-bold text-gray-400 whitespace-nowrap" x-text="column"></th>
                                    </template>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border">
                                <template x-for="(row, index) in results.rows" :key="index">
                                    <tr class="hover:bg-white/[0.01] transition-colors">
                                        <template x-for="column in results.columns">
                                            <td class="p-4 text-gray-300 whitespace-nowrap overflow-hidden max-w-[200px] truncate" 
                                                :title="row[column]"
                                                x-text="row[column] === null ? 'NULL' : row[column]">
                                            </td>
                                        </template>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </template>
            
            <template x-if="results.type === 'statement' || results.type === 'empty'">
                <div class="bg-green-500/10 border border-green-500/20 rounded-2xl p-6 text-green-500 font-mono text-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span x-text="results.message || 'Success'"></span>
                    </div>
                </div>
            </template>
        </div>
    </template>
</div>

<style>
    textarea::-webkit-scrollbar {
        width: 8px;
    }
    textarea::-webkit-scrollbar-track {
        background: transparent;
    }
    textarea::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 4px;
    }
    textarea::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.2);
    }
</style>
@endsection
