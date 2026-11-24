<?php

namespace App\Http\Controllers;

use App\Models\Movement;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'byCategory' => Product::select('category', DB::raw('SUM(stock) as total'))
                ->groupBy('category')->orderBy('category')->get(),
            'products' => Product::orderBy('name')->get(),
            'latest' => Movement::with('product')->latest('id')->take(5)->get(),
        ]);
    }
}
