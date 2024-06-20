<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Transaction;
use App\Models\TransactionDetails;
use App\Models\OnlineTransaction;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ProfitView;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->input('period', 'week');

        // Fetch order details (purchase data)
        switch ($period) {
            case 'week':
                $orderDetails = OrderDetails::select(
                        DB::raw('DATE(created_at) as date'),
                        DB::raw('SUM(subtotal) as total_purchase')
                    )
                    ->groupBy(DB::raw('DATE(created_at)'))
                    ->get();
                break;
            case 'month':
                $orderDetails = OrderDetails::select(
                        DB::raw('DATE_FORMAT(created_at, "%Y-%m") as date'),
                        DB::raw('SUM(subtotal) as total_purchase')
                    )
                    ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'))
                    ->get();
                break;
            case 'year':
                $orderDetails = OrderDetails::select(
                        DB::raw('YEAR(created_at) as date'),
                        DB::raw('SUM(subtotal) as total_purchase')
                    )
                    ->groupBy(DB::raw('YEAR(created_at)'))
                    ->get();
                break;
            default:
                // Default to monthly data if invalid period is provided
                $orderDetails = OrderDetails::select(
                        DB::raw('DATE(created_at) as date'),
                        DB::raw('SUM(subtotal) as total_purchase')
                    )
                    ->groupBy(DB::raw('DATE(created_at)'))
                    ->get();
                break;
        }
        // Fetch transaction details (sales data)
        $transactionDetails = TransactionDetails::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total) as total_sales')
        )
        ->groupBy(DB::raw('DATE(created_at)'))
        ->get();

        // Combine data and calculate profit
        $profitData = [];
        foreach ($orderDetails as $order) {
            $date = Carbon::parse($order->date);
            $totalPurchase = $order->total_purchase;
            $totalSales = $transactionDetails->firstWhere('date', $date->toDateString())->total_sales ?? 0;
            $profit = $totalSales - $totalPurchase;

            $profitData[] = [
                'date' => $date->toDateString(),
                'profit' => $profit,
            ];
        }


        switch ($period) {
            case 'week':
                $data = DB::table('weekly_top_products')
                        ->join('products', 'weekly_top_products.productid', '=', 'products.productid')
                        ->get();
                break;
            case 'month':
                $data = DB::table('monthly_top_products')
                        ->join('products', 'monthly_top_products.productid', '=', 'products.productid')
                        ->get();
                break;
            case 'year':
                $data = DB::table('yearly_top_products')
                        ->join('products', 'yearly_top_products.productid', '=', 'products.productid')
                        ->get();
                break;
            default:
                // Default to monthly data if invalid period is provided
                $data = DB::table('weekly_top_products')
                        ->join('products', 'weekly_top_products.productid', '=', 'products.productid')
                        ->get();
                break;
        }

        // Data for transactions profit based on the selected period
        $dailyProfits = DB::table('transactions')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as total'))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->get();

        $monthlyProfits = DB::table('transactions')
            ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as date'), DB::raw('SUM(total) as total'))
            ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'))
            ->get();

        $yearlyProfits = DB::table('transactions')
            ->select(DB::raw('YEAR(created_at) as date'), DB::raw('SUM(total) as total'))
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->get();

        return view('dashboard.index', [
            'total_paid' => Transaction::sum('total'),
            'total_orders' => Transaction::count(),
            'total_pending' => OnlineTransaction::where('status', '<>', 'DITERIMA')->count(),
            'total_pendingorder' => Order::where('status', '<>', 'DITERIMA')->count(),
            'products' => Product::orderBy('stock')->take(5)->get(),
            'new_products' => Product::orderByDesc('productid')->take(2)->get(),
            'profits' => ProfitView::all(),
            'data' => $data,
            'selectedPeriod' => $period,
            'dailyProfits' => $dailyProfits,
            'monthlyProfits' => $monthlyProfits,
            'yearlyProfits' => $yearlyProfits,
            'profitData' => $profitData,
        ]);
    }
}


