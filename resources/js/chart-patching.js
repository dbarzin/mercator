import { Chart, BarController, BarElement, CategoryScale, LinearScale, Legend, DoughnutController, ArcElement } from "chart.js"
import ChartDataLabels from "chartjs-plugin-datalabels"
import { color } from "chart.js/helpers";

Chart.register(BarController, DoughnutController, ArcElement, BarElement, CategoryScale, LinearScale, ChartDataLabels);

window.Chart=Chart;
