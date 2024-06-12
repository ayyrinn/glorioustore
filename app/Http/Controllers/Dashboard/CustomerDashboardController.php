<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;


class CustomerDashboardController extends Controller
{
    /**
     * Display the dashboard for customers.
     */

     public function index(Request $request)
     {
         $search = $request->input('search');
         $category = $request->input('category');
 
         $products = Product::query()
             ->when($search, function ($query, $search) {
                 return $query->where('productname', 'like', '%' . $search . '%');
             })
             ->when($category && $category !== 'Semua', function ($query) use ($category) {
                 return $query->whereHas('category', function ($query) use ($category) {
                     $query->where('name', $category);
                 });
             })
             ->get(); 
     
         return view('customerdashboard.index', ['products' => $products]);
     }

     public function getProductsByCategory(Request $request)
    {
        $categoryName = $request->query('category', 'Semua');
        $searchQuery = $request->query('search', '');

        // Mapping of general categories to specific categories in the database
        $categoryMapping = [
            'Makanan' => ['mie', 'gula', 'beras', 'minyak', 'camilan', 'Bahan Kue dan Roti'],
            'Minuman' => ['kopi', 'teh'],
            'Home and Living' => ['detergen', 'tissue', 'Insektisida Rumah Tangga'],
            'Personal Care' => ['sabun', 'shampoo', 'sikat gigi', 'pasta gigi'],
            'Kesehatan' => ['vitamin', 'obat-obatan'],
            'Rokok' => ['rokok'],
            'Produk lainnya' => ['lainnya']
        ];

        $query = Product::query();

        // Apply category filter
        if ($categoryName !== 'Semua') {
            $specificCategories = $categoryMapping[$categoryName] ?? [];
            if (!empty($specificCategories)) {
                $query->whereHas('category', function($q) use ($specificCategories) {
                    $q->whereIn('name', $specificCategories);
                });
            } else {
                $query->where('category_id', -1); // Invalid category
            }
        }

        // Apply search filter
        if (!empty($searchQuery)) {
            $query->where('productname', 'LIKE', '%' . $searchQuery . '%');
        }

        $products = $query->paginate(10);

        return view('customerdashboard.index', compact('products', 'categoryName', 'searchQuery'));
    }
}
