var userOptions = {
  series: [{
      name: "Users",
      data: [2, 5, 9, 10]
  }],
  chart: {
      height: 200,
      type: 'line',
      zoom: {
          enabled: false
      }
  },
  dataLabels: {
      enabled: false
  },
  stroke: {
      curve: 'straight'
  },
  title: {
      text: 'Total users per month',
      align: 'center'
  },
  grid: {
      row: {
          colors: ['#f3f3f3', 'transparent'],
          opacity: 0.5
      },
  },
  xaxis: {
      categories: ['Jan', 'Feb', 'Mar', 'Apr'],
  },
};
  var userChart = new ApexCharts(document.querySelector("#userchart"), userOptions);
  userChart.render();
