<?php

namespace App\Livewire\Agent;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
use App\Models\order_logs;
use App\Models\firstStepStatu;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AgentWorkspace extends Component
{
    public $selectedDate;
    public $selectedProduct = null;
    public $products;
    public $start_date;
    public $end_date;
    public $orderStats = [];
    public $performanceData = [];
    public $agentRank;
    public $dailyGoal;
    public $dailyProgress;
    public $selectedProductDisplayName = 'All Products';
    public $statusOptions;
    public $stores;
    public $selectedStore;
    protected $listeners = ['refreshComponent' => '$refresh'];
    
    public function mount()
    {
        $this->selectedDate = Carbon::today()->format('Y-m-d');
        $this->products = Product::whereHas('agents', function ($query) {
            $query->where('aid', auth()->id());
        })->get();
        $this->statusOptions = firstStepStatu::all();
        $this->dailyGoal = 100;
        $this->loadData();
    }
    
    public function updatedSelectedProduct()
    {
        if ($this->selectedProduct) {
            $product = $this->products->where('sku', $this->selectedProduct)->first();
            $this->selectedProductDisplayName = $product ? $product->name : 'All Products';
        } else {
            $this->selectedProductDisplayName = 'All Products';
        }
        
        $this->loadData();
    }
    public function updatedSelectedStore()
    {
        
        $this->loadData();
    }

    public function updatedSelectedDate()
    {
        // When a single date is selected, clear the range
        $this->start_date = null;
        $this->end_date = null;
        $this->loadData();
    }

    public function updatedStartDate()
    {
        // When a start date is set, clear the single date selector
        $this->selectedDate = null;
        $this->loadData();
    }

    public function updatedEndDate()
    {
        // When an end date is set, clear the single date selector
        $this->selectedDate = null;
        $this->loadData();
    }
    
    public function loadData()
    {
        $userId = auth()->id();
        $query = Order::where('aid', $userId);

        // --- THIS IS THE KEY FIX ---
        // Determine if we are using a range or a single date
        if ($this->start_date && $this->end_date) {
            // Use the date range
            $startDate = Carbon::parse($this->start_date)->startOfDay();
            $endDate = Carbon::parse($this->end_date)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($this->selectedDate) {
            // Use the single selected date
            $date = Carbon::parse($this->selectedDate);
            $query->whereDate('created_at', $date);
        } else {
            // If no date is set, return empty data
            $this->resetStats();
            return;
        }
        
        if ($this->selectedProduct) {
            $query->whereHas('items', function($q) {
                $q->where('sku', $this->selectedProduct);
            });
        }
        if ($this->selectedStore) {
            $query->with('AgentStores', function($q) {
                $q->where('sid', $this->selectedStore);
            });
        }
        
        $orders = $query->get();
        
        // Initialize stats counters - keeping the original structure
        $stats = [
            'total' => $orders->count(),
            'confirmed' => 0,
            'cancelled' => 0,
            'in_process' => 0,
            'delivered' => 0,
            'returned' => 0,
        ];
        
        // Initialize performance data - keeping the original structure
        $performance = [
            'confirmed' => 0,
            'cancelled' => 0,
            'no_answer' => 0,
            'pending' => 0,
            'reported' => 0,
            'double' => 0,
            'no_deliv' => 0,
            'false_rate' => 0,
            'pre-confirmed'=> 0,
        ];

        // Get order status counts based on the LAST LOG of the day/range
        foreach ($orders as $order) {
            // Get the latest status from order_logs for the specific day
            $latestLog = order_logs::where('oid', $order->oid)
                ->when($this->start_date && $this->end_date, function($q) {
                    // If using a range, find the latest log within that range
                    $startDate = Carbon::parse($this->start_date)->startOfDay();
                    $endDate = Carbon::parse($this->end_date)->endOfDay();
                    $q->whereBetween('created_at', [$startDate, $endDate]);
                })
                ->when($this->selectedDate, function($q) {
                    // If using a single date, find the latest log for that day
                    $date = Carbon::parse($this->selectedDate);
                    $q->whereDate('created_at', $date);
                })
                ->with('statusNew')
                ->latest()
                ->first();
                
            if ($latestLog && $latestLog->statusNew) {
                $statusId = $latestLog->statusNew->fsid;
                
                // Update stats based on status ID to match your new status options
                switch ($statusId) {
                    case 1: // pending
                         $performance['pending']++;
                        break;
                    case 2: // confirmed
                        $stats['confirmed']++;
                        $performance['confirmed']++;
                        break;
                    case 3: // pre-confirmed
                        $stats['in_process']++;
                        $performance['pre-confirmed']++;
                        break;
                    case 4: // reported
                        $performance['reported']++;
                        $stats['in_process']++;
                        break;
                    case 5: // cancelled
                        $stats['cancelled']++;
                        $performance['cancelled']++;
                        break;
                    case 6: // NA/1
                    case 7: // NA/2
                    case 8: // NA/3
                    case 9: // NA/4
                    case 10: // NA/5
                        $performance['no_answer']++;
                        $stats['in_process']++;
                        break;
                    case 11: // closed
                        $performance['no_answer']++;
                        $stats['in_process']++;
                        break;
                    case 12: // no delivery
                        $performance['no_deliv']++;
                        break;
                    case 13: // double
                        $performance['double']++;
                        break;
                    case 14: // false order
                        $performance['false_rate']++;
                        break;
                    case 15: // false number
                        $performance['false_rate']++;

                        break;
                    case 16: // test
                     
                        break;
                }
            }
        }
        
        $this->orderStats = $stats;
        $this->performanceData = $performance;
        
        // Get agent rank and daily progress
        $this->agentRank = $this->getAgentRank($userId);
        $this->dailyProgress = $this->orderStats['total'];
    }
    public function updatedStoreId($value)
{
    $this->loadAvailableProducts();
    // إرسال إشارة للجميع أن المتجر تغير
    $this->dispatch('refresh-orders', storeId: $value);
}
    private function resetStats()
    {
        $this->orderStats = [
            'total' => 0, 'confirmed' => 0, 'cancelled' => 0, 
            'in_process' => 0, 'delivered' => 0, 'returned' => 0
        ];
        $this->performanceData = [
            'confirmed' => 0, 'cancelled' => 0, 'no_answer' => 0, 'pending' =>0,
            'reported' => 0, 'double' => 0,'no_deliv'=>0, 'false_rate' => 0 ,'pre-confirmed'=>0
        ];
        $this->agentRank = 0;
        $this->dailyProgress = 0;
    }
    
    private function getAgentRank($userId)
    {
        $query = Order::select('aid', DB::raw('count(*) as order_count'))
            ->groupBy('aid')
            ->orderBy('order_count', 'desc');

        // Apply the same date filtering logic
        if ($this->start_date && $this->end_date) {
            $startDate = Carbon::parse($this->start_date)->startOfDay();
            $endDate = Carbon::parse($this->end_date)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($this->selectedDate) {
            $date = Carbon::parse($this->selectedDate);
            $query->whereDate('created_at', $date);
        } else {
            return 0; // No date filter, cannot determine rank
        }
            
        $agentCounts = $query->get();
        
        $rank = 1;
        foreach ($agentCounts as $agent) {
            if ($agent->aid == $userId) {
                return $rank;
            }
            $rank++;
        }
        
        return 0;
    }
    
    
    public function render()
    {
        $this->stores = Auth::user()->AgentStores()->get(); 
        return view('livewire.agent.agent-workspace');
    }
}