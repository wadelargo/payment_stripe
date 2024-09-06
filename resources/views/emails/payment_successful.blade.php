<!DOCTYPE html>
<html>
<head>
    <title>Payment Successful</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold mb-4 text-center">Thank you for your purchase!</h1>
        <p class="text-lg"><span class="font-semibold">Product:</span> {{ $payment->product_name }}</p>
        <p class="text-lg"><span class="font-semibold">Quantity:</span> {{ $payment->quantity }}</p>
        <p class="text-lg"><span class="font-semibold">Amount:</span> ${{ $payment->amount / 100 }}</p>
    </div>
</body>
</html>
