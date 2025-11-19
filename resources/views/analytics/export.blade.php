<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('analytics.index') }}" class="p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Export & Reports</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Download your analytics data in various formats</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        
        {{-- Export Options --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            {{-- Excel Export --}}
            <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-xl p-6 text-white cursor-pointer hover:scale-105 transition-transform" onclick="exportData('excel')">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-white/20 backdrop-blur-sm rounded-xl">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-2xl font-bold mb-2">Excel (.xlsx)</h3>
                <p class="text-sm opacity-90 mb-4">Complete data with formulas and charts</p>
                <button class="w-full px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-lg font-semibold transition-all">
                    Download Excel
                </button>
            </div>

            {{-- PDF Export --}}
            <div class="bg-gradient-to-br from-red-500 to-pink-600 rounded-2xl shadow-xl p-6 text-white cursor-pointer hover:scale-105 transition-transform" onclick="exportData('pdf')">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-white/20 backdrop-blur-sm rounded-xl">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-2xl font-bold mb-2">PDF Report</h3>
                <p class="text-sm opacity-90 mb-4">Professional formatted report</p>
                <button class="w-full px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-lg font-semibold transition-all">
                    Download PDF
                </button>
            </div>

            {{-- CSV Export --}}
            <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl shadow-xl p-6 text-white cursor-pointer hover:scale-105 transition-transform" onclick="exportData('csv')">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-white/20 backdrop-blur-sm rounded-xl">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-2xl font-bold mb-2">CSV Data</h3>
                <p class="text-sm opacity-90 mb-4">Raw data for analysis tools</p>
                <button class="w-full px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-lg font-semibold transition-all">
                    Download CSV
                </button>
            </div>
        </div>

        {{-- Custom Report Builder --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                <svg class="w-7 h-7 mr-3 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                </svg>
                Custom Report Builder
            </h3>
            
            <form id="reportForm" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    
                    {{-- Date Range --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Date Range</label>
                        <select class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500">
                            <option>Last 7 days</option>
                            <option>Last 30 days</option>
                            <option>Last 3 months</option>
                            <option>Last 6 months</option>
                            <option>Last year</option>
                            <option>Custom range</option>
                        </select>
                    </div>

                    {{-- Report Type --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Report Type</label>
                        <select class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500">
                            <option>Summary Report</option>
                            <option>Detailed Transactions</option>
                            <option>Category Analysis</option>
                            <option>Spending Trends</option>
                            <option>Payment Methods</option>
                        </select>
                    </div>

                    {{-- Format --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Format</label>
                        <select class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500">
                            <option>Excel (.xlsx)</option>
                            <option>PDF Report</option>
                            <option>CSV Data</option>
                            <option>JSON Data</option>
                        </select>
                    </div>
                </div>

                {{-- Include Options --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Include in Report</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <label class="flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600">
                            <input type="checkbox" class="w-5 h-5 text-indigo-600 rounded focus:ring-indigo-500" checked>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Transactions</span>
                        </label>
                        <label class="flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600">
                            <input type="checkbox" class="w-5 h-5 text-indigo-600 rounded focus:ring-indigo-500" checked>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Charts</span>
                        </label>
                        <label class="flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600">
                            <input type="checkbox" class="w-5 h-5 text-indigo-600 rounded focus:ring-indigo-500" checked>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Statistics</span>
                        </label>
                        <label class="flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600">
                            <input type="checkbox" class="w-5 h-5 text-indigo-600 rounded focus:ring-indigo-500">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Predictions</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-semibold hover:bg-gray-300 dark:hover:bg-gray-600 transition-all">
                        Reset
                    </button>
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all shadow-lg shadow-indigo-500/50">
                        Generate Report
                    </button>
                </div>
            </form>
        </div>

        {{-- Recent Exports --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Recent Exports
            </h3>
            
            <div class="space-y-3">
                @for($i = 1; $i <= 5; $i++)
                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">Monthly Report - {{ now()->subMonths($i)->format('M Y') }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Excel • 2.4 MB • {{ now()->subDays($i)->diffForHumans() }}</p>
                        </div>
                    </div>
                    <button class="p-2 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                    </button>
                </div>
                @endfor
            </div>
        </div>

        {{-- Scheduled Reports --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                    <svg class="w-6 h-6 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Scheduled Reports
                </h3>
                <button class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-all">
                    + Add Schedule
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 border-2 border-indigo-200 dark:border-indigo-800 rounded-xl bg-indigo-50 dark:bg-indigo-900/20">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white">Monthly Summary</h4>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mb-2">📧 Email: your@email.com</p>
                    <p class="text-xs text-gray-600 dark:text-gray-400">📅 Every 1st of month • Excel format</p>
                </div>

                <div class="p-4 border-2 border-gray-200 dark:border-gray-700 rounded-xl">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white">Weekly Analytics</h4>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mb-2">📧 Email: your@email.com</p>
                    <p class="text-xs text-gray-600 dark:text-gray-400">📅 Every Monday • PDF format</p>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
        function exportData(format) {
            alert(`Exporting data in ${format.toUpperCase()} format...`);
            // Implement actual export logic
        }

        document.getElementById('reportForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Generating custom report...');
            // Implement report generation
        });
    </script>
    @endpush
</x-app-layout>