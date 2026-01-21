<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Products') }}
        </h2>

        <a href="{{ route('products.create') }}"
           class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded-md
                  text-sm font-semibold shadow hover:bg-blue-700 focus:outline-none
                  focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 transition">
            Add Product
        </a>
    </x-slot>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div>
           <h3><strong>For import</strong></h3> 
            <p>*add the excel and press Import product</p>
        <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="file" required>
            <button type="submit">Import Products</button>
        </form>

        </div>
        <br>
        <hr>
<br>

        <div class="overflow-x-auto bg-white shadow rounded-lg">
    <table class="min-w-full border border-gray-200">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 border">Image</th>
                <th class="px-4 py-2 border">Name</th>
                <th class="px-4 py-2 border">Category</th>
                <th class="px-4 py-2 border">Color</th>
                <th class="px-4 py-2 border">Size</th>
                <th class="px-4 py-2 border">Qty</th>
                <th class="px-4 py-2 border">Price</th>
                <th class="px-4 py-2 border">Action</th>
            </tr>
        </thead>

        <tbody>
            @forelse($products as $product)
                <tr class="text-center">
                    <td class="px-4 py-2 border">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}"
                                 class="w-16 h-16 object-cover mx-auto rounded">
                        @else
                            <span class="text-gray-400">No Image</span>
                        @endif
                    </td>

                    <td class="px-4 py-2 border">{{ $product->name }}</td>
                    <td class="px-4 py-2 border">{{ $product->category->name ?? 'N/A' }}</td>
                    <td class="px-4 py-2 border">{{ $product->color->name ?? 'N/A' }}</td>
                    <td class="px-4 py-2 border">{{ $product->size->name ?? 'N/A' }}</td>
                    <td class="px-4 py-2 border">{{ $product->qty }}</td>
                    <td class="px-4 py-2 border">₹{{ number_format($product->price, 2) }}</td>

<td class="px-4 py-2 border space-x-2">
    <a href="{{ url('/products/' . $product->id . '/edit') }}"
       class="inline-block bg-yellow-500 e px-3 py-1 rounded text-sm hover:bg-yellow-600">
        Edit
    </a>

    <form action="{{ url('/products/' . $product->id) }}"
          method="POST"
          class="inline-block">
        @csrf
        @method('DELETE')

        <button type="submit"
                class="bg-red-500  px-3 py-1 rounded text-sm hover:bg-red-600"
                onclick="return confirm('Delete this product?')">
            Delete
        </button>
    </form>
</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8"
                        class="px-4 py-6 text-center text-gray-500">
                        No products found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

    </div>
</div>
</x-app-layout>
