import React, { useEffect, useRef } from 'react'
import { Chart, registerables } from "chart.js";

Chart.register(...registerables);

interface ChartProps {
    chartId: string;
    labels: string[];
    datasets: any;
}

const BarChart: React.FC<ChartProps> = ({ chartId, labels, datasets }) => {

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
            type: "bar",
            data: {
                labels,
                datasets,
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
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

export default BarChart