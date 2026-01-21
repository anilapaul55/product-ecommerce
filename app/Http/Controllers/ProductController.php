<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Category;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Http\Request;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
public function index()
    {
        $products = Product::with(['category', 'color', 'size'])->latest()->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $colors = Color::all();
        $sizes = Size::all();

        return view('products.create', compact('categories', 'colors', 'sizes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id'=> 'required|exists:categories,id',
            'color_id'   => 'required|exists:colors,id',
            'size_id'    => 'required|exists:sizes,id',
            'qty'        => 'required|integer|min:0',
            'price'      => 'required|numeric|min:0',
            'image'      => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imagePath = $this->uploadWebp($request->file('image'));

        Product::create([
            'name'        => $request->name,
            'category_id'=> $request->category_id,
            'color_id'   => $request->color_id,
            'size_id'    => $request->size_id,
            'qty'        => $request->qty,
            'price'      => $request->price,
            'image'      => $imagePath,
        ]);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $colors = Color::all();
        $sizes = Size::all();

        return view('products.edit', compact('product', 'categories', 'colors', 'sizes'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id'=> 'required|exists:categories,id',
            'color_id'   => 'required|exists:colors,id',
            'size_id'    => 'required|exists:sizes,id',
            'qty'        => 'required|integer|min:0',
            'price'      => 'required|numeric|min:0',
            'image'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imagePath = $product->image;

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            $imagePath = $this->uploadWebp($request->file('image'));
            
        }

        $product->update([
            'name'        => $request->name,
            'category_id'=> $request->category_id,
            'color_id'   => $request->color_id,
            'size_id'    => $request->size_id,
            'qty'        => $request->qty,
            'price'      => $request->price,
            'image'      => $imagePath,
        ]);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    private function uploadWebp($image)
    {
        $filename = uniqid() . '.webp';
        $path = 'products/' . $filename;

        $manager = new ImageManager(GdDriver::class);
    //
        $img = $manager->read($image->getRealPath())
                    ->toWebp(80);

        Storage::disk('public')->put($path, $img);

        return $path;
    }
}
