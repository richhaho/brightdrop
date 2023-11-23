var donutData = [
      {label: " " , data: 11543, color: "#23cc69"},
      {label: " ", data: 18765, color: "#eb264d"},
      {label: " ", data: 12345, color: "#3480c2"} //#eb264d
    ];
    $.plot("#donut-chart", donutData, {
      series: {
        pie: {
          show: true,
          radius: 1,
          innerRadius: 0.5,
          label: {
            show: true,
            radius: 2 / 3,
            formatter: labelFormatter,
            threshold: 0.1
          }

        }
      },
      legend: {
        show: false
      }
    });
    /*
     * END DONUT CHART
     */
     function labelFormatter(label, series) {
    return '<div style="font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;">'
        + label
        + "<br>"
        + series.percent.toFixed(2) + "%</div>";
       
  }