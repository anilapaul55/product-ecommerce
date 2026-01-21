<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Add Product') }}
            </h2>

            <a href="{{ route('products.index') }}"
               class="inline-flex items-center bg-ring-gray-400  px-4 py-2 rounded-md
                      text-sm font-semibold shadow hover:bg-gray-700 focus:outline-none
                      focus:ring-2 focus:ring-green-400 focus:ring-offset-2 transition">
                ← Back
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">

                @if ($errors->any())
                    <div class="mb-4 rounded-md bg-red-50 p-4">
                        <ul class="list-disc list-inside text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('products.store') }}"
                      method="POST"
                      enctype="multipart/form-data"
                      class="space-y-4">
                    @csrf

                    {{-- Product Form Fields --}}
                    @include('products._form')

                    <div class="flex items-center space-x-3 pt-4">
                        <button type="submit"
                                class="inline-flex items-center bg-green-600 px-5 py-2 rounded-md
                                       text-sm font-semibold shadow hover:bg-green-700 focus:outline-none
                                       focus:ring-2 focus:ring-green-400 focus:ring-offset-2 transition">
                            Save
                        </button>

                        <a href="{{ route('products.index') }}"
                           class="inline-flex items-center bg-gray-500 text-white px-5 py-2 rounded-md
                                  text-sm font-semibold shadow hover:bg-gray-600 focus:outline-none
                                  focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition">
                            Cancel
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
