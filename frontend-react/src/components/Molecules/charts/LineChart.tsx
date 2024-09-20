import React, { useEffect, useRef } from 'react'
import { Chart, registerables } from "chart.js";

Chart.register(...registerables);

interface ChartProps {
    chartId: string;
    labels: string[];
    datasets: any;
}

const LineChart: React.FC<ChartProps> = ({ chartId, labels, datasets }) => {

    const chartRef = useRef<HTMLCanvasElement | null>(null);
    const chartInstance = useRef<Chart | null>(null);

    useEffect(() => {
        // Render the chart when the component mounts
        renderChart();

        // Cleanup chart instance when the component unmounts
        return () => {
            if (chartInstance.current) {
                chartInstance.current.destroy();
            }
        };
    }, []);

    useEffect(() => {
        // Update the chart when props (labels or datasets) change
        if (chartInstance.current) {
            chartInstance.current.data.labels = labels;
            chartInstance.current.data.datasets = datasets;
            chartInstance.current.update();
        }
    }, [labels, datasets]);

    const renderChart = () => {
        const ctx = chartRef.current?.getContext("2d");
        if (!ctx) return;

        chartInstance.current = new Chart(ctx, {
            type: "line",
            data: {
                labels,
                datasets: datasets.map((dataset: any) => ({
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
                            label: (context) => {
                                const value = context.raw as number;
                                return `₹${value.toFixed(2)}`;
                            },
                        },
                    },
                },
                scales: {
                    x: {
                        ticks: {
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
                        ticks: {
                            display: true,
                            callback: (value) => `₹${value}`,
                        },
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
    };


    return (
        <div style={{ height: "100%" }}>
            <canvas id={chartId} ref={chartRef}></canvas>
        </div>
    );
}

export default LineChart