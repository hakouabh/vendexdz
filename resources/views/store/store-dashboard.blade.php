@extends('layouts.appstore')

@section('title', 'Analytics & Reports - Vendex')

@section('content')
<div class="mb-8 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
    <div>
        <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">Performance Dashboard</h1>
        <p class="text-slate-500 mt-1 text-sm font-medium flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
            Live Data updated: <span class="font-mono text-slate-700">Friday, 26 Dec • 14:30</span>
        </p>
    </div>

    <div class="flex flex-wrap gap-3">
        <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="ri-calendar-line text-slate-400"></i>
            </div>
            <select
                class="pl-10 pr-8 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-xl text-sm font-bold shadow-sm focus:ring-2 focus:ring-indigo-500 cursor-pointer hover:border-indigo-300 transition-colors">
                <option>This Month (Dec)</option>
                <option>Last Month (Nov)</option>
                <option>This Year</option>
            </select>
        </div>
        <button
            class="flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-slate-200 transition-all hover:-translate-y-0.5">
            <i class="ri-file-excel-2-line"></i> Export CSV
        </button>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <div
        class="bg-white p-6 rounded-[24px] border border-slate-100 shadow-[0_4px_20px_-4px_rgba(99,102,241,0.1)] relative overflow-hidden group">
        <div class="absolute top-0 right-0 p-4 opacity-50 group-hover:opacity-100 transition-opacity">
            <i class="ri-box-3-line text-8xl text-gray-200 transform rotate-12 -translate-y-4 translate-x-4"></i>
        </div>
        <div class="relative z-10">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Total Order</p>
            <div class="flex items-baseline gap-1 mb-2">
                <h3 class="text-3xl font-black text-slate-900">100</h3>
                <span class="text-sm font-bold text-slate-400">Order</span>
            </div>
            <div class="flex items-center gap-2">
                <span
                    class="bg-yellow-50 text-yellow-700 border border-yellow-100 text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                    <i class="ri-time-line"></i> 10 Pending
                </span>
                <span class="text-[10px] text-slate-400">From Total</span>
            </div>
        </div>
    </div>
    <div class="bg-slate-900 p-6 rounded-[24px] shadow-xl shadow-slate-200 relative overflow-hidden group">
        <div class="absolute top-0 right-0 p-4 opacity-50 group-hover:opacity-100 transition-opacity">
            <i
                class="ri-checkbox-circle-line text-8xl text-green-200 transform rotate-12 -translate-y-4 translate-x-4"></i>
        </div>
        <div class="relative z-10">
            <p class="text-xs font-bold text-slate-100 uppercase tracking-wider mb-2">Confirmed</p>
            <div class="flex items-baseline gap-1 mb-2">
                <h3 class="text-3xl font-black text-slate-200">100</h3>
                <span class="text-sm font-bold text-slate-400">Order</span>
            </div>
            <div class="flex items-center gap-2">
                <!--<span
                    class="bg-blue-50 text-blue-700 border border-green-100 text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                    <i class="ri-file-copy-line"></i> 10 Double
                </span>
                <span
                    class="bg-gray-50 text-gray-800 border border-green-100 text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                    <i class="ri-close-circle-line"></i> 10 False
                </span>-->
                <span class="text-[10px] text-slate-400">From Total</span>
            </div>
        </div>
    </div>
    <div
        class="bg-white p-6 rounded-[24px] border border-slate-100 shadow-[0_4px_20px_-4px_rgba(99,102,241,0.1)] relative overflow-hidden group">
        <div class="absolute top-0 right-0 p-4 opacity-50 group-hover:opacity-100 transition-opacity">
            <i
                class="ri-error-warning-line text-8xl text-yellow-300 transform rotate-12 -translate-y-4 translate-x-4"></i>
        </div>
        <div class="relative z-10">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Cancelled</p>
            <div class="flex items-baseline gap-1 mb-2">
                <h3 class="text-3xl font-black text-slate-900">100</h3>
                <span class="text-sm font-bold text-slate-400">Order</span>
            </div>
            <div class="flex items-center gap-2">
                <span
                    class="bg-blue-50 text-blue-700 border border-green-100 text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                    <i class="ri-file-copy-line"></i> +10 Double
                </span>
                <span
                    class="bg-gray-50 text-gray-800 border border-green-100 text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                    <i class="ri-close-circle-line"></i> +10 False
                </span>
                <span class="text-[10px] text-slate-400">Plus in the total</span>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-[24px] shadow-xl shadow-slate-200 relative overflow-hidden group">
        <div class="absolute top-0 right-0 p-4 opacity-50 group-hover:opacity-100 transition-opacity">
            <i class="ri-loader-4-line text-8xl text-indigo-200 transform rotate-12 -translate-y-4 translate-x-4"></i>
        </div>
        <div class="relative z-10">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">In Process</p>
            <div class="flex items-baseline gap-1 mb-2">
                <h3 class="text-3xl font-black text-slate-900">100</h3>
                <span class="text-sm font-bold text-slate-400">Order</span>
            </div>
            <div class="flex items-center gap-2">
                <span
                    class="bg-orange-50 text-orange-700 border border-green-100 text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                    <i class="ri-file-copy-line"></i> 10 N/A
                </span>
                <span
                    class="bg-purple-50 text-purple-800 border border-green-100 text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                    <i class="ri-close-circle-line"></i> 10 REPORTED
                </span>

            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-[24px] shadow-xl shadow-slate-200 relative overflow-hidden group">
        <div class="absolute top-0 right-0 p-4 opacity-50 group-hover:opacity-100 transition-opacity">
            <i class="ri-truck-line text-8xl text-green-400 transform rotate-12 -translate-y-4 translate-x-4"></i>
        </div>
        <div class="relative z-10">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Delivered</p>
            <div class="flex items-baseline gap-1 mb-2">
                <h3 class="text-3xl font-black text-slate-900">100</h3>
                <span class="text-sm font-bold text-slate-400">Order</span>
            </div>
            <div class="flex items-center gap-2">
                <!-- <span
                    class="bg-orange-50 text-orange-700 border border-green-100 text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                    <i class="ri-file-copy-line"></i> 10 N/A
                </span>
                <span
                    class="bg-purple-50 text-purple-800 border border-green-100 text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                    <i class="ri-close-circle-line"></i> 10 REPORTED
                </span>-->

            </div>
        </div>
    </div>
    <div class="bg-white p-6 rounded-[24px] shadow-xl shadow-slate-200 relative overflow-hidden group">
        <div class="absolute top-0 right-0 p-4 opacity-50 group-hover:opacity-100 transition-opacity">
            <i class="ri-arrow-go-back-fill text-8xl text-red-600 transform rotate-12 -translate-y-4 translate-x-4"></i>
        </div>
        <div class="relative z-10">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Return</p>
            <div class="flex items-baseline gap-1 mb-2">
                <h3 class="text-3xl font-black text-slate-900">100</h3>
                <span class="text-sm font-bold text-slate-400">Order</span>
            </div>
            <div class="flex items-center gap-2">
                <!-- <span
                    class="bg-orange-50 text-orange-700 border border-green-100 text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                    <i class="ri-file-copy-line"></i> 10 N/A
                </span>
                <span
                    class="bg-purple-50 text-purple-800 border border-green-100 text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                    <i class="ri-close-circle-line"></i> 10 REPORTED
                </span>-->

            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <div x-data="{
    chart: null,
    combinedData: {
        labels: ['Dec 01', 'Dec 10', 'Dec 15', 'Dec 20', 'Dec 26'],
        datasets: [
            {
                label: 'Revenue',
                data: [420, 580, 690, 750, 820],
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#4f46e5',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointHoverBackgroundColor: '#4f46e5',
                pointHoverBorderColor: '#ffffff',
                pointHoverBorderWidth: 2,
                yAxisID: 'y'
            },
            {
                label: 'Orders',
                data: [120, 190, 150, 250, 220],
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#10b981',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointHoverBackgroundColor: '#10b981',
                pointHoverBorderColor: '#ffffff',
                pointHoverBorderWidth: 2,
                yAxisID: 'y1'
            }
        ]
    },
    
    init() {
        this.$nextTick(() => {
            const ctx = this.$refs.chartCanvas.getContext('2d');
            this.chart = new Chart(ctx, {
                type: 'line',
                data: this.combinedData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            align: 'end',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 8,
                                padding: 20,
                                font: {
                                    family: 'sans-serif',
                                    size: 12,
                                    weight: 'bold'
                                },
                                color: '#475569'
                            }
                        },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            titleFont: {
                                family: 'sans-serif',
                                weight: 'bold',
                                size: 12
                            },
                            bodyFont: {
                                family: 'sans-serif',
                                weight: 'bold',
                                size: 12
                            },
                            padding: 12,
                            cornerRadius: 8,
                            displayColors: true,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    const value = context.parsed.y;
                                    label += context.dataset.label === 'Revenue' ? value + 'k' : value;
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    family: 'sans-serif',
                                    size: 10,
                                    weight: 'bold'
                                },
                                color: '#94a3b8'
                            }
                        },
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            beginAtZero: true,
                            grid: {
                                borderDash: [5, 5],
                                color: '#f1f5f9'
                            },
                            ticks: {
                                font: {
                                    family: 'sans-serif',
                                    size: 10,
                                    weight: 'bold'
                                },
                                color: '#4f46e5',
                                callback: function(value) {
                                    return value + 'k';
                                }
                            },
                            title: {
                                display: true,
                                text: 'Revenue (k)',
                                color: '#4f46e5',
                                font: {
                                    family: 'sans-serif',
                                    size: 10,
                                    weight: 'bold'
                                }
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            beginAtZero: true,
                            grid: {
                                drawOnChartArea: false, // only show the grid for the left axis
                            },
                            ticks: {
                                font: {
                                    family: 'sans-serif',
                                    size: 10,
                                    weight: 'bold'
                                },
                                color: '#10b981'
                            },
                            title: {
                                display: true,
                                text: 'Orders',
                                color: '#10b981',
                                font: {
                                    family: 'sans-serif',
                                    size: 10,
                                    weight: 'bold'
                                }
                            }
                        }
                    },
                    animation: {
                        duration: 1000,
                        easing: 'easeOutQuart'
                    }
                }
            });
            
            // Simulate data updates every 5 seconds
            setInterval(() => {
                this.updateData();
            }, 5000);
        });
    },
    
    updateData() {
        // Update Revenue Data
        const newRevenueData = this.combinedData.datasets[0].data.map(() => 
            Math.floor(Math.random() * 500) + 400
        );
        this.chart.data.datasets[0].data = newRevenueData;
        
        // Update Orders Data
        const newOrdersData = this.combinedData.datasets[1].data.map(() => 
            Math.floor(Math.random() * 200) + 100
        );
        this.chart.data.datasets[1].data = newOrdersData;
        
        // Update chart with animation
        this.chart.update('active');
        
        // Update stats
        this.updateStats();
    },
    
    updateStats() {
        // Update Revenue Stats
        const revenueData = this.chart.data.datasets[0].data;
        this.$refs.revenueTotal.textContent = revenueData.reduce((sum, value) => sum + value, 0) + 'k';
        this.$refs.revenueAverage.textContent = Math.round(revenueData.reduce((sum, value) => sum + value, 0) / revenueData.length) + 'k';
        this.$refs.revenuePeak.textContent = Math.max(...revenueData) + 'k';
        
        // Update Orders Stats
        const ordersData = this.chart.data.datasets[1].data;
        this.$refs.ordersTotal.textContent = ordersData.reduce((sum, value) => sum + value, 0);
        this.$refs.ordersAverage.textContent = Math.round(ordersData.reduce((sum, value) => sum + value, 0) / ordersData.length);
        this.$refs.ordersPeak.textContent = Math.max(...ordersData);
    },
    
    refreshData() {
        this.updateData();
    }
}" class="bg-white col-span-2 p-6 rounded-[24px] border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-slate-900 text-lg">Revenue & Orders Overview</h3>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-xs font-bold text-slate-500">Live Data</span>
                </div>
                <button @click="refreshData()"
                    class="h-8 w-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:text-indigo-500 transition-all duration-300">
                    <i class="ri-refresh-line text-sm"></i>
                </button>
            </div>
        </div>

        <!-- Chart -->
        <div class="relative h-64 w-full">
            <canvas x-ref="chartCanvas"></canvas>
        </div>

        <!-- Combined Stats -->
        <div class="grid grid-cols-2 gap-6 mt-6">
            <!-- Revenue Stats -->
            <div class="space-y-3">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-3 h-3 rounded-full bg-indigo-500"></div>
                    <span class="text-xs font-bold text-slate-600 uppercase">Revenue Metrics</span>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <div
                        class="text-center p-2 rounded-lg bg-indigo-50 transition-all duration-300 hover:bg-indigo-100">
                        <span class="block text-[10px] font-bold text-slate-500 uppercase">Total</span>
                        <span class="block text-sm font-black text-indigo-700 mt-1" x-ref="revenueTotal"
                            x-text="combinedData.datasets[0].data.reduce((sum, item) => sum + item, 0) + 'k'"></span>
                    </div>
                    <div
                        class="text-center p-2 rounded-lg bg-indigo-50 transition-all duration-300 hover:bg-indigo-100">
                        <span class="block text-[10px] font-bold text-slate-500 uppercase">Average</span>
                        <span class="block text-sm font-black text-indigo-700 mt-1" x-ref="revenueAverage"
                            x-text="Math.round(combinedData.datasets[0].data.reduce((sum, item) => sum + item, 0) / combinedData.datasets[0].data.length) + 'k'"></span>
                    </div>
                    <div
                        class="text-center p-2 rounded-lg bg-indigo-50 transition-all duration-300 hover:bg-indigo-100">
                        <span class="block text-[10px] font-bold text-slate-500 uppercase">Peak</span>
                        <span class="block text-sm font-black text-indigo-700 mt-1" x-ref="revenuePeak"
                            x-text="Math.max(...combinedData.datasets[0].data) + 'k'"></span>
                    </div>
                </div>
            </div>

            <!-- Orders Stats -->
            <div class="space-y-3">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                    <span class="text-xs font-bold text-slate-600 uppercase">Orders Metrics</span>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <div
                        class="text-center p-2 rounded-lg bg-emerald-50 transition-all duration-300 hover:bg-emerald-100">
                        <span class="block text-[10px] font-bold text-slate-500 uppercase">Total</span>
                        <span class="block text-sm font-black text-emerald-700 mt-1" x-ref="ordersTotal"
                            x-text="combinedData.datasets[1].data.reduce((sum, item) => sum + item, 0)"></span>
                    </div>
                    <div
                        class="text-center p-2 rounded-lg bg-emerald-50 transition-all duration-300 hover:bg-emerald-100">
                        <span class="block text-[10px] font-bold text-slate-500 uppercase">Average</span>
                        <span class="block text-sm font-black text-emerald-700 mt-1" x-ref="ordersAverage"
                            x-text="Math.round(combinedData.datasets[1].data.reduce((sum, item) => sum + item, 0) / combinedData.datasets[1].data.length)"></span>
                    </div>
                    <div
                        class="text-center p-2 rounded-lg bg-emerald-50 transition-all duration-300 hover:bg-emerald-100">
                        <span class="block text-[10px] font-bold text-slate-500 uppercase">Peak</span>
                        <span class="block text-sm font-black text-emerald-700 mt-1" x-ref="ordersPeak"
                            x-text="Math.max(...combinedData.datasets[1].data)"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="bg-white p-6 rounded-[24px] border border-slate-100 shadow-sm flex flex-col">
        <h3 class="font-bold text-slate-900 text-lg mb-6 flex items-center gap-2">
            <i class="ri-map-pin-line text-indigo-500"></i> Top Wilayas
        </h3>
        <div class="flex-1 space-y-5 overflow-y-auto pr-2">
            <div>
                <div class="flex justify-between text-sm font-bold text-slate-700 mb-1">
                    <span>16 - Alger</span>
                    <span>45%</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2.5">
                    <div class="bg-slate-900 h-2.5 rounded-full" style="width: 45%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-sm font-bold text-slate-700 mb-1">
                    <span>31 - Oran</span>
                    <span>25%</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2.5">
                    <div class="bg-indigo-600 h-2.5 rounded-full" style="width: 25%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-sm font-bold text-slate-700 mb-1">
                    <span>25 - Constantine</span>
                    <span>15%</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2.5">
                    <div class="bg-indigo-400 h-2.5 rounded-full" style="width: 15%"></div>
                </div>
            </div>
        </div>
        <button
            class="mt-4 w-full py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">View
            Map</button>
    </div>
