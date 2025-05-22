@props(['variant', 'avatar', 'name', 'label', 'content'])

<div onclick="submitVote('{{ $variant }}')"
    class="lg:w-1/2 w-full p-6 bg-white rounded-[10px] flex flex-col sm:flex-row items-start gap-6 hover:bg-slate-50 hover:outline hover:outline-2 hover:outline-stone-300 hover:outline-offset-[-2px] transition-all cursor-pointer">
    <img class="w-12 h-12 rounded-2xl" src="{{ $avatar }}" alt="Model {{ $variant }}">
    <div class="flex-1 space-y-3">
        <div class="flex justify-between items-center">
            <span class="text-neutral-700 text-lg font-bold">{{ $name }}</span>
            <span class="bg-neutral-100 px-4 py-1 rounded text-sm text-neutral-700">Response {{ $variant }}</span>
        </div>
        <div class="text-neutral-700 text-sm sm:text-base leading-relaxed space-y-1">
            {!! $content !!}
        </div>
    </div>
</div>