<x-layout>
    <section class="px-4 sm:px-6 md:px-16 min-h-screen bg-white">
      <div class="max-w-7xl mx-auto">
        <!-- Greeting -->
        <div class="py-5 mb-6">
          <h1 class="text-emerald-700 text-2xl sm:text-3xl font-bold">Hey Ravi! ✨</h1>
          <p class="text-stone-500 text-base">Moonly Software workspace</p>
        </div>
  
        <!-- Banner -->
        <div class="py-6 text-center mb-8">
          <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold">
            <span class="text-neutral-700">Keep the conversation</span>
            <span class="text-red-400"> going</span>
          </h2>
        </div>
  
        <!-- Distributions -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6 pb-10">
          @foreach($distributions as $distribution)
            <div class="bg-white rounded-xl shadow hover:shadow-md transition duration-300 flex flex-col">
              <div class="bg-gray-100 p-4 rounded-t-xl flex justify-start">
                <img src="https://www.gravatar.com/avatar/2c7d99fe281ecd3bcd65ab915bac6dd5?s=250" alt="AI Assistant" class="w-24 h-24 rounded-2xl object-cover" />
              </div>
              <div class="p-4 flex-1">
                <div class="flex flex-wrap gap-2 text-sm mb-2">
                  <span class="bg-[#637DFF] text-white px-2 py-0.5 rounded-md">{{ $distribution->useCase->name }}</span>
                  <span class="bg-[#637DFF] text-white px-2 py-0.5 rounded-md">{{ $distribution->model->name }}</span>
                </div>
                <div class="flex items-center gap-2 mb-2">
                  <h3 class="text-lg font-semibold">{{ $distribution->bot_name }}</h3>
                  <svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                       viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <circle cx="12" cy="12" r="10" />
                    <path d="m9 12 2 2 4-4" />
                  </svg>
                </div>
                <p class="text-gray-500 text-sm line-clamp-3">{{ Str::limit($distribution->description, 100) }}</p>
              </div>
              <div class="p-4">
                <a href="{{ route('distributions.abTest', $distribution) }}"
                   class="block text-center bg-gray-100 hover:bg-black hover:text-white px-4 py-2 text-sm font-medium rounded-md transition">
                  Chat
                </a>
                @if($distribution->is_in_ab_test)
                  <div class="mt-5 bg-red-500 p-4 text-white rounded-md">
                    <strong>{{ $distribution->model->name }}</strong> is currently in A/B testing.
                  </div>
                @endif
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </section>
  </x-layout>
  