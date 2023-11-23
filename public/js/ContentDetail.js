  /*dt40.splice(0,dt40.length-40);
  var sum = 0;for (i=0;i<dt40.length;i++){sum+=dt40[i][1];}

  function selectDays(){
     var dys=parseInt(($('#seldays').val()).substring(5,7));

     dt10.splice(0,dt10.length-10);
     dt20.splice(0,dt20.length-20);
     dt30.splice(0,dt30.length-30);
     dt40.splice(0,dt40.length-40);
    if (dys==10) dt=dt10;
    if (dys==20) dt=dt20;
    if (dys==30) dt=dt30;
    if (dys==40) dt=dt40;
    var sum = 0;
    for (i=0;i<dt.length;i++){sum+=dt[i][1];}
$('#jqChart').jqChart({
                // title: { text: '' },
                // animation: { duration: 1 },
                // series: [
                //     {
                //         type: 'area',
                //         title: 'Sessions',
                //         color:'red',
                //         fillStyle: '#2d69a0',
                //         data: [['Jan22', 0], ['Jan23', 30], ['Jan24', 70],
                //                ['Jan25', 60], ['Jan26', 65], ['Jan27', 80], ['Jan28', 90]]
                //     },
                //     {
                //         type: 'area',
                //         title: 'Prospects',
                //         fillStyle: '#327aba',
                //         data: [['Jan22', 0], ['Jan23', 20], ['Jan24', 50],
                //                ['Jan25', 45], ['Jan26', 55], ['Jan27', 75], ['Jan28', 60]]
                //     },
                   
                //     {
                //         type: 'area',
                //         title: 'Leads',
                //         fillStyle: '#272a52',
                //         data: [['Jan22', 0], ['Jan23', 10], ['Jan24', 20],
                //                ['Jan25', 15], ['Jan26', 40], ['Jan27', 33], ['Jan28', 50]]
                //     }
                // ]
                title: { text: '' },
                animation: { duration: 1 },
                series: [
                    {
                        type: 'area',
                        title: pg,
                        color:'red',
                        fillStyle: '#1568b9',
                        data: dt
                    }
                   
                ]
            });

  }*/

//////////////////////////////////
/*console.log(entry_donut);
	 var donutData = [
      {label: " " , data: entry_donut.dount_google, color: "#31ca6a"},
      {label: " ", data: entry_donut.dount_bing, color: "#327aba"},
      {label: " ", data: entry_donut.dount_yahoo, color: "#e70047"},
      {label: " ", data: entry_donut.dount_ask, color: "#272a52"},
      {label: " ", data: entry_donut.dount_dogpile, color: "#f5f7ff"}

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
    });*/

/////////////////////////////////////////////////////
/*$('#jqChart_bar').jqChart({
                title: { text: '' },
                legend: { location: 'top' },
                animation: { duration: 1 },
                axes: [
                    {
                        type: 'category',
                        location: 'bottom',
                        categories: entrydate
                    }
                ],
                series: series_data
            });*/

  // {label: " " , data: 120, color: "#31ca6a"},
  //     {label: " ", data: 120, color: "#327aba"},
  //     {label: " ", data: 60, color: "#e70047"},
  //     {label: " ", data: 40, color: "#272a52"},
  //     {label: " ", data: 20, color: "#f5f7ff"}