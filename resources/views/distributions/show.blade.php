<x-layout>
    <section class="px-6 md:px-16 pt-20 min-h-screen">
        <div class="max-w-3xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-800 mb-8">{{ $distribution->bot_name }}</h1>

            <p class="mb-4 text-gray-600"><strong>Model:</strong> {{ $distribution->model->name }}</p>
            <p class="mb-8 text-gray-600"><strong>Use Case:</strong> {{ $distribution->useCase->name }}</p>

            <p class="text-gray-700 mb-12">{{ $distribution->description }}</p>

            <a href="{{ route('distributions.abTest', $distribution) }}" class="inline-block bg-purple-600 hover:bg-purple-700 text-white font-semibold px-6 py-3 rounded-lg transition">
                Start A/B Test
            </a>
        </div>
    </section>
</x-layout>