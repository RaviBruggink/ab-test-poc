<x-layout>
    <section class="px-6 md:px-16 pt-20 min-h-screen">

        <div class="max-w-5xl mx-auto">

            <!-- Titel -->
            <h1 class="text-3xl font-bold text-gray-800 mb-8">
                @if (isset($distribution))
                    A/B Test: {{ $distribution->bot_name }} vs Gekozen Model
                @else
                    Start een A/B Test
                @endif
            </h1>

            <!-- Succesbericht -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-lg shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Info bij distributie -->
            @if (isset($distribution))
                <div class="mb-8 p-4 bg-purple-100 rounded-lg text-purple-800">
                    Je test nu bot <strong>{{ $distribution->bot_name }}</strong> voor de use case <strong>{{ $distribution->useCase->name }}</strong> tegen een ander gekozen model.
                </div>
            @endif

            <!-- Formulier -->
            <form id="voteForm" method="POST" action="/vote" class="space-y-10">
                @csrf

                <!-- Hidden inputs -->
                @if (isset($distribution))
                    <input type="hidden" name="distribution_id" value="{{ $distribution->id }}">
                    <input type="hidden" name="use_case" value="{{ $distribution->useCase->name }}">
                    <input type="hidden" id="model_a_select" value="{{ $models[0]['label'] }}">
                @else
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

                    <div>
                        <label for="model_a_select" class="block text-sm font-medium text-gray-700 mb-2">
                            Kies Model A:
                        </label>
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
                    </div>
                @endif

                <!-- Model B keuze ALTIJD -->
                <div>
                    <label for="model_b_select" class="block text-sm font-medium text-gray-700 mb-2">
                        Kies Model B:
                    </label>
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
                </div>

                <!-- Gekozen model hidden -->
                <input type="hidden" name="chosen_model" id="chosen_model_input">

                <!-- A/B kaarten -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                    <!-- Kaart Model A -->
                    <div class="space-y-4">
                        <button 
                            type="button" 
                            onclick="submitVote('A')"
                            class="group relative w-full text-left p-6 bg-white rounded-2xl border border-gray-300 hover:shadow-md hover:-translate-y-1"
                        >
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="w-10 h-10 bg-purple-100 text-purple-600 flex items-center justify-center rounded-full font-bold">
                                    A
                                </div>
                                <div id="model_a_label" class="font-semibold text-lg text-gray-800">
                                    {{ $models[0]['label'] }}
                                </div>
                            </div>

                            <!-- Simulatie Output A -->
                            <div class="text-gray-600 text-sm bg-gray-50 p-4 rounded-lg shadow-inner">
                                <p><strong>Gebruiker:</strong> "Hoe ziet het weer eruit morgen in Amsterdam?"</p>
                                <p class="mt-2"><strong>AI Antwoord:</strong> "Morgen wordt het bewolkt met lichte regen en 17°C, vergeet je paraplu niet!"</p>
                            </div>

                            <!-- Hover checkmark -->
                            <div class="hidden group-hover:flex absolute bottom-4 left-4">
                                <div class="w-6 h-6 bg-green-500 text-white rounded-full flex items-center justify-center text-xs">
                                    ✓
                                </div>
                            </div>
                        </button>
                    </div>

                    <!-- Kaart Model B -->
                    <div class="space-y-4">
                        <button 
                            type="button" 
                            onclick="submitVote('B')"
                            class="group relative w-full text-left p-6 bg-white rounded-2xl border border-gray-300 hover:shadow-md hover:-translate-y-1"
                        >
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="w-10 h-10 bg-green-100 text-green-600 flex items-center justify-center rounded-full font-bold">
                                    B
                                </div>
                                <div id="model_b_label" class="font-semibold text-lg text-gray-800">
                                    (model keuze B)
                                </div>
                            </div>

                            <!-- Simulatie Output B -->
                            <div class="text-gray-600 text-sm bg-gray-50 p-4 rounded-lg shadow-inner">
                                <p><strong>Gebruiker:</strong> "Hoe ziet het weer eruit morgen in Amsterdam?"</p>
                                <p class="mt-2"><strong>AI Antwoord:</strong> "Verwacht regen in de ochtend en een drogere middag rond 18°C."</p>
                            </div>

                            <!-- Hover checkmark -->
                            <div class="hidden group-hover:flex absolute bottom-4 left-4">
                                <div class="w-6 h-6 bg-green-500 text-white rounded-full flex items-center justify-center text-xs">
                                    ✓
                                </div>
                            </div>
                        </button>
                    </div>

                </div>

            </form>

            <!-- Script -->
            <script>
                function submitVote(selected) {
                    const modelA = document.getElementById('model_a_select').value;
                    const modelB = document.getElementById('model_b_select').value;

                    if (modelA === modelB) {
                        alert('Model A en Model B mogen niet hetzelfde zijn.');
                        return;
                    }

                    const chosenModel = selected === 'A' ? modelA : modelB;
                    document.getElementById('chosen_model_input').value = chosenModel;

                    const voteForm = document.getElementById('voteForm');

                    const aInput = document.createElement('input');
                    aInput.type = 'hidden';
                    aInput.name = 'model_a';
                    aInput.value = modelA;
                    voteForm.appendChild(aInput);

                    const bInput = document.createElement('input');
                    bInput.type = 'hidden';
                    bInput.name = 'model_b';
                    bInput.value = modelB;
                    voteForm.appendChild(bInput);

                    voteForm.submit();
                }
            </script>

        </div>

    </section>
</x-layout>