<x-layout>
    <div class="m-6 bg-gray-50 p-4 rounded-lg shadow-sm border border-gray-200 relative text-sm">

        <!-- Colorblind toggle knop -->
        <button id="toggleColorblindMode" aria-label="Toggle Colorblind Mode"
            class="absolute right-3 top-3 group p-1.5 bg-neutral-700 text-white rounded-full hover:bg-neutral-600 transition">
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

        <!-- Titel en beschrijving -->
        <h3 class="text-lg font-semibold mb-1.5">Relatieve prestaties per Use Case</h3>
        <p class="text-sm mb-5 max-w-[40ch]">
            Hoe modellen scoren ten opzichte van een gekozen baseline binnen één specifieke use case.
        </p>

        <!-- Filters -->
        <div class="flex flex-wrap gap-4 mb-5">
            <div>
                <label for="useCaseSelect" class="text-xs font-semibold text-gray-600 block mb-1">Use Case</label>
                <select id="useCaseSelect"
                    class="text-sm border border-gray-300 rounded px-2 py-1 focus:ring-2 focus:ring-indigo-500">
                    <option value="__average__">Gemiddelde over alle use cases</option>
                    @foreach ($useCases as $uc)
                        <option value="{{ $uc }}">{{ $uc }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="modelSelect" class="text-xs font-semibold text-gray-600 block mb-1">Baseline Model</label>
                <select id="modelSelect"
                    class="text-sm border border-gray-300 rounded px-2 py-1 focus:ring-2 focus:ring-indigo-500">
                    <option value="__all__">Alle modellen (geen baseline)</option>
                    @foreach ($models as $model)
                        <option value="{{ $model['label'] }}">{{ $model['label'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Grafiek -->
        <div id="modelChartContainer" class="w-full overflow-x-auto"></div>
    </div>

    <!-- Highcharts -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Gebruik jouw bestaande data
            const models = @json($models);
            const modelScores = @json($grouped);
            const useCases = @json($useCases);

            let selectedModel = "__all__";
            let selectedUseCase = useCases[0] || "__average__";
            let colorblindMode = false;

            const originalColors = Object.fromEntries(models.map(m => [m.label, m.color]));
            const colorblindColors = {
                'GPT-4o': '#0072B2',
                'Gemma (Ollama)': '#009E73',
                'Llama3': '#E69F00',
                'LLaMa 3.3': '#56B4E9',
                'Claude 3.5 Sonnet': '#CC79A7',
                'Claude 3.5 Haiku': '#F0E442',
                'Claude 3.7 Sonnet': '#D55E00'
            };

            function renderChart(useCase, baselineLabel) {
                const container = document.getElementById('modelChartContainer');

                if (baselineLabel === '__all__') {
                    // Voor "alle modellen" - gebruik lollipop chart met absolute scores
                    renderLollipopChart(useCase, container);
                } else {
                    // Voor baseline vergelijking - gebruik error bars met percentages
                    renderErrorBarChart(useCase, baselineLabel, container);
                }
            }

            function renderLollipopChart(useCase, container) {
                const chartData = models.map((m, index) => {
                    const score = modelScores[m.label]?.[useCase] ?? 0;
                    const margin = Math.max(1, score * 0.08); // 8% marge of minimaal 1 punt

                    return {
                        name: m.label,
                        score: score,
                        low: Math.max(0, score - margin),
                        high: score + margin,
                        color: colorblindMode ? (colorblindColors[m.label] || m.color) : m.color,
                        x: index
                    };
                });

                const maxScore = Math.max(...chartData.map(d => d.high), 10);
                container.style.height = `${Math.max(chartData.length * 60, 400)}px`;

                Highcharts.chart('modelChartContainer', {
                    chart: {
                        type: 'columnrange',
                        inverted: true,
                        backgroundColor: 'transparent',
                        margin: [10, 80, 80, 120]
                    },
                    title: { text: null },
                    xAxis: {
                        categories: chartData.map(d => d.name),
                        labels: {
                            style: {
                                color: '#374151',
                                fontSize: '12px',
                                fontWeight: '500'
                            }
                        },
                        lineColor: '#e5e7eb',
                        tickLength: 0
                    },
                    yAxis: {
                        title: {
                            text: 'Aantal stemmen',
                            style: { color: '#6b7280', fontSize: '12px' }
                        },
                        gridLineColor: 'rgba(0,0,0,0.05)',
                        labels: {
                            style: {
                                color: '#6b7280',
                                fontSize: '11px'
                            }
                        },
                        min: 0,
                        max: Math.ceil(maxScore * 1.1)
                    },
                    tooltip: {
                        formatter() {
                            if (this.series.name === 'Score') {
                                return `<strong>${this.point.name}</strong><br>Stemmen: ${Math.round(this.point.y)}`;
                            } else {
                                const point = this.point;
                                return `<strong>${point.name}</strong><br>
                                    Stemmen: ${Math.round(point.score)}<br>
                                    Geschatte range: ${Math.round(point.low)} - ${Math.round(point.high)}`;
                            }
                        },
                        style: { fontSize: '12px' }
                    },
                    plotOptions: {
                        columnrange: {
                            dataLabels: { enabled: false },
                            borderRadius: 0,
                            pointWidth: 8,
                            borderWidth: 0,
                            opacity: 0.3
                        },
                        scatter: {
                            marker: {
                                symbol: 'line',
                                radius: 15,
                                lineWidth: 3
                            }
                        }
                    },
                    legend: { enabled: false },
                    credits: { enabled: false },
                    series: [{
                        name: 'Marge',
                        data: chartData.map(d => ({
                            name: d.name,
                            low: d.low,
                            high: d.high,
                            score: d.score,
                            color: d.color
                        })),
                        zIndex: 1
                    }, {
                        type: 'scatter',
                        name: 'Score',
                        data: chartData.map(d => ({
                            name: d.name,
                            x: d.x,
                            y: d.score,
                            color: d.color
                        })),
                        zIndex: 2,
                        marker: {
                            symbol: 'line',
                            radius: 15,
                            lineWidth: 3
                        }
                    }]
                });
            }

            function renderErrorBarChart(useCase, baselineLabel, container) {
                const baselineModel = models.find(m => m.label === baselineLabel);
                const baselineValue = modelScores[baselineModel.label]?.[useCase] || 1;

                const chartData = models
                    .filter(m => m.label !== baselineLabel)
                    .map((m, index) => {
                        const value = modelScores[m.label]?.[useCase] ?? 0;
                        const diff = ((value - baselineValue) / baselineValue) * 100;
                        const margin = Math.max(2, Math.abs(diff * 0.15)); // 15% marge of minimaal 2%

                        return {
                            name: m.label,
                            score: diff,
                            low: diff - margin,
                            high: diff + margin,
                            color: colorblindMode ? (colorblindColors[m.label] || m.color) : m.color,
                            x: index
                        };
                    });

                container.style.height = `${Math.max(chartData.length * 60, 400)}px`;

                Highcharts.chart('modelChartContainer', {
                    chart: {
                        type: 'columnrange',
                        inverted: true,
                        backgroundColor: 'transparent',
                        margin: [10, 80, 80, 120]
                    },
                    title: { text: null },
                    xAxis: {
                        categories: chartData.map(d => d.name),
                        labels: {
                            style: {
                                color: '#374151',
                                fontSize: '12px',
                                fontWeight: '500'
                            }
                        },
                        lineColor: '#e5e7eb',
                        tickLength: 0
                    },
                    yAxis: {
                        title: {
                            text: 'Verschil t.o.v. baseline (%)',
                            style: { color: '#6b7280', fontSize: '12px' }
                        },
                        gridLineColor: 'rgba(0,0,0,0.05)',
                        plotLines: [{
                            color: '#9ca3af',
                            width: 2,
                            value: 0,
                            zIndex: 1,
                            dashStyle: 'Dash'
                        }],
                        labels: {
                            formatter() {
                                return `${this.value > 0 ? '+' : ''}${Math.round(this.value)}%`;
                            },
                            style: {
                                color: '#6b7280',
                                fontSize: '11px'
                            }
                        }
                    },
                    tooltip: {
                        formatter() {
                            if (this.series.name === 'Score') {
                                return `<strong>${this.point.name}</strong><br>Verschil: ${this.point.y > 0 ? '+' : ''}${this.point.y.toFixed(1)}%`;
                            } else {
                                const point = this.point;
                                return `<strong>${point.name}</strong><br>
                                    Verschil: ${point.score > 0 ? '+' : ''}${point.score.toFixed(1)}%<br>
                                    Geschatte range: ${point.low.toFixed(1)}% tot ${point.high.toFixed(1)}%`;
                            }
                        },
                        style: { fontSize: '12px' }
                    },
                    plotOptions: {
                        columnrange: {
                            dataLabels: { enabled: false },
                            borderRadius: 0,
                            pointWidth: 8,
                            borderWidth: 0,
                            opacity: 0.3
                        },
                        scatter: {
                            marker: {
                                symbol: 'line',
                                radius: 15,
                                lineWidth: 3
                            }
                        }
                    },
                    legend: { enabled: false },
                    credits: { enabled: false },
                    series: [{
                        name: 'Marge',
                        data: chartData.map(d => ({
                            name: d.name,
                            low: d.low,
                            high: d.high,
                            score: d.score,
                            color: d.color
                        })),
                        zIndex: 1
                    }, {
                        type: 'scatter',
                        name: 'Score',
                        data: chartData.map(d => ({
                            name: d.name,
                            x: d.x,
                            y: d.score,
                            color: d.color
                        })),
                        zIndex: 2,
                        marker: {
                            symbol: 'line',
                            radius: 15,
                            lineWidth: 3
                        }
                    }]
                });
            }

            // Events
            document.getElementById('modelSelect').addEventListener('change', e => {
                selectedModel = e.target.value;
                renderChart(selectedUseCase, selectedModel);
            });

            document.getElementById('useCaseSelect').addEventListener('change', e => {
                selectedUseCase = e.target.value;
                renderChart(selectedUseCase, selectedModel);
            });

            document.getElementById('toggleColorblindMode').addEventListener('click', () => {
                colorblindMode = !colorblindMode;
                renderChart(selectedUseCase, selectedModel);
            });

            // Initial render
            renderChart(selectedUseCase, selectedModel);
        });
    </script>
</x-layout>