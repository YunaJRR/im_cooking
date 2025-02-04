var commentOptions = {
    series: [{
        name: "Comments",
        data: [2, 5, 9, 3, 10, 8, 5],
    }],
    chart: {
        height: 150,
        type: 'line',
        zoom: {
            enabled: false
        }
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        curve: 'smooth',
        width: 4,
        colors: ['#008FFB']
    },
    markers: {
        size: 0,
        colors: ['#ffffff'],
        strokeColors: ['#008FFB'],
        strokeWidth: 2,
        hover: {
            size: 6
        }
    },
    title: {
        text: 'Total comments per day',
        align: 'center',
        style: {
            color: '#ffffff'
        }
    },
    
    xaxis: {
        categories: ['01,28','01/29','01,30','01/31','02/01', '02/02', '02/03'],
    }
};

var userChart = new ApexCharts(document.querySelector("#commentchart"), commentOptions);
userChart.render();