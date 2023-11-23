var donutData1 = [
		{label: " " , data: 60, color: "#31ca6a"},
		{label: " ", data: 60, color: "#327aba"},
		{label: " ", data: 15, color: "#fc5d56"},
		{label: " ", data: 15, color: "#e70047"}
	];
	// show_donut(donutData1);
function show_donut(donutData1){
	console.log(donutData1);
	$("#donut-chart1").css('height','200px');
	$.plot("#donut-chart1", donutData1, {
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


	// $("#chartContainer").CanvasJSChart({ 
	// 	title: { 
	// 		text: "Percentage Population of Countries in EU - 2007" 
	// 	}, 
	// 	data: [ 
	// 	{ 
	// 		type: "doughnut", 
	// 		indexLabel: "{label}: {y}%",
	// 		toolTipContent: "{label}: {y}%",
	// 		dataPoints: [ 
	// 			{ label: "Germany",       y: 16.6}, 
	// 			{ label: "France",        y: 12.8}, 
	// 			{ label: "United Kingdom",y: 12.3}, 
	// 			{ label: "Italy",         y: 11.9}, 
	// 			{ label: "Spain",         y: 9.0}, 
	// 			{ label: "Poland",        y: 7.7}, 
	// 			{ label: "Other (21 Countries)",y: 29.7} 
	// 		] 
	// 	} 
	// 	] 
	// });
}

    // var donutData2 = [
      
    //   {label: " ", data: 120, color: "#327aba"},
    //   {label: " ", data: 40, color: "#fc5d56"},
    //   {label: " ", data: 20, color: "#31ca6a"}

    // ];
    // $("#donut-chart2").css('height','200px');
    // $.plot("#donut-chart2", donutData2, {
    //   series: {
    //     pie: {
    //       show: true,
    //       radius: 1,
    //       innerRadius: 0.5,
    //       label: {
    //         show: true,
    //         radius: 2 / 3,
             
    //         threshold: 0.1
    //       }

    //     }
    //   },
    //   legend: {
    //     show: false
    //   }
    // });
