<x-layout>
    <section class="px-6 md:px-16 pt-20 min-h-screen">

        <div class="max-w-5xl mx-auto">

            <!-- Section: Pagina Titel -->
            <h1 class="text-3xl font-bold text-gray-800 mb-8">Start een A/B Test</h1>

            <!-- Section: Succesbericht -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-lg shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Section: A/B Test Formulier -->
            <form id="voteForm" method="POST" action="/vote" class="space-y-10">
                @csrf

                <!-- Section: Use Case Selectie -->
                <div>
                    <label for="use_case_select" class="block text-sm font-medium text-gray-700 mb-2">
                        Kies een Use Case:
                    </label>
                    <select 
                        name="use_case" 
                        id="use_case_select" 
                        required
                        class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400"
                    >
                        @foreach (config('models.use_cases') as $useCase)
                            <option value="{{ $useCase }}" {{ old('use_case') === $useCase ? 'selected' : '' }}>
                                {{ $useCase }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Hidden Input: Gekozen Model -->
                <input type="hidden" name="chosen_model" id="chosen_model_input">

                <!-- Section: Modellen Vergelijking -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                    <!-- Card: Model A -->
                    <div class="space-y-4">
                        <select 
                            id="model_a_select" 
                            required
                            class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400"
                        >
                            @foreach (config('models.models') as $model)
                                <option value="{{ $model['label'] }}" {{ old('model_a') === $model['label'] ? 'selected' : '' }}>
                                    {{ $model['label'] }}
                                </option>
                            @endforeach
                        </select>

                        <button 
                            type="button" 
                            onclick="submitVote('A')"
                            class="group relative w-full text-left p-6 bg-white rounded-2xl border border-gray-300 transition-all duration-200 hover:border-gray-400 hover:shadow-md hover:-translate-y-1 space-y-4"
                        >
                            <!-- Card Content: Model A -->
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-purple-100 text-purple-600 flex items-center justify-center rounded-full font-bold">
                                    A
                                </div>
                                <div id="model_a_label" class="font-semibold text-lg text-gray-800">
                                    Nova
                                </div>
                            </div>

                            <!-- Card Text: Model A -->
                            <div class="text-sm text-gray-600">
                                Leuk om te horen dat DanceFest 3000 in volle gang is! Laten we eens kijken welke artiesten er nog moeten komen:
                                <ul class="list-disc list-inside mt-2">
                                    <li><strong>Mainstage:</strong>
                                        <ul class="list-disc list-inside ml-4">
                                            <li><span class="text-red-500">Quintino</span> (om 14:45 ophalen vanaf Eindhoven Airport)</li>
                                            <li><span class="text-red-500">La Fuente</span> (start om 18:00)</li>
                                            <li><span class="text-red-500">Atmozfears</span> (start om 20:00, komt met onbekende MC)</li>
                                        </ul>
                                    </li>
                                    <li><strong>Techno:</strong>
                                        <ul class="list-disc list-inside ml-4">
                                            <li><span class="font-bold">Adam Beyer</span> (start om 18:00)</li>
                                            <li><span class="text-red-500">Charlotte de Witte</span> (start om 20:00, technische dienst klaarzetten)</li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>

                            <!-- Hover Effect: Checkmark Model A -->
                            <div class="hidden group-hover:flex absolute bottom-4 left-4">
                                <div class="w-6 h-6 bg-green-500 text-white rounded-full flex items-center justify-center text-xs">
                                    ✓
                                </div>
                            </div>
                        </button>
                    </div>

                    <!-- Card: Model B -->
                    <div class="space-y-4">
                        <select 
                            id="model_b_select" 
                            required
                            class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400"
                        >
                            @foreach (config('models.models') as $model)
                                <option value="{{ $model['label'] }}" {{ old('model_b') === $model['label'] ? 'selected' : '' }}>
                                    {{ $model['label'] }}
                                </option>
                            @endforeach
                        </select>

                        <button 
                            type="button" 
                            onclick="submitVote('B')"
                            class="group relative w-full text-left p-6 bg-white rounded-2xl border border-gray-300 transition-all duration-200 hover:border-gray-400 hover:shadow-md hover:-translate-y-1 space-y-4"
                        >
                            <!-- Card Content: Model B -->
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-green-100 text-green-600 flex items-center justify-center rounded-full font-bold">
                                    B
                                </div>
                                <div id="model_b_label" class="font-semibold text-lg text-gray-800">
                                    Nova
                                </div>
                            </div>

                            <!-- Card Text: Model B -->
                            <div class="text-sm text-gray-600">
                                Leuk om te horen dat DanceFest 3000 in volle gang is! Laten we eens kijken welke artiesten er nog moeten komen:
                                <ul class="list-disc list-inside mt-2">
                                    <li><strong>Mainstage:</strong>
                                        <ul class="list-disc list-inside ml-4">
                                            <li><span class="text-red-500">Quintino</span> (om 14:45 ophalen vanaf Eindhoven Airport)</li>
                                            <li><span class="text-red-500">La Fuente</span> (start om 18:00)</li>
                                            <li><span class="text-red-500">Atmozfears</span> (start om 20:00, komt met onbekende MC)</li>
                                        </ul>
                                    </li>
                                    <li><strong>Techno:</strong>
                                        <ul class="list-disc list-inside ml-4">
                                            <li><span class="font-bold">Adam Beyer</span> (start om 18:00)</li>
                                            <li><span class="text-red-500">Charlotte de Witte</span> (start om 20:00, technische dienst klaarzetten)</li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>

                            <!-- Hover Effect: Checkmark Model B -->
                            <div class="hidden group-hover:flex absolute bottom-4 left-4">
                                <div class="w-6 h-6 bg-green-500 text-white rounded-full flex items-center justify-center text-xs">
                                    ✓
                                </div>
                            </div>
                        </button>
                    </div>

                </div>
            </form>
        </div>

        <!-- Script: Submit Vote -->
        <script>
            function submitVote(selected) {
                const modelA = document.getElementById('model_a_select').value;
                const modelB = document.getElementById('model_b_select').value;
                const useCase = document.getElementById('use_case_select').value;

                if (modelA === modelB) {
                    alert('Model A en Model B mogen niet hetzelfde zijn.');
                    return;
                }

                const chosenModel = selected === 'A' ? modelA : modelB;
                document.getElementById('chosen_model_input').value = chosenModel;

                const aInput = document.createElement('input');
                aInput.type = 'hidden';
                aInput.name = 'model_a';
                aInput.value = modelA;
                document.getElementById('voteForm').appendChild(aInput);

                const bInput = document.createElement('input');
                bInput.type = 'hidden';
                bInput.name = 'model_b';
                bInput.value = modelB;
                document.getElementById('voteForm').appendChild(bInput);

                document.getElementById('voteForm').submit();
            }
        </script>

    </section>
</x-layout>