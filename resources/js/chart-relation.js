import { Chart, Filler, LineController, TimeScale, LineElement, PointElement, LinearScale, Title, CategoryScale } from 'chart.js';
import 'chartjs-adapter-date-fns';
Chart.register(LineController, Filler, TimeScale, LineElement, PointElement, LinearScale, Title, CategoryScale);

document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('chart_value_canvas').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            datasets: [{
                label: "value",
                data: window.data,
                borderColor: 'grey',
                backgroundColor: 'rgba(128, 128, 128, 0.3)',
                fill: true,
                tension: 0.4 
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false,
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                },
                x: {
                    type: 'time',
                    title: {
                        display: false,
                    },
                    ticks: {
                        major: {
                            font: {
                                weight: 'bold',
                                size: 12
                            },
                            color: '#FF0000'
                        }
                    }
                }
            }
        }
    });
});
