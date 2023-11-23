	 var donutData = [
      {label: " " , data: 70, color: "#e70047"},
      {label: " ", data: 30, color: "#327aba"}
    ];
    $("#donut-chart").css('height','200px');
    $.plot("#donut-chart", donutData, {
      series: {
        pie: {
          show: true,
          radius: 1,
          innerRadius: 0.5,
          label: {
            show: true,
            radius: 2 / 3,
             
            threshold: 0.1
          }

        }
      },
      legend: {
        show: false
      }
    });