<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moonly A/B Test POC</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">

    <!-- Navbar -->
    <nav class="bg-white shadow p-4 flex justify-between items-center">
        <div class="text-xl font-bold text-gray-800">Moonly A/B Test</div>
        <div class="flex gap-4">
            <a href="/" class="text-gray-700 hover:text-gray-900">A/B Test</a>
            <a href="/chart" class="text-gray-700 hover:text-gray-900">Resultaten</a>
        </div>
    </nav>

    <!-- Content -->
    <main class="p-6">
        {{ $slot }}
    </main>

</body>
</html>
