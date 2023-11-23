function pathChart_plot(data,element){
var flow_contents="";
for (i=0;i<data.length;i++){
	flow_contents+='<div class="pathpart"><div class="arrow"><center><img src="img/social_icon'+(i+1)+'.png"></center></div>';
	for (j=0;j<data[i].contents.length;j++){
		if (j==0){
			flow_contents+='<div class="arrow"><center><p><i class="fa fa-chevron-down fa-2x" aria-hidden="true"></i></p></center></div><div class="stock"><div class="title toptitle"><p>'+data[i].contents[j].title+'</p>	</div><div class="contents"><p>Prospects: '+data[i].contents[j].Prospects+'</p><p>Bounce Rate: '+data[i].contents[j].BounceRate+'</p>	</div> </div>';
		}else{
			flow_contents+='<div class="arrow"><center><p><i class="fa fa-chevron-down fa-2x" aria-hidden="true"></i></p></center></div><div class="stock"><div class="title"><p>'+data[i].contents[j].title+'</p>	</div><div class="contents"><p>Prospects: '+data[i].contents[j].Prospects+'</p><p>Bounce Rate: '+data[i].contents[j].BounceRate+'</p>	</div></div>';
		}
	}
	flow_contents+='</div>';
}
// flow_contents+='<canvas id="Canvas_line" width="5500" height="600"></canvas>';
// flow_contents+='<canvas id="Canvas_curve" width="5500" height="600"></canvas>';

 $(element).append(flow_contents);

// var canvas = document.getElementById('Canvas_line');
//   if (canvas.getContext) {
//     var ctx = canvas.getContext('2d');
     

//     ctx.lineWidth = 3;
//     ctx.strokeStyle = '#31ca6a';
//     ctx.arc(104,47,3,0,2*Math.PI, false);
//     ctx.arc(175,47,3,0,2*Math.PI, false);
//   var startX=[];
//     for (i=1;i<data.length;i++){
//     ctx.moveTo(133+160*i,47);
//     ctx.arc(133+160*i,47,3,0,2*Math.PI, false);
//     ctx.arc(133+160*i+45,47,3,0,2*Math.PI, false);
//     startX.push(133+160*i);
// 	}
// 	ctx.stroke();
	
// var canvas1 = document.getElementById('Canvas_curve');
// 	var ctx1 = canvas1.getContext('2d');
// 	ctx1.strokeStyle = '#9d9d9d';
// 	ctx1.lineWidth = 3;
// 	for (i=1;i<data.length;i++){
// 		for (j=1;j<data[i].contents.length;j++){
// 			ctx1.moveTo(startX[i-1],27);
// 			// ctx1.arc(startX[i-1],27,3,0,2*Math.PI, false);
// 			// ctx1.arc(startX[i-1]+45,27+100*j,3,0,2*Math.PI, false);

// 			//ctx.moveTo(100,50);
// 			//ctx.bezierCurveTo(150, 50, 150, 100, 150,100);
//     		//ctx.bezierCurveTo(150,100,150,300,200,300);

// 			ctx1.bezierCurveTo(startX[i-1]+20,27, startX[i-1]+23, 50, startX[i-1]+22,70);
//     		ctx1.bezierCurveTo(startX[i-1]+22,70,startX[i-1]+20,27+100*j-50,startX[i-1]+45,27+100*j);
//     		ctx1.arc(startX[i-1]+45,27+100*j,3,0,2*Math.PI, false);
// 		}
// 	}
// 	ctx1.stroke();

	



    
    

  }
   

