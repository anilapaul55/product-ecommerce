<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Your Cart') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Messages --}}
                    @if(session('error'))
                        <p class="mb-3">{{ session('error') }}</p>
                    @endif

                    @if(session('success'))
                        <p class="text-green-600 mb-3">{{ session('success') }}</p>
                    @endif

                    @if(empty($cart))
                        <p>Your cart is empty.</p>
                        <a href="{{ route('udashboard') }}"
                           class="inline-block mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Go to Shop
                        </a>
                    @else

                    @php $total = 0; @endphp

                    <div class="overflow-x-auto">
                        <table class="w-full border border-gray-300 text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border px-3 py-2 text-left">Product</th>
                                    <th class="border px-3 py-2 text-left">Price</th>
                                    <th class="border px-3 py-2 text-left">Qty</th>
                                    <th class="border px-3 py-2 text-left">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cart as $id => $item)
                                <tr>
                                    <td class="border px-3 py-2">{{ $item['name'] }}</td>
                                    <td class="border px-3 py-2">₹{{ $item['price'] }}</td>
                                    <td class="border px-3 py-2">
                                        <input type="number"
                                            value="{{ $item['quantity'] }}"
                                            min="1"
                                            max="{{ $item['stock'] }}"
                                            onchange="updateQty({{ $id }}, this.value)"
                                            class="w-16 border rounded px-2 py-1 text-sm">
                                    </td>
                                    <td class="border px-3 py-2">
                                        <form action="{{ route('cart.remove', $id) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                    class="bg-red-600 text-white px-3 py-1 rounded text-xs hover:bg-red-700">
                                                Remove
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>

                    {{-- <div class="mt-4 space-y-2">
                        <p>Total: ₹<span id="total-amount">{{ $total }}</span></p>
                        <p>Discount: ₹<span id="discount-amount">0.00</span></p>
                        <p class="font-semibold">
                            Payable: ₹<span id="payable-amount">{{ $total }}</span>
                        </p>
                    </div> --}}
                    <div class="mt-4 space-y-2">
    @php
        // If $total is not calculated or is 0, calculate from cart
        if (empty($total) || $total == 0) {
            $total = 0;
            foreach($cart as $item) {
                $total += $item['price'] * $item['quantity'];
            }
        }

        // Discount logic (if you have a coupon in session)
        $discount = 0;
        if(session()->has('coupon')) {
            $coupon = session('coupon');
            $type = $coupon->type ?? $coupon['type'];
            $value = $coupon->value ?? $coupon['value'];

            if($type === 'fixed'){
                $discount = $value;
            } else {
                $discount = ($total * $value) / 100;
            }
        }

        $payable = max(0, $total - $discount);
    @endphp

    <p>Total: ₹<span id="total-amount">{{ number_format($total, 2) }}</span></p>
    <p>Discount: ₹<span id="discount-amount">{{ number_format($discount, 2) }}</span></p>
    <p class="font-semibold">
        Payable: ₹<span id="payable-amount">{{ number_format($payable, 2) }}</span>
    </p>
</div>
<br>

<div id="coupon-message" class="mt-2"></div>

@if(session()->has('coupon')) 
    <p class="text-green-600">Coupon Applied</p>
@else
    <form onsubmit="applyCoupon(event)" class="mt-4 flex gap-2">
        @csrf
        <input type="text" id="coupon-code"
            placeholder="Coupon code"
            class="border rounded px-3 py-2 text-sm w-48">

        <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-purple-700">
            Apply Coupon
        </button>
    </form>
@endif
                    {{-- Checkout --}}
                    <form action="{{ route('checkout') }}" method="POST" class="mt-4">
                        @csrf
                        <button type="submit"
                                class=" bg-blue-600 text-white px-6 py-2 rounded hover:bg-green-700">
                            Checkout
                        </button>
                    </form>

                    @endif

                </div>
            </div>

        </div>
    </div>
<script>

function clearCouponMessage() {
    let msgDiv = document.getElementById('coupon-message');
    if (msgDiv) {
        msgDiv.innerHTML = '';
    }
}

function updateQty(id, qty) {
    fetch("{{ url('/cart/ajax-update') }}/" + id, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ quantity: qty })
    })
    .then(res => res.json())
    -then(data => {
        clearCouponMessage();
      if (data.status === 'success') {

            let cart = data.cart;

            document.getElementById('total-amount').innerText = cart.total;
            document.getElementById('discount-amount').innerText = cart.discount;
            document.getElementById('payable-amount').innerText = cart.payable;

            let msgDiv = document.getElementById('coupon-message');

            if (cart.message) {
                let color = cart.success ? 'text-green-600' : 'text-red-600';
                msgDiv.innerHTML = `
                    <div class="${color} text-sm font-medium">
                        ${cart.message}
                    </div>
                `;
            }
        }
    });
}

function applyCoupon(e) {
    e.preventDefault();

    let code = document.getElementById('coupon-code').value;

    fetch("{{ route('cart.ajax.apply.coupon') }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ code: code })
    })
    .then(res => res.json())
    .then(data => {

        let msgDiv = document.getElementById('coupon-message');

        if (data.status === 'error') {
            msgDiv.innerHTML = `
                <div class="text-red-600 text-sm font-medium">
                    ${data.message}
                </div>
            `;
            return;
        }

        let cart = data.cart;

        if (cart.success) {

            document.getElementById('total-amount').innerText = cart.total;
            document.getElementById('discount-amount').innerText = cart.discount;
            document.getElementById('payable-amount').innerText = cart.payable;

            msgDiv.innerHTML = `
                <div class="text-green-600 text-sm font-medium">
                    ${cart.message}
                </div>
            `;

        } else {

            msgDiv.innerHTML = `
                <div class="text-red-600 text-sm font-medium">
                    ${cart.message}
                </div>
            `;
        }
    });
}
</script>

</x-app-layout>
