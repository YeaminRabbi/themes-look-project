<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $products = Product::count();
        $orders = Order::count();
        $totalCost = number_format(ProductAttribute::sum('purchase_price') , 2);
        $totalSell = number_format(OrderItem::sum('total_price') , 2);

        $allMonths = [];
        $currentYear = date('Y');

        for ($month = 1; $month <= 12; $month++) {
            $allMonths[] = sprintf("%04d-%02d", $currentYear, $month);
        }
        
        $monthlySalesData = OrderItem::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total_price) as total_sales')
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Initialize arrays for months and sales
        $months = [];
        $sales = [];

        // Fill in sales data for each month
        foreach ($allMonths as $month) {
            // Find sales data for the current month
            $salesData = $monthlySalesData->firstWhere('month', $month);

            if ($salesData) {
                // If sales data exists for the month, add it to the arrays
                $months[] = $month;
                $sales[] = round($salesData->total_sales, 2);
            } else {
                // If no sales data for the month, add zero or null
                $months[] = $month;
                $sales[] = 0; // You can set to null or zero based on your preference
            }
        }
         // Fetch monthly sales data
        // $monthlySales = OrderItem::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total_price) as total_sales')
        //     ->groupBy('month')
        //     ->orderBy('month')
        //     ->get();

        // // Prepare arrays for months and sales values
        // $months = [];
        // $sales = [];
        // foreach ($monthlySales as $salesData) {
        //     $months[] = $salesData->month;
        //     $sales[] = round($salesData->total_sales, 2);
        // }

        // return view('home', compact('products', 'orders', 'totalCost', 'totalSell', 'months', 'sales'));
        return view('home', compact('products', 'orders', 'totalCost', 'totalSell', 'months', 'sales'));

        //  // Fetch monthly sales data
        // $monthlySales = OrderItem::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(total_price) as total_sales')
        //     ->groupBy('month', 'year')
        //     ->orderBy('year')
        //     ->orderBy('month')
        //     ->get();

        // // Prepare arrays for months and sales values
        // $months = [];
        // $sales = [];
        // foreach ($monthlySales as $salesData) {
        //     $monthYear = date('M Y', strtotime($salesData->year . '-' . $salesData->month . '-01'));
        //     $months[] = $monthYear;
        //     $sales[] = round($salesData->total_sales, 2);
        // }

        // return view('home', compact('products', 'orders', 'totalCost', 'totalSell', 'months', 'sales'));
        // return view('home', compact('products', 'orders', 'totalCost'));
    }
}
