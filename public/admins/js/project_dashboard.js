// 01.
const taskOverviewOptions = {
  series: [
    {
      name: "Tasks",
      data: [18.5, 19.2, 20, 19.5, 21, 20.8, 19.9]
    }
  ],
  chart: {
    type: 'line',
    height: 40,
    width: 100,
    offsetY: 0,
    offsetX: 0,
    toolbar: { show: false },
    sparkline: { enabled: true }
  },
  stroke: {
    width: 2,
    curve: 'smooth'
  },
  dataLabels: { enabled: false },
  fill: {
    type: "gradient",
    gradient: {
      shadeIntensity: 1,
      colorStops: [
        { offset: 0, color: 'rgba(var(--primary),.4)', opacity: 1 },
        { offset: 100, color: 'rgba(var(--primary),.1)', opacity: 1 }
      ]
    }
  },
  colors: ['rgba(var(--primary))'],
  xaxis: { show: false },
  yaxis: { show: false },
  grid: { show: false },
  tooltip: {
    enabled: false
  },
  responsive: [{
    breakpoint: 1024,
    options: {
      chart: {
        height: 40,
        width: 60,
      },
    }
  }]
};

chart = new ApexCharts(document.querySelector("#taskOverview"), taskOverviewOptions);
chart.render();

// 02.
const taskOverviewOptions1 = {
  series: [
    {
      name: "Tasks",
      data: [18.5, 19.2, 20, 19.5, 21, 20.8, 19.9]
    }
  ],
  chart: {
    type: 'line',
    height: 40,
    width: 100,
    offsetY: 0,
    offsetX: 0,
    toolbar: { show: false },
    sparkline: { enabled: true }
  },
  stroke: {
    width: 2,
    curve: 'smooth'
  },
  dataLabels: { enabled: false },
  fill: {
    type: "gradient",
    gradient: {
      shadeIntensity: 1,
      colorStops: [
        { offset: 0, color: 'rgba(var(--primary),.4)', opacity: 1 },
        { offset: 100, color: 'rgba(var(--primary),.1)', opacity: 1 }
      ]
    }
  },
  colors: ['rgba(var(--primary))'],
  xaxis: { show: false },
  yaxis: { show: false },
  grid: { show: false },
  tooltip: {
    enabled: false
  },
  responsive: [{
    breakpoint: 1024,
    options: {
      chart: {
        height: 40,
        width: 60,
      },
    }
  }]
};

chart = new ApexCharts(document.querySelector("#taskOverview1"), taskOverviewOptions1);
chart.render();

// 03.
const taskOverviewOptions2 = {
  series: [
    {
      name: "Tasks",
      data: [18.5, 19.2, 20, 19.5, 21, 20.8, 19.9]
    }
  ],
  chart: {
    type: 'line',
    height: 40,
    width: 100,
    offsetY: 0,
    offsetX: 0,
    toolbar: { show: false },
    sparkline: { enabled: true }
  },
  stroke: {
    width: 2,
    curve: 'smooth'
  },
  dataLabels: { enabled: false },
  fill: {
    type: "gradient",
    gradient: {
      shadeIntensity: 1,
      colorStops: [
        { offset: 0, color: 'rgba(var(--primary),.4)', opacity: 1 },
        { offset: 100, color: 'rgba(var(--primary),.1)', opacity: 1 }
      ]
    }
  },
  colors: ['rgba(var(--primary))'],
  xaxis: { show: false },
  yaxis: { show: false },
  grid: { show: false },
  tooltip: {
    enabled: false
  },
  responsive: [{
    breakpoint: 1024,
    options: {
      chart: {
        height: 40,
        width: 60,
      },
    }
  }]
};

chart = new ApexCharts(document.querySelector("#taskOverview2"), taskOverviewOptions2);
chart.render();

// 04.

var options = {
  series: [22, 48, 16, 11],
  chart: {
    height: 200,
    type: 'donut'
  },
  plotOptions: {
    pie: {
      startAngle: 10,

      donut: {
        size: '80%',
        dataLabels: {
          enabled: false
        },
      }
    }
  },
  labels: ['18 - 24 years', '25 - 40 years', '40 - 55 years', '55+ years'],
  colors: ['rgba(var(--primary),1)', 'rgba(var(--primary),.8)', 'rgba(var(--primary),.6)', 'rgba(var(--primary),.4)'],
  legend: {
    show: false
  },
  dataLabels: {
    enabled: false
  },
  responsive: [{
    breakpoint: 1400,
    options: {
      chart: {
        height: 200
      },
    }
  },
    {
      breakpoint: 992,
      options: {
        chart: {
          height: 180
        },
      }
    }
  ]

};

var chart = new ApexCharts(document.querySelector("#audienceChart"), options);
chart.render();


var options = {
  series: [{
    name: 'Website Blog',
    type: 'column',
    data: [20, 25, 30, 35, 40, 50, 60]
  }, 
  
  {
    name: 'Social Media',
    type: 'line',
    data: [25, 25, 50, 25, 40],
    color: 'green',
    stroke: {
      curve: 'smooth',
      width: 2
    },
  }],
  chart: {
    height: 180,
    type: 'line',
  },
  colors:['rgba(var(--white),1)','rgba(var(--white),1)'],
  stroke: {
    curve: 'smooth',
    width: [0, 2]
  },

  plotOptions: {
    bar: {
      columnWidth: '4px',
      distributed: true,
      // borderRadius: 8,
    }
  },
  yaxis: {
    show: false,
    labels: {
      show: false
    },
    axisBorder: {
      show: false
    },
    axisTicks: {
      show: false
    }
  },
  xaxis: {
    show: true,
    categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
    labels: {
      show: true,
      style: {
        colors: '#fff', // Adjust color for visibility if needed
        fontSize: '12px'
      }
    },
    axisBorder: {
      show: false
    },
    axisTicks: {
      show: false
    }
  },
  grid: {
    show: false,
    xaxis: {
      lines: {
        show: false
      }
    },
    yaxis: {
      lines: {
        show: false
      }
    },

  },
  tooltip: {
    enabled: false,
  },
  legend: {
    show: false
  },
};

var chart = new ApexCharts(document.querySelector("#profitOverview"), options);
chart.render();

// 05. slick slider js

$('.updates-box-slider').slick({
  infinite: true,
  slidesToShow: 1,
  slidesToScroll: 1,
  dots: true,
  arrows: false,
  autoplay: true,
  autoplaySpeed: 4000,
});

// 06.

document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".task-check").forEach(checkbox => {
    checkbox.addEventListener("change", function () {
      const nameElement = this.closest("li").querySelector(".client-name");
      if (this.checked) {
        nameElement.style.textDecoration = "line-through";
        nameElement.style.color = "#888"; // Optional: Dim the text color
      } else {
        nameElement.style.textDecoration = "none";
        nameElement.style.color = ""; // Reset text color
      }
    });
  });
});
