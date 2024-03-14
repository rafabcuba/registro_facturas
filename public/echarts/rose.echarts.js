/*
* Gráfica de rosa del estado de expiración de los certificados
* para ser utilizada con "echarts"
*/

var myChart = echarts.init(document.getElementById('main'));
window.onresize = function() {
  myChart.resize();
};

var data = document.querySelector('.js-rosechart');
  resumen = JSON.parse(data.dataset.resumen);
  // console.log(typeof(resumen));
  // console.log(resumen);
  // console.log(resumen[0].ok);
  // ok = resumen[0].ok;
  // console.log(ok);

var option;

option = {
  legend: {
    top: 'bottom'
  },
  tooltip: {},
  toolbox: {
    show: true,
    feature: {
      mark: { show: true },
      dataView: { show: true, readOnly: false },
      restore: { show: true },
      saveAsImage: { show: true }
    }
  },
  series: [
    {
      name: 'Resumen de estado',
      type: 'pie',
      // radius: [50, 250],
      radius: ['20%', '50%'],
      center: ['25%', '30%'],
      roseType: 'area',
      itemStyle: {
        borderRadius: 8
      },
      data: [
        { value: resumen[0].expired, name: 'Expirados' },
        { value: resumen[0].warning, name: 'Expiran en 10 dias' },
        { value: resumen[0].ok, name: 'ok' },
      ]
    },
    {
      name: 'Resumen de estado',
      type: 'pie',
      // radius: [50, 250],
      radius: ['60%'],
      center: ['75%', '30%'],
      itemStyle: {
        borderRadius: 8
      },
      data: [
        { value: resumen[0].expired, name: 'Expirados' },
        { value: resumen[0].warning, name: 'Expiran en 10 dias' },
        { value: resumen[0].ok, name: 'ok' },
      ]
    }
  ]
};

option && myChart.setOption(option);