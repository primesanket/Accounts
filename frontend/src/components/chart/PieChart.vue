<template>
    <div style="width: 100%">
        <canvas :id="chartId" style="height: auto"></canvas>
    </div>
</template>

<script>
import { Chart, registerables } from "chart.js";

Chart.register(...registerables);

export default {
    props: {
        chartId: {
            type: String,
            default: "pie-chart",
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
    data() {
        return {
            chart: null, // Store the Chart instance
        };
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
            const ctx = document.getElementById(this.chartId)?.getContext("2d");

            if (ctx) {
                this.chart = new Chart(ctx, {
                    type: "pie",
                    data: {
                        labels: this.labels,
                        datasets: this.datasets,
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display: false,
                            },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        const value = context.raw;
                                        return `₹${value.toFixed(2)}`;
                                    },
                                },
                            },
                        },
                    },
                });
            } else {
                console.error(
                    `Canvas element with id '${this.chartId}' not found.`
                );
            }
        },
        updateChart() {
            if (this.chart) {
                this.chart.data.labels = this.labels;
                this.chart.data.datasets = this.datasets;

                // Ensure the chart is updated correctly
                this.chart.update();
            }
        },
    },
};
</script>

<style scoped></style>
