<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\ProductsImportCollection;
use Maatwebsite\Excel\Facades\Excel;

class ProductImportController extends Controller
{
        public function showImportForm()
    {
        return view('products.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new ProductsImportCollection, $request->file('file'));
        return redirect()->back()->with('success', 'Products imported successfully!');
    }
}
