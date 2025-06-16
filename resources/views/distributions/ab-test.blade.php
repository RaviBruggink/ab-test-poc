<x-layout>
    <section class="px-6 md:px-16 pt-20 min-h-screen hidden">
        <div class="max-w-4xl mx-auto">

            <h1 class="text-3xl font-bold text-gray-800 mb-8">A/B Test: {{ $useCase->name }}</h1>

            <form id="voteForm" method="POST" action="/vote" class="space-y-10">
                @csrf

                <input type="hidden" name="use_case" value="{{ $useCase->name }}">
                <input type="hidden" name="model_a" value="{{ $distribution->model->name }}">
                <input type="hidden" name="model_b" value="{{ $otherModel->name }}">
                <input type="hidden" name="chosen_model" id="chosen_model_input">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Distribution Model -->
                    <button type="button" onclick="submitVote('A')" class="p-6 bg-white rounded-2xl shadow hover:shadow-lg">
                        <h2 class="text-lg font-semibold mb-2">{{ $distribution->model->name }}</h2>
                        <p class="text-gray-500">{{ $distribution->bot_name }}</p>
                    </button>

                    <!-- Random Model -->
                    <button type="button" onclick="submitVote('B')" class="p-6 bg-white rounded-2xl shadow hover:shadow-lg">
                        <h2 class="text-lg font-semibold mb-2">{{ $otherModel->name }}</h2>
                        <p class="text-gray-500">Willekeurig gekozen</p>
                    </button>
                </div>
            </form>

            <script>
                function submitVote(selected) {
                    const chosen = selected === 'A' 
                        ? document.getElementsByName('model_a')[0].value 
                        : document.getElementsByName('model_b')[0].value;

                    document.getElementById('chosen_model_input').value = chosen;
                    document.getElementById('voteForm').submit();
                }
            </script>

        </div>
    </section>

    <section>

    </section>
</x-layout>