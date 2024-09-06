@extends('welcome')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Products</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 py-8">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold mb-8">Products</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($products as $product)
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h2 class="text-xl font-bold mb-2">{{ $product['name'] }}</h2>
                    <h3 class="text-lg mb-4">Price: ${{ $product['price'] }}</h3>
                    <form action="{{ route('stripe') }}" method="post" class="text-center">
                        @csrf
                        <input type="hidden" name="product_name" value="{{ $product['name'] }}">
                        <input type="hidden" name="quantity" value="1">
                        <input type="hidden" name="price" value="{{ $product['price'] }}">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Pay with Stripe
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>

</body>
</html>
@endsection
