var months = new Array(12);
months[0] = "January";
months[1] = "February";
months[2] = "March";
months[3] = "April";
months[4] = "May";
months[5] = "June";
months[6] = "July";
months[7] = "August";
months[8] = "September";
months[9] = "October";
months[10] = "November";
months[11] = "December";
// date("d-m-Y", strtotime($bc->Date))  
var bar = new Morris.Bar({
      element: 'bar-chart',
      resize: true,      
      data: [

     {y: months, a: 100, b: 10},
     {y: months, a: 900, b: 3000},
     {y: months, a: 1000, b: 100},
     {y: months, a: 500, b: 600},
     {y: months, a: 5000, b: 200},
     {y: months, a: 200, b: 10},
     {y: months, a: 3000, b: 70},
     {y: months, a: 20, b: 10},
      ],      
      barColors: ['#00a65a', '#e34b56'],
      xkey: 'y',
      ykeys: ['a', 'b'],
      labels: ['Unique Leads', 'Total Visitors'],
      hideHover: 'auto',
      stacked: true,
      axes: true,
      grid: false,
      barSize: 20,
      barGap: 0.05,
      barSizeRatio: 1,
      resize: true,
      width: true,
      xLabelMargin: 10
     
    });
