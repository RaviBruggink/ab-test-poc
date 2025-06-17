@props(['variant', 'avatar', 'name', 'label', 'content'])

<div onclick="submitVote('{{ $variant }}')"
     class="group w-full lg:w-1/2 cursor-pointer p-6 bg-white rounded-2xl flex flex-col sm:flex-row items-start gap-6 hover:bg-slate-50 hover:ring-1 hover:ring-neutral-200 transition">
    
    {{-- Avatar & Check Icon --}}
    <div class="flex flex-col items-center justify-between h-full gap-3">
        <img src="{{ $avatar }}" alt="Model {{ $variant }}" class="w-12 h-12 rounded-2xl object-cover" />
        
        <svg xmlns="http://www.w3.org/2000/svg"
             viewBox="0 0 24 24"
             fill="none"
             stroke="currentColor"
             stroke-width="2"
             stroke-linecap="round"
             stroke-linejoin="round"
             class="w-5 h-5 text-white group-hover:text-neutral-300 transition">
            <circle cx="12" cy="12" r="10"/>
            <path d="m9 12 2 2 4-4"/>
        </svg>
    </div>

    {{-- Content --}}
    <div class="flex-1 space-y-3">
        <div class="flex justify-between items-center">
            <span class="text-gray-800 text-lg font-semibold">{{ $name }}</span>
        </div>

        <div class="text-gray-700 text-sm sm:text-base leading-relaxed space-y-2">
            {!! $content !!}
        </div>
    </div>
</div>