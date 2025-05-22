<x-layout>
    <section class="px-20 pb-14 pt-5 min-h-screen">
        <div class="mx-auto w-full">

            {{-- Gebruikersbericht --}}
            <x-chat.message 
                avatar="https://placehold.co/52x52" 
                name="User" 
                time="13:31"
                content="<p>Hey, we zijn inmiddels vollop bezig met het adverteren van DanceFest 3000. Ik wil een poster gaan maken van de timetable. wat is de line-up?</p>"
            />

            {{-- A/B Cards --}}
            <section class="flex flex-col lg:flex-row gap-6 pb-40">
                
            @php
            $cardContentA = '
                <p><strong>Line-up voor DanceFest 3000:</strong></p>

                <p><u>Mainstage:</u></p>
                <ul class="list-disc pl-5">
                    <li><span class="text-red-400 font-bold">Quintino</span> — aankomst om 14:45 (Eindhoven Airport)</li>
                    <li><span class="text-red-400 font-bold">La Fuente</span> — optreden om 18:00</li>
                    <li><span class="text-red-400 font-bold">Atmozfears</span> — optreden om 20:00 (MC nog niet bevestigd)</li>
                </ul>

                <p><u>Techno Area:</u></p>
                <ul class="list-disc pl-5">
                    <li><strong>Adam Beyer</strong> — vanaf 18:00</li>
                    <li><strong>Charlotte de Witte</strong> — vanaf 20:00 (soundcheck gepland 30 min. vooraf)</li>
                </ul>
            ';

            $cardContentB = '
                <p>🔥 De line-up ziet er fantastisch uit! Hier is een sneak peek van wat je kunt verwachten:</p>

                <p><strong>Mainstage Madness:</strong></p>
                <ul class="list-disc pl-5">
                    <li><span class="text-red-400 font-bold">Quintino</span> landt om 14:45 op Eindhoven Airport – klaar om de boel af te breken!</li>
                    <li><span class="text-red-400 font-bold">La Fuente</span> zet alles op z’n kop om 18:00</li>
                    <li><span class="text-red-400 font-bold">Atmozfears</span> sluit af om 20:00 – MC nog een verrassing 😉</li>
                </ul>

                <p><strong>Techno Tunnel:</strong></p>
                <ul class="list-disc pl-5">
                    <li><strong>Adam Beyer</strong> begint strak om 18:00</li>
                    <li><strong>Charlotte de Witte</strong> ramt erin om 20:00 – techniek staat paraat vanaf 19:30</li>
                </ul>
            ';
            @endphp

                <x-chat.card variant="A" avatar="https://placehold.co/52x52" name="Nova" :content="$cardContentA" />
                <x-chat.card variant="B" avatar="https://placehold.co/52x52" name="Nova" :content="$cardContentB" />
            </section>

            {{-- Chat Input --}}
            <div class="fixed bottom-10 left-0 right-0 px-4">
                <div class="max-w-screen-md mx-auto rounded-2xl px-4 py-3 bg-gray-50 shadow-sm border border-gray-200">
                    <div class="flex items-center gap-2">
                        <textarea x-data="chatInput" x-model="chatMessage" x-on:change="resize($el)"
                            x-on:message-sent.window="resize($el)" x-ref="answer"
                            @keydown.enter="if (!$event.shiftKey) { $event.preventDefault(); send() }"
                            @keyup.enter="if ($event.shiftKey) { $event.preventDefault(); }" @input="resize($el)"
                            placeholder="Start typing to chat..."
                            class="w-full resize-none bg-transparent border-none focus:ring-0 text-gray-700 text-md"
                            style="height: 28px; line-height: 1.5;"></textarea>
                        <button @click="send()"
                            class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-send-icon lucide-send">
                                <path d="M14.536 21.686a.5.5 0 0 0 .937-.024l6.5-19a.496.496 0 0 0-.635-.635l-19 6.5a.5.5 0 0 0-.024.937l7.93 3.18a2 2 0 0 1 1.112 1.11z" />
                                <path d="m21.854 2.147-10.94 10.939" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Titel --}}
            <h1 class="text-3xl font-bold text-gray-800 mb-8">
                @if (isset($distribution))
                    A/B Test: {{ $distribution->bot_name }} vs Gekozen Model
                @else
                    Start een A/B Test
                @endif
            </h1>

            {{-- Succesmelding --}}
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-lg shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Info Box --}}
            @if (isset($distribution))
                <div class="mb-8 p-4 bg-purple-100 rounded-lg text-purple-800">
                    Je test nu bot <strong>{{ $distribution->bot_name }}</strong> voor de use case
                    <strong>{{ $distribution->useCase->name }}</strong> tegen een ander gekozen model.
                </div>
            @endif

            {{-- A/B Formulier --}}
            <form id="voteForm" method="POST" action="/vote" class="space-y-10">
                @csrf

                @if (isset($distribution))
                    <input type="hidden" name="distribution_id" value="{{ $distribution->id }}">
                    <input type="hidden" name="use_case" value="{{ $distribution->useCase->name }}">
                    <input type="hidden" id="model_a_select" value="{{ $models[0]['label'] }}">
                @else
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

                <input type="hidden" name="chosen_model" id="chosen_model_input">
            </form>
        </div>
    </section>

    {{-- Vote script --}}
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
</x-layout>
