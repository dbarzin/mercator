import { Chart } from "chart.js"
import GaugeController from './gauge-controller';
import { color } from "chart.js/helpers";

Chart.register(GaugeController);

// ===========================================================================

function createGaugeChart(ctx, value) {
    return new Chart(ctx, {
      type: 'gauge',
      data: {
        datasets: [{
          value: value,
          data: [40, 80,100],
          backgroundColor: ['#E15759', '#F28E2B', '#59A14F'],
        }]
      },
      options: {
        needle: {
          radiusPercentage: 2,
          widthPercentage: 3.2,
          lengthPercentage: 80,
          color: 'rgba(0, 0, 0, 1)'
        },
        valueLabel: {
          display: true,
          formatter: (value) => {
              return value;
            },
          color: 'rgba(255, 255, 255, 1)',
          backgroundColor: 'rgba(0, 0, 0, 1)',
          borderRadius: 5,
          padding: {
            top: 10,
            bottom: 10
          }
        },
        plugins: {
          datalabels: {
            formatter: function(value, context) {
              return null;
            }
          }
        }
      }
    });
}

//===========================================================================

// Exécuter après le chargement du DOM
document.addEventListener('DOMContentLoaded', () => {
    if (window.chartData) {
        createGaugeChart(document.getElementById('gauge_chart_div'), window.chartData.maturity);
    }
});
