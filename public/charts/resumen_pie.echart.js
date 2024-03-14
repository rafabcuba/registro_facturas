/*
* Gráfica de rosa del estado de expiración de los certificados
* para ser utilizada con "chart.js"
*/

const CHART_COLORS = {
  red: 'rgb(255, 99, 132, 0.5)',
  orange: 'rgb(255, 159, 64, 0.5)',
  yellow: 'rgb(255, 205, 86, 0.5)',
  green: 'rgb(75, 192, 192,0.5)',
  blue: 'rgb(54, 162, 235, 0.5)',
  purple: 'rgb(153, 102, 255, 0.5)',
  grey: 'rgb(201, 203, 207, 0.5)'
};

var datasource = document.querySelector('.js-datachart');
resumen = JSON.parse(datasource.dataset.resumen);

const labels = [
  'ok',
  'expiran en 10 dias',
  'expirados',
];

const data = {
  labels: labels,
  datasets: [{
    label: 'Datos del resumen',
    data: [resumen[0].ok, resumen[0].warning, resumen[0].expired],
    backgroundColor: [
          CHART_COLORS.green,
          CHART_COLORS.yellow,
          CHART_COLORS.red,
      ],
      
  }]
};

const config = {
  type: 'polarArea',
  data: data,
  options: {
    responsive: true,
    //radius: '50%',
    animation: {animateScale: 'true'},
    plugins: {
      legend: {
        position: 'top',
      },
      title: {
        display: true,
        text: 'Resumen'
      }
    }
  },
};

const resumenPieChart = new Chart(
  document.getElementById('resumenPieChart'),
  config
);
