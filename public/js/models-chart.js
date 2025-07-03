const actionsButton = document.getElementById("actionsButton");
const actionsMenu = document.getElementById("actionsMenu");

actionsButton.addEventListener("click", () => {
    actionsMenu.classList.toggle("hidden");
});

document.addEventListener("click", (e) => {
    if (!actionsButton.contains(e.target) && !actionsMenu.contains(e.target)) {
        actionsMenu.classList.add("hidden");
    }
});
document.addEventListener("DOMContentLoaded", () => {

    const container   = document.getElementById("modelChartContainer");
    const models      = JSON.parse(container.dataset.models);
    const modelScores = JSON.parse(container.dataset.scores);
    const useCases    = JSON.parse(container.dataset.usecases);

    let selectedModel = "__all__";
    let selectedUseCase = useCases[0] || "__average__";
    let colorblindMode = false;

    const colorblindColors = {
        "GPT-4o": "#0072B2",
        "Gemma (Ollama)": "#009E73",
        "Llama3": "#E69F00",
        "LLaMa 3.3": "#56B4E9",
        "Claude 3.5 Sonnet": "#CC79A7",
        "Claude 3.5 Haiku": "#F0E442",
        "Claude 3.7 Sonnet": "#D55E00",
    };

    function renderChart(useCase, baselineLabel) {
        const container = document.getElementById("modelChartContainer");
        const xrangeData = [];
        const markerData = [];

        const isBaseline = baselineLabel !== "__all__";
        const baselineValue = isBaseline
            ? modelScores[baselineLabel]?.[useCase] || 1
            : null;

        models.forEach((m, index) => {
            const value = modelScores[m.label]?.[useCase] ?? 0;
            const color = colorblindMode
                ? colorblindColors[m.label] || m.color
                : m.color;

            let score, label;

            if (isBaseline) {
                const baseline = baselineValue || 1;
                const diff = ((value - baseline) / baseline) * 100;
                score = parseFloat(diff.toFixed(1));
                label = `${score > 0 ? "+" : ""}${score}%`;
            } else {
                score = parseFloat(value.toFixed(1));
                label = `${score}`;
            }

            const margin = 2;

            xrangeData.push({
                x: score - margin,
                x2: score + margin,
                y: index,
                name: m.label,
                midpoint: score,
                color,
                score,
            });

            markerData.push({
                x: score,
                y: index,
                name: m.label,
                label,
                color,
                score,
            });
        });

        const lowestX = Math.min(...xrangeData.map((d) => d.x));
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
            name: m.label,
        }));

        const plotBands = models.map((m, i) => ({
            from: i - 0.5,
            to: i + 0.5,
            color: i % 2 === 0 ? "#f8fafc" : "#ffffff",
        }));

        const chartWidth = container.offsetWidth;
        const labelXOffset = -chartWidth / 2 + 60;

        container.style.height = `${Math.max(models.length * 60, 400)}px`;
        container.style.width = "100%";

        Highcharts.SVGRenderer.prototype.symbols.vertbar = function (
            x,
            y,
            w,
            h
        ) {
            const barWidth = 2;
            const barHeight = 14;
            const offsetX = x + (w - barWidth) / 2;
            const offsetY = y + (h - barHeight) / 2;
            return [
                "M",
                offsetX,
                offsetY,
                "L",
                offsetX + barWidth,
                offsetY,
                offsetX + barWidth,
                offsetY + barHeight,
                offsetX,
                offsetY + barHeight,
                "Z",
            ];
        };

        Highcharts.chart("modelChartContainer", {
            chart: {
                type: "xrange",
                backgroundColor: "transparent",
                margin: [10, 0, 80, 190],
            },
            title: { text: null },
            xAxis: {
                tickInterval: 10,
                tickColor: "#CECECE",
                tickWidth: 1,
                min: labelXPosition - 10,
                lineWidth: 0,
                title: {
                    text: isBaseline
                        ? "Verschil t.o.v. baseline (%)"
                        : "Absolute score",
                    enabled: false,
                },
                gridLineWidth: 1,
                gridLineColor: "#CECECE",
                plotLines: [
                    ...(isBaseline
                        ? [
                              {
                                  color: "#6B7280",
                                  width: 2,
                                  value: 0,
                                  dashStyle: "Solid",
                                  zIndex: 2,
                              },
                          ]
                        : []),
                ],
                labels: {
                    formatter() {
                        const v = this.value;
                        return isBaseline
                            ? `${v > 0 ? "+" : ""}${Math.round(v)}%`
                            : `${Math.round(v)}`;
                    },
                    style: {
                        color: "#111827",
                        fontSize: "11px",
                        fontWeight: "600",
                    },
                },
            },
            yAxis: {
                categories: models.map((m) => m.label),
                reversed: true,
                plotBands: plotBands,
                gridLineWidth: 0,
                labels: {
                    borderBottom: "2px solid #CECECE",
                    x: 0,
                    enabled: true,
                    useHTML: true,
                    formatter: function () {
                        const i = this.pos;
                        const label = this.value;
                        const bgColor = i % 2 === 0 ? "#f8fafc" : "#ffffff";

                        const model = models.find((m) => m.label === label);
                        const color = colorblindMode
                            ? colorblindColors[label] || model?.color || "#000"
                            : model?.color || "#000";

                        return `
                                    <div style="
                                        display: flex;
                                        align-items: center;
                                        background-color: ${bgColor};
                                        min-height: 47px;
                                        width: 300px;
                                        padding-left: 160px;
                                        box-sizing: border-box;
                                        font-family: inter, sans-serif;
                                        font-size: 14px;
                                        font-weight: 400;
                                        color: #444142;
                                    ">
                                        <span style="
                                            width: 10px;
                                            height: 10px;
                                            border-radius: 50%;
                                            background-color: ${color};
                                            margin-left: 8px;
                                            flex-shrink: 0;
                                        "></span>
                                        <span style="flex: 1; text-align: left; padding-left: 8px;">${label}</span>
                                    </div>
                                `;
                    },
                    style: {
                        color: "#111827",
                        fontSize: "14px",
                        fontWeight: "100",
                        whiteSpace: "nowrap",
                    },
                },
            },
            tooltip: {
                outside: true,
                useHTML: true,
                borderWidth: 0,
                shadow: false,
                backgroundColor: "transparent",
                formatter() {
                    return `
                                <div style="padding: 8px 12px; font-family: sans-serif; background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border: 1px solid #E5E7EB; position: relative;">
                                    <strong style="display:block; font-size:13px; margin-bottom: 4px; color:#111; justify-content: center; text-align: center;">
                                        ${this.point.name}
                                    </strong>
                                    <div style="font-size:12px;">
                                        <div style="width: 144px; padding: 10px; border-radius: 8px;">
                                            <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                                                <span style="color: #6b7280; font-size: 10px;">Score:</span>
                                                <span style="background: #111; color: white; font-size: 11px; padding: 2px 6px; border-radius: 4px; width:50px; justify-content: center; text-align: center;">
                                                    ${
                                                        isBaseline
                                                            ? `${
                                                                  this.point
                                                                      .score > 0
                                                                      ? "+"
                                                                      : ""
                                                              }${this.point.score.toFixed(
                                                                  1
                                                              )}%`
                                                            : `${this.point.score.toFixed(
                                                                  1
                                                              )}`
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
                    fontSize: "12px",
                    pointerEvents: "auto",
                },
            },
            plotOptions: {
                xrange: {
                    borderColor: "#00000000",
                    pointPadding: 0.3,
                    groupPadding: 0.1,
                    pointWidth: 15,
                    borderRadius: 100,
                    dataLabels: { enabled: false },
                    states: {
                        inactive: { enabled: false },
                    },
                },
                scatter: {
                    hover: {
                        enabled: false,
                    },
                    enableMouseTracking: false,
                    marker: {
                        symbol: "vertbar",
                        fillColor: "#111827",
                    },
                    states: {
                        inactive: {
                            enabled: false,
                        },
                    },
                },
            },
            legend: { enabled: false },
            credits: { enabled: false },
            exporting: { enabled: false },
            series: [
                {
                    name: "Labels",
                    type: "scatter",
                    data: labelData,
                    enableMouseTracking: false,
                    marker: { enabled: false },
                    dataLabels: {
                        enabled: false,
                        align: "left",
                        x: labelXOffset,
                        verticalAlign: "middle",
                        formatter() {
                            return `
                                        <div style="
                                            display: flex;
                                            align-items: center;
                                            padding-left: 60px;
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
                            color: "#111827",
                            fontSize: "13px",
                            fontWeight: "600",
                            textOutline: "none",
                            opacity: 1,
                        },
                    },
                },
                { name: "Interval", data: xrangeData },
                { type: "scatter", name: "Score", data: markerData, zIndex: 3 },
            ],
        });
    }

    document.getElementById("modelSelect").addEventListener("change", (e) => {
        selectedModel = e.target.value;
        renderChart(selectedUseCase, selectedModel);
    });

    document.getElementById("useCaseSelect").addEventListener("change", (e) => {
        selectedUseCase = e.target.value;
        renderChart(selectedUseCase, selectedModel);
    });

    document
        .getElementById("toggleColorblindMode")
        .addEventListener("click", () => {
            colorblindMode = !colorblindMode;
            renderChart(selectedUseCase, selectedModel);
        });

    renderChart(selectedUseCase, selectedModel);
});
