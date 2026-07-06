<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Thống kê cơ bản
        $totalRevenue = Order::where('status', 'completed')->sum('total_amount');
        $newOrdersCount = Order::count();
        $totalCustomers = User::where('role', '!=', 'admin')->count();
        $totalProducts = Product::count();

        // Đơn hàng gần đây
        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Sản phẩm bán chạy (dựa trên order_items của đơn hàng hoàn thành)
        $topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->whereHas('order', function ($query) {
                $query->where('status', 'completed');
            })
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->with(['product' => function($q) {
                $q->select('id', 'name', 'price', 'slug')->with(['images' => function($q2) {
                    $q2->where('is_primary', true);
                }]);
            }])
            ->get();

        return Inertia::render('Admin/Dashboard', [
            'totalRevenue' => $totalRevenue,
            'newOrdersCount' => $newOrdersCount,
            'totalCustomers' => $totalCustomers,
            'totalProducts' => $totalProducts,
            'recentOrders' => $recentOrders,
            'topProducts' => $topProducts,
        ]);
    }

    public function chartData(Request $request)
    {
        $range = $request->query('range', '7_days');
        
        $startDate = Carbon::today();
        $endDate = Carbon::today();
        $groupByFormat = '%Y-%m-%d';
        $interval = 'day';

        switch ($range) {
            case '7_days':
                $startDate = Carbon::today()->subDays(6);
                break;
            case '30_days':
                $startDate = Carbon::today()->subDays(29);
                break;
            case '3_months':
                $startDate = Carbon::today()->subMonths(3);
                $groupByFormat = '%Y-%m'; // Gộp theo tháng
                $interval = 'month';
                break;
            case '6_months':
                $startDate = Carbon::today()->subMonths(6);
                $groupByFormat = '%Y-%m';
                $interval = 'month';
                break;
            case '9_months':
                $startDate = Carbon::today()->subMonths(9);
                $groupByFormat = '%Y-%m';
                $interval = 'month';
                break;
            case '1_year':
                $startDate = Carbon::today()->subYear();
                $groupByFormat = '%Y-%m';
                $interval = 'month';
                break;
            default:
                $startDate = Carbon::today()->subDays(6);
        }

        // 1. Biểu đồ Doanh thu (Hoàn thành)
        $revenueQuery = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()]);
            
        // MySQL group by date
        $revenueData = $revenueQuery->select(
                DB::raw("DATE_FORMAT(created_at, '{$groupByFormat}') as date"),
                DB::raw("SUM(total_amount) as total")
            )
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->keyBy('date');

        // Tạo chuỗi ngày/tháng liên tục để biểu đồ không bị đứt quãng
        $labels = [];
        $revenueValues = [];
        
        if ($interval === 'day') {
            $currentDate = clone $startDate;
            while ($currentDate <= $endDate) {
                $dateStr = $currentDate->format('Y-m-d');
                $labels[] = $currentDate->format('d/m');
                $revenueValues[] = isset($revenueData[$dateStr]) ? $revenueData[$dateStr]->total : 0;
                $currentDate->addDay();
            }
        } else {
            $currentDate = clone $startDate;
            $currentDate->startOfMonth();
            while ($currentDate <= $endDate) {
                $dateStr = $currentDate->format('Y-m');
                $labels[] = 'T' . $currentDate->format('m/Y');
                $revenueValues[] = isset($revenueData[$dateStr]) ? $revenueData[$dateStr]->total : 0;
                $currentDate->addMonth();
            }
        }

        // 2. Biểu đồ Trạng thái đơn hàng
        $statusData = Order::whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();
            
        $statusLabels = $statusData->pluck('status');
        $statusValues = $statusData->pluck('count');

        return response()->json([
            'revenue' => [
                'labels' => $labels,
                'data' => $revenueValues
            ],
            'orderStatus' => [
                'labels' => $statusLabels,
                'data' => $statusValues
            ]
        ]);
    }
}
