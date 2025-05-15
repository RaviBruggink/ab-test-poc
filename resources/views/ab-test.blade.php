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

            <!-- Distributie-informatie -->
            @if (isset($distribution))
                <div class="mb-8 p-4 bg-purple-100 rounded-lg text-purple-800">
                    Je test nu bot <strong>{{ $distribution->bot_name }}</strong> voor de use case
                    <strong>{{ $distribution->useCase->name }}</strong> tegen een ander gekozen model.
                </div>
            @endif

            <!-- A/B Test Formulier -->
            <form id="voteForm" method="POST" action="/vote" class="space-y-10">
                @csrf

                <!-- Hidden inputs indien distributie bestaat -->
                @if (isset($distribution))
                    <input type="hidden" name="distribution_id" value="{{ $distribution->id }}">
                    <input type="hidden" name="use_case" value="{{ $distribution->useCase->name }}">
                    <input type="hidden" id="model_a_select" value="{{ $models[0]['label'] }}">
                @else
                    <!-- Select Use Case -->
                    <div>
                        <label for="use_case_select" class="block text-sm font-medium text-gray-700 mb-2">
                            Kies een Use Case:
                        </label>
                        <select name="use_case" id="use_case_select" required
                            class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400">
                            @foreach (config('models.use_cases') as $useCase)
                                <option value="{{ $useCase }}" {{ old('use_case') === $useCase ? 'selected' : '' }}>
                                    {{ $useCase }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Select Model A -->
                    <div>
                        <label for="model_a_select" class="block text-sm font-medium text-gray-700 mb-2">
                            Kies Model A:
                        </label>
                        <select id="model_a_select" required
                            class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400">
                            @foreach (config('models.models') as $model)
                                <option value="{{ $model['label'] }}" {{ old('model_a') === $model['label'] ? 'selected' : '' }}>
                                    {{ $model['label'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- Select Model B -->
                <div>
                    <label for="model_b_select" class="block text-sm font-medium text-gray-700 mb-2">
                        Kies Model B:
                    </label>
                    <select id="model_b_select" required
                        class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400">
                        @foreach (config('models.models') as $model)
                            <option value="{{ $model['label'] }}" {{ old('model_b') === $model['label'] ? 'selected' : '' }}>
                                {{ $model['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Hidden gekozen model -->
                <input type="hidden" name="chosen_model" id="chosen_model_input">

                <!-- A/B Kaarten -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                    <!-- Model A Kaart -->
                    <div class="space-y-4">
                        <button type="button" onclick="submitVote('A')"
                            class="group relative w-full text-left p-6 bg-white rounded-2xl border border-gray-300 hover:shadow-md hover:-translate-y-1">

                            <div class="flex items-center space-x-4 mb-4">
                                <div class="w-10 h-10 bg-purple-100 text-purple-600 flex items-center justify-center rounded-full font-bold">
                                    A
                                </div>
                                <div id="model_a_label" class="font-semibold text-lg text-gray-800">
                                    {{ $models[0]['label'] }}
                                </div>
                            </div>

                            <!-- Output tekst Model A -->
                            <div class="text-gray-600 text-sm bg-gray-50 p-4 rounded-lg shadow-inner">
                                <p class="mt-2">
                                    Zeker! Laravel is een populair PHP-framework voor webontwikkeling dat gebruikmaakt van het MVC-patroon. Het biedt tools zoals:
                                </p>
                                <ul class="list-disc list-inside mt-4 space-y-1">
                                    <li><span class="text-red-400 font-semibold">Eloquent ORM</span>: Voor eenvoudige database-interacties.</li>
                                    <li><span class="text-red-400 font-semibold">Blade Templating Engine</span>: Voor het maken van dynamische webpagina's.</li>
                                    <li><span class="text-red-400 font-semibold">Routing</span>: Voor het beheren van webverzoeken.</li>
                                    <li><span class="text-red-400 font-semibold">Middleware</span>: Voor het implementeren van verzoekfilters.</li>
                                    <li><span class="text-red-400 font-semibold">Migraties</span>: Voor databasebeheer en versiecontrole.</li>
                                    <li><span class="text-red-400 font-semibold">Queues</span>: Voor taakverwerking op de achtergrond.</li>
                                    <li><span class="text-red-400 font-semibold">Security</span>: Voor ingebouwde beveiligingsmaatregelen.</li>
                                </ul>
                                <p class="mt-4">
                                    Laravel staat bekend om zijn elegante syntax, uitgebreide functionaliteiten en sterke community-ondersteuning, waardoor het een favoriete keuze is voor het bouwen van webapplicaties.
                                </p>
                            </div>

                            <!-- Hover Checkmark -->
                            <div class="hidden group-hover:flex absolute bottom-4 left-4">
                                <div class="w-6 h-6 bg-green-500 text-white rounded-full flex items-center justify-center text-xs">
                                    &#10003;
                                </div>
                            </div>
                        </button>
                    </div>

                    <!-- Model B Kaart -->
                    <div class="space-y-4">
                        <button type="button" onclick="submitVote('B')"
                            class="group relative w-full text-left p-6 bg-white rounded-2xl border border-gray-300 hover:shadow-md hover:-translate-y-1">

                            <div class="flex items-center space-x-4 mb-4">
                                <div class="w-10 h-10 bg-green-100 text-green-600 flex items-center justify-center rounded-full font-bold">
                                    B
                                </div>
                                <div id="model_b_label" class="font-semibold text-lg text-gray-800">
                                    (model keuze B)
                                </div>
                            </div>

                            <!-- Output tekst Model B -->
                            <div class="text-gray-600 text-sm bg-gray-50 p-4 rounded-lg shadow-inner">
                                <p class="mt-2">
                                    Zeker! Laravel is een krachtig PHP-framework voor webontwikkeling dat het MVC-patroon volgt. Hier zijn enkele belangrijke tools die het biedt:
                                </p>
                                <ul class="list-disc list-inside mt-4 space-y-1">
                                    <li><span class="text-red-400 font-semibold">Eloquent ORM</span>: Voor eenvoudige interactie met databases.</li>
                                    <li><span class="text-red-400 font-semibold">Blade Templating Engine</span>: Voor flexibele views en layouts.</li>
                                    <li><span class="text-red-400 font-semibold">Routing</span>: Voor beheer van webverzoeken.</li>
                                    <li><span class="text-red-400 font-semibold">Middleware</span>: Voor het filteren van HTTP-verzoeken.</li>
                                    <li><span class="text-red-400 font-semibold">Migraties</span>: Voor versiebeheer van de database.</li>
                                    <li><span class="text-red-400 font-semibold">Queues</span>: Voor taakverwerking in de achtergrond.</li>
                                    <li><span class="text-red-400 font-semibold">Security</span>: Voor ingebouwde beveiliging van applicaties.</li>
                                </ul>
                                <p class="mt-4">
                                    Laravel is populair vanwege zijn elegante syntax, krachtige features en een actieve community, wat het ideaal maakt voor moderne webapplicaties.
                                </p>
                            </div>

                            <!-- Hover Checkmark -->
                            <div class="hidden group-hover:flex absolute bottom-4 left-4">
                                <div class="w-6 h-6 bg-green-500 text-white rounded-full flex items-center justify-center text-xs">
                                    &#10003;
                                </div>
                            </div>
                        </button>
                    </div>

                </div>
            </form>

            <!-- Script: Afhandeling van votes -->
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

                    // Voeg hidden inputs toe voor model_a en model_b
                    ['model_a', 'model_b'].forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = id;
                        input.value = id === 'model_a' ? modelA : modelB;
                        voteForm.appendChild(input);
                    });

                    voteForm.submit();
                }
            </script>

        </div>
    </section>
</x-layout>