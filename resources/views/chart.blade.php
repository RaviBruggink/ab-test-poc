<x-layout>
    <div class="relative text-sm">
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

        <h3 class="text-5xl font-semibold my-16 ml-14">Models</h3>

        <div class="flex flex-wrap gap-4 mb-10 ml-14">
            <div>
                <label for="useCaseSelect" class="text-xs font-semibold text-gray-600 block mb-1">Use Case</label>
                <select id="useCaseSelect"
                    class="text-md border border-gray-300 rounded px-2 py-2 focus:ring-2 focus:ring-indigo-500">
                    <option value="__average__">All</option>
                    @foreach ($useCases as $uc)
                        <option value="{{ $uc }}">{{ $uc }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="modelSelect" class="text-xs font-semibold text-gray-600 block mb-1">Baseline</label>
                <select id="modelSelect"
                    class="text-md border border-gray-300 rounded px-2 py-2 focus:ring-2 focus:ring-indigo-500">
                    <option value="__all__">None</option>
                    @foreach ($models as $model)
                        <option value="{{ $model['label'] }}">{{ $model['label'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="">
            <div id="modelChartContainer" class="w-full overflow-x-auto"></div>
        </div>

    </div>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/xrange.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const models = @json($models);
            const modelScores = @json($grouped);
            const useCases = @json($useCases);

            let selectedModel = "__all__";
            let selectedUseCase = useCases[0] || "__average__";
            let colorblindMode = false;

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
                const xrangeData = [];
                const markerData = [];

                const isBaseline = baselineLabel !== '__all__';
                const baselineValue = isBaseline ? (modelScores[baselineLabel]?.[useCase] || 1) : null;

                models.forEach((m, index) => {
                    const value = modelScores[m.label]?.[useCase] ?? 0;
                    const color = colorblindMode ? (colorblindColors[m.label] || m.color) : m.color;

                    let score, margin, label;
                    if (isBaseline) {
                        const diff = ((value - baselineValue) / baselineValue) * 100;
                        score = parseFloat(diff.toFixed(1));
                        label = `${score > 0 ? '+' : ''}${score}%`;
                    } else {
                        score = parseFloat(value.toFixed(1));
                        label = `${score}`;
                    }

                    margin = isBaseline
                        ? (m.label === baselineLabel ? 0 : Math.max(2, Math.abs(score * 0.15)))
                        : Math.max(1, score * 0.08);


                    xrangeData.push({
                        x: score - margin,
                        x2: score + margin,
                        y: index,
                        name: m.label,
                        midpoint: score,
                        color,
                        score
                    });

                    markerData.push({
                        x: score,
                        y: index,
                        name: m.label,
                        label,
                        color,
                        score
                    });
                });

                const lowestX = Math.min(...xrangeData.map(d => d.x));
                let labelXPosition;

                if (isBaseline) {
                    const marginLeft = Math.max(10, Math.abs(lowestX) * 0.5);
                    labelXPosition = lowestX - marginLeft;
                } else {
                    const minSafeLeft = Math.min(0, lowestX - 20);
                    labelXPosition = minSafeLeft;
                }

                const labelData = models.map((m, idx) => ({
                    x: labelXPosition,
                    y: idx,
                    name: m.label
                }));

                const plotBands = models.map((m, i) => ({
                    from: i - 0.5,
                    to: i + 0.5,
                    color: i % 2 === 0 ? '#fafafa' : '#ffffff'
                }));

                const chartWidth = container.offsetWidth;
                const labelXOffset = -chartWidth / 2 + 60;  // Pas 60 aan naar wens voor marge


                container.style.height = `${Math.max(models.length * 60, 400)}px`;

                Highcharts.chart('modelChartContainer', {
                    chart: {
                        type: 'xrange',
                        backgroundColor: 'transparent',
                        margin: [10, 0, 80, 0],
                    },
                    title: { text: null },
                    xAxis: {
                        tickColor: '#CECECE',
                        tickWidth: 1,
                        min: labelXPosition - 10,
                        lineColor: '#CECECE',
                        lineWidth: 1,
                        title: {
                            text: isBaseline ? 'Verschil t.o.v. baseline (%)' : 'Absolute score',
                            enabled: false,
                        },
                        gridLineWidth: 0,
                        plotLines: isBaseline ? [{
                            color: '#9ca3af',
                            width: 1,
                            value: 0,
                            zIndex: 1,
                            dashStyle: 'Dash'
                        }] : [],
                        labels: {
                            formatter() {
                                const v = this.value;
                                return isBaseline ? `${v > 0 ? '+' : ''}${Math.round(v)}%` : `${Math.round(v)}`;
                            },
                            style: {
                                color: '#CECECE',
                                fontSize: '11px',
                                fontWeight: '600'
                            }
                        }
                    },
                    yAxis: {
                        categories: models.map(m => m.label),
                        reversed: true,
                        plotBands: plotBands,
                        gridLineWidth: 0,
                        labels: {
                            enabled: false,
                            useHTML: true,
                            formatter: function () {
                                const i = this.pos;
                                const bgColor = i % 2 === 0 ? '#fafafa' : '#ffffff';
                                return `<span style="
                                    display: flex;
                                    background-color: ${bgColor};
                                    padding: 4px 6px 0px 20px;
                                    min-width: 140px;
                                    min-height: 47px;
                                    text-align: left;
                                    box-sizing: border-box;
                                    width: 100%;
                                    justify-content: start;
                                    align-items: center;
                                ">${this.value}</span>`;
                            },
                            style: {
                                color: '#374151',
                                fontSize: '12px',
                                fontWeight: '500',
                                whiteSpace: 'nowrap'
                            }
                        }
                    },
                    tooltip: {
                        outside: true,
                        useHTML: true,
                        borderWidth: 0,
                        shadow: false,
                        backgroundColor: 'transparent',
                        formatter() {
                            return `
                                <div style="padding: 8px 12px; font-family: sans-serif; background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                    <strong style="display:block; font-size:13px; margin-bottom: 4px; color:#111; justify-content: center; text-align: center;">
                                        ${this.point.name}
                                    </strong>
                                    <div style="font-size:12px;">
                                        <div style="width: 144px; padding: 10px; border-radius: 8px;">
                                            <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                                                <span style="color: #6b7280; font-size: 10px;">Score:</span>
                                                <span style="background: #111; color: white; font-size: 11px; padding: 2px 6px; border-radius: 4px; width:50px; justify-content: center; text-align: center;">
                                                    ${isBaseline
                                                        ? `${this.point.score > 0 ? '+' : ''}${this.point.score.toFixed(1)}%`
                                                        : `${this.point.score.toFixed(1)}`
                                                    }
                                                </span>
                                            </div>
                                            <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                                                <span style="color: #6b7280; font-size: 10px;">Confidence:</span>
                                                <span style="background: #10b981; color: white; font-size: 11px; padding: 2px 6px; border-radius: 4px; width:50px; justify-content: center; text-align: center;">+5%</span>
                                            </div>
                                            <div style="display: flex; justify-content: space-between;">
                                                <span style="color: #6b7280; font-size: 10px;">Failsafe:</span>
                                                <span style="background: #ef4444; color: white; font-size: 11px; padding: 2px 6px; border-radius: 4px; width:50px; justify-content: center; text-align: center;">-1.2%</span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Tooltip pointer -->
                                    <div style="
                                        position: absolute;
                                        top: 100%;
                                        left: 50%;
                                        transform: translateX(-50%);
                                        width: 0;
                                        height: 0;
                                        border-left: 6px solid transparent;
                                        border-right: 6px solid transparent;
                                        border-top: 6px solid white;
                                    "></div>
                                </div>
                            `;
                        },
                        style: {
                            fontSize: '12px',
                            pointerEvents: 'auto'
                        }
                    },
                    plotOptions: {
                        xrange: {
                            borderWidth: 0,
                            pointPadding: 0.3,
                            groupPadding: 0.1,
                            pointWidth: 15,
                            borderRadius: 999,
                            dataLabels: { enabled: false },
                            states: {
                                inactive: { enabled: false }
                            }
                        },
                        scatter: {
                            hover: {
                                enabled: false,
                            },
                            enableMouseTracking: false,
                            marker: {
                                symbol: 'line',
                                lineWidth: 2,
                                lineColor: '#111',
                                radius: 4
                            },
                            states: {
                                inactive: {
                                    enabled: false
                                }
                            }
                        }
                    },
                    legend: { enabled: false },
                    credits: { enabled: false },
                    exporting: { enabled: false },
                    series: [
                        {
                            name: 'Labels',
                            type: 'scatter',
                            data: labelData,
                            enableMouseTracking: false,
                            marker: { enabled: false },
                            dataLabels: {
                                enabled: true,
                                align: 'left',
                                x: labelXOffset,
                                verticalAlign: 'middle',
                                formatter() {
                                    return `
                                        <div style="
                                            display: flex;
                                            align-items: center;
                                            padding-left: 60px;
                                            border-radius: 6px;
                                            font-size: 16px;
                                            font-weight: 300;
                                            color: #111827;
                                            font-family: sans-serif;
                                        ">
                                            ${this.point.name}
                                        </div>
                                    `;
                                },
                                useHTML: true,
                                style: {
                                    color: '#111827',
                                    fontSize: '13px',
                                    fontWeight: '600',
                                    textOutline: 'none',
                                    opacity: 1
                                }
                            }
                        },
                        { name: 'Interval', data: xrangeData },
                        { type: 'scatter', name: 'Score', data: markerData, zIndex: 3 }
                    ]
                });
            }

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

            renderChart(selectedUseCase, selectedModel);
        });
    </script>
</x-layout>