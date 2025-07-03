<x-layout>
    <div class="relative text-sm">
        <!-- Colorblind mode toggle button -->
        <button id="toggleColorblindMode" aria-label="Toggle Colorblind Mode"
            class="hidden right-3 top-3 group p-1.5 bg-neutral-700 text-white rounded-full hover:bg-neutral-600 transition">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                    d="M12 22a1 1 0 0 1 0-20 10 9 0 0 1 10 9 5 5 0 0 1-5 5h-2.25a1.75 1.75 0 0 0-1.4 2.8l.3.4a1.75 1.75 0 0 1-1.4 2.8z" />
                <circle cx="13.5" cy="6.5" r=".5" fill="currentColor" />
                <circle cx="17.5" cy="10.5" r=".5" fill="currentColor" />
                <circle cx="6.5" cy="12.5" r=".5" fill="currentColor" />
                <circle cx="8.5" cy="7.5" r=".5" fill="currentColor" />
            </svg>
        </button>

        <!-- Page title -->
        <h3 class="text-5xl font-semibold my-16 ml-14">Models</h3>

        <!-- Filters section -->
        <div class="flex flex-wrap gap-4 mb-10 ml-14">
            <!-- Use case filter dropdown -->
            <div>
                <label for="useCaseSelect" class="text-xs font-semibold text-gray-600 block mb-1">Use Case</label>
                <select id="useCaseSelect" class="text-md border border-[#CECECE] bg-[#f8fafc] rounded px-2 py-2">
                    <option value="__average__">All</option>
                    @foreach ($useCases as $uc)
                        <option value="{{ $uc }}">{{ $uc }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Baseline model filter dropdown -->
            <div>
                <label for="modelSelect" class="text-xs font-semibold text-gray-600 block mb-1">Baseline</label>
                <select id="modelSelect" class="text-md border border-[#CECECE] bg-[#f8fafc] rounded px-2 py-2">
                    <option value="__all__">None</option>
                    @foreach ($models as $model)
                        <option value="{{ $model['label'] }}">{{ $model['label'] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="ml-auto mr-14">
                <button id="actionsButton"
                    class="bg-black text-white px-4 py-2 rounded font-medium hover:bg-gray-800 flex flex-row items-center gap-2">
                    Actions
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-chevron-down-icon lucide-chevron-down">
                        <path d="m6 9 6 6 6-6" />
                    </svg>
                </button>

                <!-- Dropdown menu -->
                <div id="actionsMenu" class="absolute right-14 mt-2 w-40 bg-white rounded-md shadow-lg z-10 hidden">
                    <button
                        class="w-full text-left px-4 py-2 text-sm text-gray-800 bg-[#f8fafc] border-[#CECECE] rounded-t-md hover:bg-[#f3f3f3]">Add
                        model +</button>
                    <button
                        class="w-full text-left px-4 py-2 text-sm text-gray-800 bg-[#f8fafc] border-[#CECECE] rounded-b-md hover:bg-[#f3f3f3]">Test
                        models</button>
                </div>
            </div>
        </div>

        <!-- Chart container -->
        <div class="flex justify-end">
            <div id="modelChartContainer" class="w-full" 
            data-models='@json($models)'
            data-scores='@json($grouped)'
            data-usecases='@json($useCases)'>
            </div>
        </div>
    </div>
</x-layout>

<script src="{{ asset('js/models-chart.js') }}"></script>