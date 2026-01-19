<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Category;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function dashboard()
    {
        if (!userCan('view dashboard')) {
            abort(403);
        }

        $data['total_orders'] = Order::count();
        $data['total_products'] = Product::active()->count();
        $data['total_customers'] = User::where('is_affiliate', false)->count();
        $data['total_categories'] = Category::count();
        $data['stats'] = $this->orderStats(request('stats') ?? 'this_week');

        $statuses = Order::statusList('', true);
        $order_status_with_orders = [];
        foreach ($statuses as $key => $status) {
            $order_count = Order::where('status', $key)->count();
            $status = $status;
            $color = Order::BStatusColor($key);

            array_push($order_status_with_orders, [
                'order_count' => $order_count,
                'status' => $status,
                'color' => $color,
                'link' => route('admin.order.index', ['keyword' => $status]),
            ]);
        }

        $sales = Order::sum('total');

        return view('admin.pages.dashboard', $data, compact('order_status_with_orders', 'sales'));
    }

    public function orderStats($filter = 'this_week')
    {
        $today = Carbon::today();

        switch ($filter) {
            case 'this_week':
                $orders = DB::table('orders')
                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
                    ->where('created_at', '>=', Carbon::now()->subDays(6)->startOfDay())
                    ->groupBy(DB::raw('DATE(created_at)'))
                    ->orderBy('date', 'ASC')
                    ->get();

                $dates = [];
                $counts = [];

                for ($i = 6; $i >= 0; $i--) {
                    $date = $today->copy()->subDays($i);
                    $formattedDate = $date->format('M d');
                    $dates[] = $formattedDate;

                    $order = $orders->firstWhere('date', $date->format('Y-m-d'));
                    $counts[] = $order ? $order->count : 0;
                }
                break;

            case 'this_month':
                $orders = DB::table('orders')
                    ->select(DB::raw('WEEK(created_at, 1) as week'), DB::raw('YEAR(created_at) as year'), DB::raw('COUNT(*) as count'))
                    ->whereMonth('created_at', $today->month)
                    ->whereYear('created_at', $today->year)
                    ->groupBy(DB::raw('WEEK(created_at, 1), YEAR(created_at)'))
                    ->orderBy('year', 'ASC')
                    ->orderBy('week', 'ASC')
                    ->get();

                $dates = [];
                $counts = [];
                $count = 1;

                foreach ($orders as $order) {
                    $dates[] = "Week " . $count;
                    $counts[] = $order->count;

                    $count = $count + 1;
                }
                break;

            case 'last_month':
                $orders = DB::table('orders')
                    ->select(DB::raw('WEEK(created_at, 1) as week'), DB::raw('YEAR(created_at) as year'), DB::raw('COUNT(*) as count'))
                    ->whereMonth('created_at', $today->subMonth())
                    ->whereYear('created_at', $today->year)
                    ->groupBy(DB::raw('WEEK(created_at, 1), YEAR(created_at)'))
                    ->orderBy('year', 'ASC')
                    ->orderBy('week', 'ASC')
                    ->get();

                $dates = [];
                $counts = [];
                $count = 1;

                foreach ($orders as $order) {
                    $dates[] = "Week " . $count;
                    $counts[] = $order->count;

                    $count = $count + 1;
                }
                break;

            case 'last_6_months':
                $orders = DB::table('orders')
                    ->select(DB::raw('MONTH(created_at) as month'), DB::raw('YEAR(created_at) as year'), DB::raw('COUNT(*) as count'))
                    ->where('created_at', '>=', $today->copy()->subMonths(5)->startOfMonth())
                    ->groupBy(DB::raw('MONTH(created_at), YEAR(created_at)'))
                    ->orderBy('year', 'ASC')
                    ->orderBy('month', 'ASC')
                    ->get();

                $dates = [];
                $counts = [];

                foreach ($orders as $order) {
                    $dates[] = Carbon::createFromDate($order->year, $order->month, 1)->format('M Y');
                    $counts[] = $order->count;
                }
                break;

            case 'last_year':
                $orders = DB::table('orders')
                    ->select(DB::raw('MONTH(created_at) as month'), DB::raw('YEAR(created_at) as year'), DB::raw('COUNT(*) as count'))
                    ->whereYear('created_at', $today->copy()->subYear()->year)
                    ->groupBy(DB::raw('MONTH(created_at), YEAR(created_at)'))
                    ->orderBy('year', 'ASC')
                    ->orderBy('month', 'ASC')
                    ->get();

                $dates = [];
                $counts = [];

                foreach ($orders as $order) {
                    $dates[] = Carbon::createFromDate($order->year, $order->month, 1)->format('M Y');
                    $counts[] = $order->count;
                }
                break;

            default:
                return response()->json(['error' => 'Invalid filter'], 400);
        }

        return [$dates, $counts];
    }
}
