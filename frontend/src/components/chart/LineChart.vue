<template>
    <div style="height: 100%">
        <canvas :id="chartId"></canvas>
    </div>
</template>

<script>
import { Chart, registerables } from "chart.js";

Chart.register(...registerables);

export default {
    name: "LineChart",
    props: {
        chartId: {
            type: String,
            default: "bar-chart",
        },
        labels: {
            type: Array,
            required: true,
        },
        datasets: {
            type: Array,
            required: true,
        },
    },
    watch: {
        labels: "updateChart",
        datasets: "updateChart",
    },
    mounted() {
        this.renderChart();
    },
    methods: {
        renderChart() {
            const ctx = document.getElementById(this.chartId).getContext("2d");

            new Chart(ctx, {
                type: "line",
                data: {
                    labels: this.labels,
                    datasets: this.datasets.map((dataset) => ({
                        ...dataset,
                        borderCapStyle: "round",
                        borderJoinStyle: "round",
                        borderWidth: 3,
                        tension: 0.3,
                    })),
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            labels: {
                                color: "rgba(0, 0, 0, 0.8)", // Legend text color
                                padding: 10, // Padding around the text
                                boxWidth: 40, // Width of the color box
                                boxHeight: 10, // Height of the color box
                                generateLabels: function (chart) {
                                    // Remove the fill color from the legend
                                    const originalLabels =
                                        Chart.defaults.plugins.legend.labels.generateLabels(
                                            chart
                                        );
                                    return originalLabels.map((label) => ({
                                        ...label,
                                        fillStyle: "transparent", // Set the fill style to transparent
                                    }));
                                },
                            },
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    // Get the value from the dataset
                                    const value = context.raw;
                                    // Format the value with the rupee symbol
                                    return `₹${value.toFixed(2)}`;
                                },
                            },
                        },
                    },
                    scales: {
                        x: {
                            tickLength: 0,
                            drawOnChartArea: false,
                            ticks: {
                                tickLength: 0,
                                font: {
                                    size: 14,
                                },
                                color: "rgba(0, 0, 0, 0.8)",
                            },
                            grid: {
                                display: false,
                            },
                        },
                        y: {
                            tickLength: 0,
                            ticks: {
                                display: true,
                                callback: function (value) {
                                    return "₹" + value;
                                },
                            },
                            drawOnChartArea: false,
                            grid: {
                                color: "rgba(0, 0, 0, 0.3)",
                            },
                            border: {
                                display: false,
                                dash: [3, 3],
                            },
                        },
                    },
                },
            });
        },
        updateChart() {
            if (this.chart) {
                this.chart.data.labels = this.labels;
                this.chart.data.datasets = this.datasets;
                this.chart.update(); // Update the chart with new data
            }
        },
    },
};
</script>

<style scoped></style>
