<?php

namespace App\Http\Controllers;

use App\Models\Product;

use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
            ->where('qty', '>', 0)
            ->get();

        return view('user.dashboard', compact('products'));
    }
}
