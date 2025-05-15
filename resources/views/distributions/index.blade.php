<x-layout>
    <section class="px-6 md:px-16 pt-20 min-h-screen">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-800 mb-8">Distributies</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach($distributions as $distribution)
                    <a href="{{ route('distributions.show', $distribution) }}" class="p-6 bg-white rounded-xl shadow hover:shadow-lg transition">
                        <h2 class="text-xl font-semibold mb-2">{{ $distribution->bot_name }}</h2>
                        <p class="text-gray-600 mb-2">{{ $distribution->model->name }} - {{ $distribution->useCase->name }}</p>
                        <p class="text-gray-500 text-sm">{{ Str::limit($distribution->description, 100) }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
</x-layout>
