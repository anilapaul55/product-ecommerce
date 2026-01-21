<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-5 text-gray-900">
                    <h3 class="text-md font-semibold mb-4">Shop Products</h3>

                    @if(session('success'))
                        <p class="text-green-600 text-sm mb-3">{{ session('success') }}</p>
                    @endif

                    @if(session('error'))
                        <p class="text-red-600 text-sm mb-3">{{ session('error') }}</p>
                    @endif
                    
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
    @foreach($products as $product)
        <div>
            <div class="border rounded-md p-3 shadow-sm text-center bg-white h-[320px] flex flex-col">

                <img src="{{ asset('storage/'.$product->image) }}"
                     class="w-full h-40 object-cover mb-2 rounded">

                <div class="flex-1">
                    <h4 class="text-sm font-semibold truncate">
                        {{ $product->name }}
                    </h4>

                    <p class="text-xs text-gray-600">₹{{ $product->price }}</p>
                    <p class="text-xs text-gray-500">Stock: {{ $product->qty }}</p>
                </div>

                <form action="{{ route('cart.add', $product->id) }}"
                      method="POST"
                      class="mt-2 flex items-center justify-center gap-1">
                    @csrf
                    <button type="submit"
                        class="bg-blue-600 text-white px-2 py-1 rounded text-xs hover:bg-blue-700">
                        Add
                    </button>
                </form>

            </div>
        </div>
    @endforeach
</div>


                    <div class="mt-5">
                        <a href="{{ route('cart.view') }}" 
                           class="inline-block bg-green-600  px-4 py-2 rounded text-sm hover:bg-green-700">
                           View Cart
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
