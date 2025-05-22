@props(['avatar', 'name', 'time', 'content'])

<div class="p-6 bg-white rounded-[10px] flex flex-col sm:flex-row items-start gap-6 mb-10">
    <img class="w-12 h-12 rounded-2xl" src="{{ $avatar }}" alt="{{ $name }}">
    <div class="flex-1 space-y-3">
        <div class="flex justify-between items-center">
            <span class="text-neutral-700 text-lg font-bold">{{ $name }}</span>
            <p class="text-sm">{{ $time }}</p>
        </div>
        <div class="text-neutral-700 text-sm sm:text-base leading-relaxed space-y-1">
            {!! $content !!}
        </div>
    </div>
</div>