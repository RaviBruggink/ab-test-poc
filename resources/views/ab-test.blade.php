<x-layout>
    <section class="px-6 md:px-16 pt-20 bg-neutral-50 min-h-screen">

        <div class="max-w-5xl mx-auto">

            <h1 class="text-3xl font-bold text-gray-800 mb-8">Start een A/B Test</h1>

            @if (session('success'))
                <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-lg shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <form id="voteForm" method="POST" action="/vote" class="space-y-10">
                @csrf

                <!-- Use Case -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kies een Use Case:</label>
                    <select name="use_case" id="use_case_select" required
                        class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400">
                        @foreach (config('models.use_cases') as $useCase)
                            <option value="{{ $useCase }}" {{ old('use_case') === $useCase ? 'selected' : '' }}>
                                {{ $useCase }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Verborgen input voor gekozen model -->
                <input type="hidden" name="chosen_model" id="chosen_model_input">

                <!-- Output Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Model A -->
                    <div class="space-y-4">
                        <select id="model_a_select" required
                            class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400">
                            @foreach (config('models.models') as $model)
                                <option value="{{ $model['label'] }}"
                                    {{ old('model_a') === $model['label'] ? 'selected' : '' }}>
                                    {{ $model['label'] }}
                                </option>
                            @endforeach
                        </select>

                        <button type="button" onclick="submitVote('A')"
                            class="text-left w-full p-6 bg-white rounded-2xl shadow-md hover:shadow-lg transition hover:-translate-y-1 duration-200 space-y-4">
                            <div class="flex items-center space-x-4">
                                <div
                                    class="w-10 h-10 bg-purple-100 text-purple-600 flex items-center justify-center rounded-full font-bold">
                                    A
                                </div>
                                <div class="font-semibold text-lg text-gray-800" id="model_a_label">
                                    Model A Output
                                </div>
                            </div>
                            <div class="text-sm text-gray-600">
                                Werving kan een uitdagend, maar ook een heel boeiend onderdeel van HR zijn. In de
                                context van AI kunnen modellen voor natuurlijke taalverwerking, zoals GPT (Generative
                                Pre-trained Transformer), erg nuttig zijn. Ze kunnen helpen bij het doorzoeken van cv's,
                                het opstellen van vacatureteksten, en zelfs bij het initialiseren van gesprekken met
                                kandidaten.

                                Een andere belangrijke toepassing is het gebruik van AI bij het beoordelen en
                                rangschikken van kandidaten op basis van door AI-gestuurde screenings. Hierbij kun je
                                denken aan het analyseren van grote hoeveelheden gegevens om geschikte kandidaten te
                                filteren op basis van vooraf gedefinieerde criteria.

                                Wat spreekt je het meeste aan aan werving en de rol die AI daarin kan spelen? Is er een
                                specifieke uitdaging die je probeert op te lossen?
                            </div>
                        </button>
                    </div>

                    <!-- Model B -->
                    <div class="space-y-4">
                        <select id="model_b_select" required
                            class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400">
                            @foreach (config('models.models') as $model)
                                <option value="{{ $model['label'] }}"
                                    {{ old('model_b') === $model['label'] ? 'selected' : '' }}>
                                    {{ $model['label'] }}
                                </option>
                            @endforeach
                        </select>

                        <button type="button" onclick="submitVote('B')"
                            class="text-left w-full p-6 bg-white rounded-2xl shadow-md hover:shadow-lg transition hover:-translate-y-1 duration-200 space-y-4">
                            <div class="flex items-center space-x-4">
                                <div
                                    class="w-10 h-10 bg-green-100 text-green-600 flex items-center justify-center rounded-full font-bold">
                                    B
                                </div>
                                <div class="font-semibold text-lg text-gray-800" id="model_b_label">
                                    Model B Output
                                </div>
                            </div>
                            <div class="text-sm text-gray-600">
                                Hoi Ravi, fijn dat je er weer bent. Vanuit jouw interesse in HR-zaken begrijp ik dat je
                                op zoek bent naar een model dat daar goed bij past. Verschillende AI-modellen kunnen
                                worden ingezet voor HR-taken, afhankelijk van de specifieke behoefte. Bijvoorbeeld, een
                                model dat gericht is op natuurlijke taalverwerking kan helpen bij taken zoals het
                                analyseren van sollicitatiebrieven of het beantwoorden van vragen van werknemers.

                                Denk je aan iets specifieks binnen HR, zoals werving, teamdynamiek of misschien
                                feedbacksystemen? Dan kan ik je beter adviseren welk model of welke specifieke
                                AI-mogelijkheden daar het beste bij passen. Hoe gaat het verder met je interesse in AI
                                en HR?
                            </div>
                        </button>
                    </div>
                </div>
            </form>

        </div>

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

                // Voeg ook de gekozen modellen toe als hidden inputs
                let aInput = document.createElement('input');
                aInput.type = 'hidden';
                aInput.name = 'model_a';
                aInput.value = modelA;
                document.getElementById('voteForm').appendChild(aInput);

                let bInput = document.createElement('input');
                bInput.type = 'hidden';
                bInput.name = 'model_b';
                bInput.value = modelB;
                document.getElementById('voteForm').appendChild(bInput);

                document.getElementById('voteForm').submit();
            }
        </script>

    </section>
</x-layout>