</div>

<div class="mb-8">
    <div class="mb-8">
        <h3 class="text-lg font-bold text-slate-900 mb-4 ml-1">Performance Cycles</h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <div x-data="{ 
        confirmed: { val: 1240, show: true, color: '#10b981', label: 'Confirmed' }, 
        cancelled: { val: 300, show: true, color: '#ef4444', label: 'Cancelled' }, 
        noAnswer: { val: 150, show: true, color: '#7bff00ff', label: 'No Answer' },
        reported: { val: 1240, show: true, color: '#3b82f6', label: 'Reported' }, 
        double: { val: 1240, show: true, color: '#f59e0b', label: 'Double' }, 
        falserate: { val: 1240, show: true, color: '#8b5cf6', label: 'False Rate' },
        
        getTotal() { 
            return (this.confirmed.show ? this.confirmed.val : 0) + 
                   (this.cancelled.show ? this.cancelled.val : 0) + 
                   (this.noAnswer.show ? this.noAnswer.val : 0) +
                   (this.reported.show ? this.reported.val : 0) +
                   (this.double.show ? this.double.val : 0) +
                   (this.falserate.show ? this.falserate.val : 0);
        },
        
        getAngles() {
            let total = this.getTotal();
            if (total === 0) return 'conic-gradient(#f1f5f9 0% 100%)';
            
            let p1 = this.confirmed.show ? (this.confirmed.val / total) * 100 : 0;
            let p2 = p1 + (this.cancelled.show ? (this.cancelled.val / total) * 100 : 0);
            let p3 = p2 + (this.noAnswer.show ? (this.noAnswer.val / total) * 100 : 0);
            let p4 = p3 + (this.reported.show ? (this.reported.val / total) * 100 : 0);
            let p5 = p4 + (this.double.show ? (this.double.val / total) * 100 : 0);
            
            return `conic-gradient(
                ${this.confirmed.color} 0% ${p1}%, 
                ${this.cancelled.color} ${p1}% ${p2}%, 
                ${this.noAnswer.color} ${p2}% ${p3}%,
                ${this.reported.color} ${p3}% ${p4}%,
                ${this.double.color} ${p4}% ${p5}%,
                ${this.falserate.color} ${p5}% 100%
            )`;
        },
        
        getTopReason() {
            let items = [
                this.confirmed.show ? { val: this.confirmed.val, label: this.confirmed.label, color: this.confirmed.color } : null,
                this.cancelled.show ? { val: this.cancelled.val, label: this.cancelled.label, color: this.cancelled.color } : null,
                this.noAnswer.show ? { val: this.noAnswer.val, label: this.noAnswer.label, color: this.noAnswer.color } : null,
                this.reported.show ? { val: this.reported.val, label: this.reported.label, color: this.reported.color } : null,
                this.double.show ? { val: this.double.val, label: this.double.label, color: this.double.color } : null,
                this.falserate.show ? { val: this.falserate.val, label: this.falserate.label, color: this.falserate.color } : null
            ].filter(item => item !== null);
            
            if (items.length === 0) return { label: 'No Data', color: '#94a3b8' };
            
            items.sort((a, b) => b.val - a.val);
            return { label: items[0].label, color: items[0].color };
        },
        
        init() {
            // Simulate data updates with staggered timing
            setInterval(() => {
                this.confirmed.val = Math.floor(Math.random() * 500) + 1000;
                setTimeout(() => {
                    this.cancelled.val = Math.floor(Math.random() * 200) + 200;
                }, 200);
                setTimeout(() => {
                    this.noAnswer.val = Math.floor(Math.random() * 100) + 100;
                }, 400);
                setTimeout(() => {
                    this.reported.val = Math.floor(Math.random() * 500) + 800;
                }, 600);
                setTimeout(() => {
                    this.double.val = Math.floor(Math.random() * 300) + 500;
                }, 800);
                setTimeout(() => {
                    this.falserate.val = Math.floor(Math.random() * 200) + 400;
                }, 1000);
            }, 5000);
        }
    }" class="group bg-white p-6 rounded-[24px] border border-slate-100 shadow-sm flex flex-col items-center hover:shadow-xl transition-all duration-500 w-full max-w-sm transform hover:-translate-y-1">

                <!-- Animated border effect -->
                <div
                    class="absolute inset-0 rounded-[24px]  opacity-0 group-hover:opacity-20 transition-opacity duration-500">
                </div>

                <!-- Header with refresh icon -->
                <div class="flex items-center justify-between w-full mb-4 relative z-10">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest animate-pulse">Confirmation
                    </h4>
                    <div
                        class="h-8 w-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 group-hover:text-indigo-500 transition-all duration-500 hover:rotate-180">
                        <i class="ri-refresh-line text-sm animate-spin-slow"></i>
                    </div>
                </div>

                <!-- Dynamic donut chart with enhanced animations -->
                <div class="relative w-40 h-40 rounded-full flex items-center justify-center transition-all duration-1000 hover:scale-110 hover:rotate-12"
                    :style="{ background: getAngles(), transform: 'rotate(-90deg)' }">

                    <!-- Animated rings -->
                    <div class="absolute inset-0 rounded-full border-2 border-slate-100 animate-ping opacity-20"></div>

                    <div class="absolute w-32 h-32 bg-white rounded-full flex flex-col items-center justify-center shadow-inner transition-all duration-500 hover:shadow-lg"
                        style="transform: rotate(90deg)">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Rate</span>
                        <span class="text-lg font-black mt-1 transition-all duration-500 animate-pulse"
                            :style="{ color: getTopReason().color }">Confirmation </span>
                        <div class="h-1.5 w-8 mt-2 rounded-full transition-all duration-500"
                            :style="{ backgroundColor: getTopReason().color }"></div>
                    </div>
                </div>

                <!-- Stats display with animations -->
                <div class="grid grid-cols-3 gap-2 w-full mt-6 px-4">
                    <template x-for="(item, key) in { confirmed: confirmed, cancelled: cancelled, noAnswer: noAnswer }">
                        <div class="text-center transition-all duration-500 cursor-pointer transform hover:scale-105"
                            @click="item.show = !item.show" :class="item.show ? 'opacity-100' : 'opacity-40 grayscale'">
                            <span class="block text-xs font-bold text-slate-400" x-text="item.label"></span>
                            <span class="block text-sm font-bold transition-all duration-500 animate-fade-in"
                                :style="{ color: item.color }" x-text="item.val.toLocaleString()"></span>
                        </div>
                    </template>
                </div>

                <!-- Second row of stats -->
                <div class="grid grid-cols-3 gap-2 w-full mt-2 px-4">
                    <template x-for="(item, key) in { reported: reported, double: double, falserate: falserate }">
                        <div class="text-center transition-all duration-500 cursor-pointer transform hover:scale-105"
                            @click="item.show = !item.show" :class="item.show ? 'opacity-100' : 'opacity-40 grayscale'">
                            <span class="block text-xs font-bold text-slate-400" x-text="item.label"></span>
                            <span class="block text-sm font-bold transition-all duration-500 animate-fade-in"
                                :style="{ color: item.color }" x-text="item.val.toLocaleString()"></span>
                        </div>
                    </template>
                </div>




                <!-- Footer with enhanced live indicator -->
                <div class="mt-6 pt-4 border-t border-slate-50 flex items-center justify-between w-full relative z-10">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest animate-pulse">Live
                        Data</span>
                    <div
                        class="flex items-center gap-2 bg-gradient-to-r from-green-50 to-emerald-50 px-3 py-1 rounded-full border border-green-200">
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-ping"></span>
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full absolute"></span>
                        <span class="text-[9px] font-bold text-green-700 uppercase">Updating</span>
                    </div>
                </div>
            </div>

            <style>
            .animate-spin-slow {
                animation: spin 4s linear infinite;
            }

            @keyframes spin {
                from {
                    transform: rotate(0deg);
                }

                to {
                    transform: rotate(360deg);
                }
            }

            @keyframes fade-in {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .animate-fade-in {
                animation: fade-in 0.5s ease-out;
            }

            /* Custom scrollbar */
            .scrollbar-thin::-webkit-scrollbar {
                width: 6px;
            }

            .scrollbar-thin::-webkit-scrollbar-track {
                background: #f1f5f9;
                border-radius: 3px;
            }

            .scrollbar-thin::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 3px;
            }

            .scrollbar-thin::-webkit-scrollbar-thumb:hover {
                background: #94a3b8;
            }
            </style>

            <div x-data="{ 
        confirmed: { val: 1240, show: true, color: '#10b981', label: 'Confirmed' }, 
        cancelled: { val: 300, show: true, color: '#ef4444', label: 'Cancelled' }, 
        noAnswer: { val: 150, show: true, color: '#7bff00ff', label: 'No Answer' },
        reported: { val: 1240, show: true, color: '#3b82f6', label: 'Reported' }, 
        double: { val: 1240, show: true, color: '#f59e0b', label: 'Double' }, 
        falserate: { val: 1240, show: true, color: '#8b5cf6', label: 'False Rate' },
        
        getTotal() { 
            return (this.confirmed.show ? this.confirmed.val : 0) + 
                   (this.cancelled.show ? this.cancelled.val : 0) + 
                   (this.noAnswer.show ? this.noAnswer.val : 0) +
                   (this.reported.show ? this.reported.val : 0) +
                   (this.double.show ? this.double.val : 0) +
                   (this.falserate.show ? this.falserate.val : 0);
        },
        
        getAngles() {
            let total = this.getTotal();
            if (total === 0) return 'conic-gradient(#f1f5f9 0% 100%)';
            
            let p1 = this.confirmed.show ? (this.confirmed.val / total) * 100 : 0;
            let p2 = p1 + (this.cancelled.show ? (this.cancelled.val / total) * 100 : 0);
            let p3 = p2 + (this.noAnswer.show ? (this.noAnswer.val / total) * 100 : 0);
            let p4 = p3 + (this.reported.show ? (this.reported.val / total) * 100 : 0);
            let p5 = p4 + (this.double.show ? (this.double.val / total) * 100 : 0);
            
            return `conic-gradient(
                ${this.confirmed.color} 0% ${p1}%, 
                ${this.cancelled.color} ${p1}% ${p2}%, 
                ${this.noAnswer.color} ${p2}% ${p3}%,
                ${this.reported.color} ${p3}% ${p4}%,
                ${this.double.color} ${p4}% ${p5}%,
                ${this.falserate.color} ${p5}% 100%
            )`;
        },
        
        getTopReason() {
            let items = [
                this.confirmed.show ? { val: this.confirmed.val, label: this.confirmed.label, color: this.confirmed.color } : null,
                this.cancelled.show ? { val: this.cancelled.val, label: this.cancelled.label, color: this.cancelled.color } : null,
                this.noAnswer.show ? { val: this.noAnswer.val, label: this.noAnswer.label, color: this.noAnswer.color } : null,
                this.reported.show ? { val: this.reported.val, label: this.reported.label, color: this.reported.color } : null,
                this.double.show ? { val: this.double.val, label: this.double.label, color: this.double.color } : null,
                this.falserate.show ? { val: this.falserate.val, label: this.falserate.label, color: this.falserate.color } : null
            ].filter(item => item !== null);
            
            if (items.length === 0) return { label: 'No Data', color: '#94a3b8' };
            
            items.sort((a, b) => b.val - a.val);
            return { label: items[0].label, color: items[0].color };
        },
        
        init() {
            // Simulate data updates with staggered timing
            setInterval(() => {
                this.confirmed.val = Math.floor(Math.random() * 500) + 1000;
                setTimeout(() => {
                    this.cancelled.val = Math.floor(Math.random() * 200) + 200;
                }, 200);
                setTimeout(() => {
                    this.noAnswer.val = Math.floor(Math.random() * 100) + 100;
                }, 400);
                setTimeout(() => {
                    this.reported.val = Math.floor(Math.random() * 500) + 800;
                }, 600);
                setTimeout(() => {
                    this.double.val = Math.floor(Math.random() * 300) + 500;
                }, 800);
                setTimeout(() => {
                    this.falserate.val = Math.floor(Math.random() * 200) + 400;
                }, 1000);
            }, 5000);
        }
    }" class="group bg-white p-6 rounded-[24px] border border-slate-100 shadow-sm flex flex-col items-center hover:shadow-xl transition-all duration-500 w-full max-w-sm transform hover:-translate-y-1">

                <!-- Animated border effect -->
                <div
                    class="absolute inset-0 rounded-[24px]  opacity-0 group-hover:opacity-20 transition-opacity duration-500">
                </div>

                <!-- Header with refresh icon -->
                <div class="flex items-center justify-between w-full mb-4 relative z-10">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest animate-pulse">Confirmation
                    </h4>
                    <div
                        class="h-8 w-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 group-hover:text-indigo-500 transition-all duration-500 hover:rotate-180">
                        <i class="ri-refresh-line text-sm animate-spin-slow"></i>
                    </div>
                </div>

                <!-- Dynamic donut chart with enhanced animations -->
                <div class="relative w-40 h-40 rounded-full flex items-center justify-center transition-all duration-1000 hover:scale-110 hover:rotate-12"
                    :style="{ background: getAngles(), transform: 'rotate(-90deg)' }">

                    <!-- Animated rings -->
                    <div class="absolute inset-0 rounded-full border-2 border-slate-100 animate-ping opacity-20"></div>

                    <div class="absolute w-32 h-32 bg-white rounded-full flex flex-col items-center justify-center shadow-inner transition-all duration-500 hover:shadow-lg"
                        style="transform: rotate(90deg)">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Rate</span>
                        <span class="text-lg font-black mt-1 transition-all duration-500 animate-pulse"
                            :style="{ color: getTopReason().color }">Confirmation </span>
                        <div class="h-1.5 w-8 mt-2 rounded-full transition-all duration-500"
                            :style="{ backgroundColor: getTopReason().color }"></div>
                    </div>
                </div>

                <!-- Stats display with animations -->
                <div class="grid grid-cols-3 gap-2 w-full mt-6 px-4">
                    <template x-for="(item, key) in { confirmed: confirmed, cancelled: cancelled, noAnswer: noAnswer }">
                        <div class="text-center transition-all duration-500 cursor-pointer transform hover:scale-105"
                            @click="item.show = !item.show" :class="item.show ? 'opacity-100' : 'opacity-40 grayscale'">
                            <span class="block text-xs font-bold text-slate-400" x-text="item.label"></span>
                            <span class="block text-sm font-bold transition-all duration-500 animate-fade-in"
                                :style="{ color: item.color }" x-text="item.val.toLocaleString()"></span>
                        </div>
                    </template>
                </div>

                <!-- Second row of stats -->
                <div class="grid grid-cols-3 gap-2 w-full mt-2 px-4">
                    <template x-for="(item, key) in { reported: reported, double: double, falserate: falserate }">
                        <div class="text-center transition-all duration-500 cursor-pointer transform hover:scale-105"
                            @click="item.show = !item.show" :class="item.show ? 'opacity-100' : 'opacity-40 grayscale'">
                            <span class="block text-xs font-bold text-slate-400" x-text="item.label"></span>
                            <span class="block text-sm font-bold transition-all duration-500 animate-fade-in"
                                :style="{ color: item.color }" x-text="item.val.toLocaleString()"></span>
                        </div>
                    </template>
                </div>




                <!-- Footer with enhanced live indicator -->
                <div class="mt-6 pt-4 border-t border-slate-50 flex items-center justify-between w-full relative z-10">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest animate-pulse">Live
                        Data</span>
                    <div
                        class="flex items-center gap-2 bg-gradient-to-r from-green-50 to-emerald-50 px-3 py-1 rounded-full border border-green-200">
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-ping"></span>
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full absolute"></span>
                        <span class="text-[9px] font-bold text-green-700 uppercase">Updating</span>
                    </div>
                </div>
            </div>

            <style>
            .animate-spin-slow {
                animation: spin 4s linear infinite;
            }

            @keyframes spin {
                from {
                    transform: rotate(0deg);
                }

                to {
                    transform: rotate(360deg);
                }
            }

            @keyframes fade-in {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .animate-fade-in {
                animation: fade-in 0.5s ease-out;
            }

            /* Custom scrollbar */
            .scrollbar-thin::-webkit-scrollbar {
                width: 6px;
            }

            .scrollbar-thin::-webkit-scrollbar-track {
                background: #f1f5f9;
                border-radius: 3px;
            }

            .scrollbar-thin::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 3px;
            }

            .scrollbar-thin::-webkit-scrollbar-thumb:hover {
                background: #94a3b8;
            }
            </style>

            <div
                class="pointer-events-none grayscale-50 opacity-50 bg-white p-6 rounded-[24px] border border-slate-100 shadow-sm flex flex-col items-center hover:shadow-md transition">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Failure Reasons</h4>

                <div class="relative w-40 h-40 rounded-full flex items-center justify-center transition-transform hover:scale-105 duration-500"
                    style="background: conic-gradient(#ef4444 0% 40%, #f97316 40% 70%, #94a3b8 70% 100%);">
                    <div class="absolute w-32 h-32 bg-white rounded-full flex flex-col items-center justify-center">
                        <span class="text-xs font-bold text-slate-500 uppercase">Top Reason</span>
                        <span class="text-lg font-black text-slate-900 mt-1">No Answer</span>
                    </div>
                </div>

                <div class="flex flex-col w-full mt-4 space-y-2 px-2">
                    <div class="flex justify-between items-center text-xs">
                        <span class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-red-500"></span> No
                            Answer</span>
                        <span class="font-bold">40%</span>
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <span class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-orange-500"></span>
                            Canceled</span>
                        <span class="font-bold">30%</span>
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <span class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-slate-400"></span>
                            Wrong
                            #</span>
                        <span class="font-bold">30%</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

    <div class="bg-white p-6 rounded-[24px] border border-slate-100 shadow-sm">
        <h3 class="font-bold text-slate-900 text-lg mb-6">Pipeline Flow</h3>
        <div class="space-y-1">
            <div class="relative group">
                <div
                    class="flex items-center justify-between px-4 py-3 bg-slate-50 rounded-lg border-l-4 border-blue-600 z-10 relative">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-blue-600 shadow-sm">
                            <i class="ri-shopping-cart-2-line"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase">Confirmed</p>
                            <p class="text-sm font-bold text-slate-900">1,500 Orders</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-slate-400">100%</span>
                </div>
            </div>
            <div class="flex justify-center -my-2 relative z-0"><i class="ri-arrow-down-line text-slate-300"></i></div>
            <div class="relative group">
                <div
                    class="flex items-center justify-between px-4 py-3 bg-slate-50 rounded-lg border-l-4 border-indigo-500 z-10 relative w-[95%] mx-auto">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-indigo-500 shadow-sm">
                            <i class="ri-truck-line"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase">Shipped</p>
                            <p class="text-sm font-bold text-slate-900">1,420 Orders</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-slate-400">94%</span>
                </div>
            </div>
            <div class="flex justify-center -my-2 relative z-0"><i class="ri-arrow-down-line text-slate-300"></i></div>
            <div class="relative group">
                <div
                    class="flex items-center justify-between px-4 py-3 bg-green-50 rounded-lg border-l-4 border-green-500 z-10 relative w-[90%] mx-auto">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-green-600 shadow-sm">
                            <i class="ri-home-smile-fill"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-green-700 uppercase">Delivered</p>
                            <p class="text-sm font-bold text-slate-900">1,200 Orders</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-green-700">80%</span>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-[24px] border border-slate-100 shadow-sm">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-slate-900 text-lg">Inventory Movers</h3>
            <a href="#" class="text-xs font-bold text-indigo-600 hover:underline">View All</a>
        </div>

        <div class="space-y-4">
            <div
                class="flex items-center gap-4 p-3 hover:bg-slate-50 rounded-2xl transition group cursor-pointer border border-transparent hover:border-slate-100">
                <div class="w-14 h-14 bg-gray-100 rounded-xl overflow-hidden relative">
                    <div class="w-full h-full bg-slate-200 flex items-center justify-center text-xl">👟</div>
                    <div class="absolute top-0 right-0 bg-yellow-400 text-[8px] font-bold px-1.5 py-0.5 rounded-bl-lg">
                        #1</div>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-bold text-slate-900 group-hover:text-indigo-600">Nike Air Force 1</h4>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-[10px] font-bold text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded">SKU:
                            NK-001</span>
                        <span class="text-[10px] text-green-600 font-bold">In Stock</span>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm font-black text-slate-900">450</p>
                    <p class="text-[10px] text-slate-400 font-bold uppercase">Sold</p>
                </div>
            </div>

            <div
                class="flex items-center gap-4 p-3 hover:bg-slate-50 rounded-2xl transition group cursor-pointer border border-transparent hover:border-slate-100">
                <div class="w-14 h-14 bg-gray-100 rounded-xl overflow-hidden relative">
                    <div class="w-full h-full bg-slate-200 flex items-center justify-center text-xl">⌚</div>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-bold text-slate-900 group-hover:text-indigo-600">Smart Watch Ultra</h4>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-[10px] font-bold text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded">SKU:
                            SW-99</span>
                        <span class="text-[10px] text-orange-500 font-bold">Low Stock</span>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm font-black text-slate-900">210</p>
                    <p class="text-[10px] text-slate-400 font-bold uppercase">Sold</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection