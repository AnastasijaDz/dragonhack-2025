<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DragonHack 2025</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900">

    <div class="min-h-screen flex flex-col items-center justify-center">
        <h1 class="text-5xl font-bold text-blue-600 mb-4">Welcome to DragonHack 2025</h1>

        <div class="w-full max-w-xl mt-10">
            <h2 class="text-2xl font-semibold mb-4">Farmers List</h2>
            <ul class="bg-white shadow rounded-lg p-4 space-y-2">
                @forelse ($farmers as $farmer)
                    <li class="border-b pb-2">{{ $farmer->name }}</li>
                @empty
                    <li class="text-gray-500">No farmers found.</li>
                @endforelse
            </ul>
        </div>
    </div>

</body>
</html>
