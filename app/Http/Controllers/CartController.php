<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add($id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            if ($cart[$id]['quantity'] + 1 > $product->qty) {
                return back()->with('error', 'Stock limit reached');
            }
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'image' => $product->image,
                'stock' => $product->qty,
            ];
        }

        session()->put('cart', $cart);
        return back()->with('success', 'Product added to cart');
    }

    public function update(Request $request, $id)
    {
        $cart = session()->get('cart');

        if (!isset($cart[$id])) {
            return back();
        }

        $qty = (int) $request->quantity;

        if ($qty < 1) {
            unset($cart[$id]);
        } elseif ($qty <= $cart[$id]['stock']) {
            $cart[$id]['quantity'] = $qty;
        } else {
            return back()->with('error', 'Quantity exceeds stock');
        }

        session()->put('cart', $cart);
        return back();
    }

    public function remove($id)
    {
        $cart = session()->get('cart');
        unset($cart[$id]);
        session()->put('cart', $cart);

        return back();
    }

    public function view()
    {
        $cart = session()->get('cart', []);
        $coupon = session()->get('coupon');

        return view('user.view', compact('cart', 'coupon'));
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required']);

        $coupon = Coupon::where('code', $request->code)->first();

        if (!$coupon) {
            return back()->with('error', 'Invalid coupon');
        }

        session()->put('coupon', $coupon);
        return back()->with('success', 'Coupon applied');
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Cart is empty');
        }

        foreach ($cart as $id => $item) {
            $product = Product::find($id);
            if ($product->qty < $item['quantity']) {
                return back()->with('error', 'Stock changed. Please update cart.');
            }

            $product->decrement('qty', $item['quantity']);
        }

        session()->forget(['cart', 'coupon']);

        return redirect()->route('udashboard')->with('success', 'Order placed successfully!');
    }

    protected function cartTotals($coupon)
    {
        $cart = session()->get('cart', []);
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $discount = 0;
        $success = false;
        $message = null;

        if ($coupon) {
            $type = $coupon->type ?? $coupon['type'];
            $value = $coupon->value ?? $coupon['value'];
            $minAmount = $coupon->min_amount;

            if ($total < $minAmount) {
                $message = "Minimum purchase of ₹{$minAmount} is required to use this coupon.";

            } else {
                if ($type === 'fixed') {
                    $discount = $value;
                } else {
                    $discount = ($total * $value) / 100;
                }
                session()->put('coupon', $coupon);
                $message = "coupon Applied";
                $success = true;
            }
        }

        $payable = max(0, $total - $discount);

        return [
            'total' => number_format($total, 2),
            'discount' => number_format($discount, 2),
            'payable' => number_format($payable, 2),
            'message' => $message,
            'success' => $success,
        ];
    }

public function ajaxUpdate(Request $request, $id)
{
    $cart = session()->get('cart', []);

    if (!isset($cart[$id])) {
        return response()->json(['status' => 'error']);
    }

    $qty = (int) $request->quantity;

    if ($qty < 1 || $qty > $cart[$id]['stock']) {
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid quantity'
        ]);
    }

    $cart[$id]['quantity'] = $qty;
    session()->put('cart', $cart);

    $coupon = null;
    $couponRemovedMessage = null;

    if (session()->has('coupon')) {
        $coupon = session('coupon');

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $minAmount = $coupon->min_amount ?? $coupon['min_amount'];

        if ($total < $minAmount) {
            session()->forget('coupon');
            $coupon = null;
            $couponRemovedMessage = "Coupon removed because minimum amount is not met.";
        }
    }

    $cartTotals = $this->cartTotals($coupon);


    if ($couponRemovedMessage) {
        $cartTotals['message'] = $couponRemovedMessage;
        $cartTotals['success'] = false;
    }

    return response()->json([
        'status' => 'success',
        'cart' => $cartTotals
    ]);
}


    public function ajaxApplyCoupon(Request $request)
    {
        $request->validate(['code' => 'required']);

        $coupon = Coupon::where('code', $request->code)->first();

        if (!$coupon) {
            return response()->json(['status' => 'error', 'message' => 'Invalid coupon']);
        }

        return response()->json([
            'status' => 'success',
            'cart' => $this->cartTotals($coupon)
        ]);
    }

}
