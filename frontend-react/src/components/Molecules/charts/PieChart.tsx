import React, { useEffect, useRef } from 'react';
import { Chart, PieController, ArcElement, Tooltip, Legend } from 'chart.js';

Chart.register(PieController, ArcElement, Tooltip, Legend);

interface PieChartDataset {
    data: number[];
    backgroundColor: string[];
}

interface ChartProps {
    chartId: string;
    labels?: string[];
    datasets?: PieChartDataset[];
}

const PieChart: React.FC<ChartProps> = ({
    chartId,
    labels = ['Rent', 'Utilities', 'Groceries'],
    datasets = [{
        data: [5000, 2000, 3000],
        backgroundColor: ["#508D4E", "#059BFF", "#FF4069"]
    }]
}) => {
    const chartRef = useRef<HTMLCanvasElement | null>(null);
    const chartInstance = useRef<Chart<'pie'> | null>(null);

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
        // Update the chart when labels or datasets change
        if (chartInstance.current) {
            chartInstance.current.data.labels = labels;
            chartInstance.current.data.datasets = datasets;
            chartInstance.current.update();
        }
    }, [labels, datasets]);

    const renderChart = () => {
        const ctx = chartRef.current?.getContext('2d');
        if (!ctx) return;

        chartInstance.current = new Chart<'pie'>(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: datasets.map(dataset => ({
                    ...dataset,
                    data: dataset.data,
                })),
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                const value = context.raw as number;
                                return `₹${value.toFixed(2)}`;
                            },
                        },
                    },
                },
            },
        });
    };

    return (
        <div style={{ width: '190px', height: '190px' }}>
            <canvas id={chartId} ref={chartRef}></canvas>
        </div>
    );
};

export default PieChart;
