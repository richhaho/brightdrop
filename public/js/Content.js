      
//var text_string1 = "10 Ploys Fraudsters Use to Commit Identity Fraud | Verafin 12 Red Flags for &#34;Funnel Accounts&#34; Used to Launder Money | Verafin 2012 Chevrolet Cruze for sale at Hickman Chevrolet Cadillac St. John&#39;s NL 2018 Cadillac XT5 for sale at Hickman Chevrolet Cadillac St. John&#39;s NL A 2014 Honda Pilot in St. John&#39;s NL dealer Hickman Select Pre-Owned. White Diamond Pearl 4WD Touring Sport Utility. A 2015 Dodge Grand Caravan in St. John&#39;s NL dealer Hickman Select Pre-Owned. White 4dr Wgn SXT Mini-van, Passenger.";
var text_string1=text_string1+text_string1+text_string1+text_string1;
$(window).load(function(){

      drawWordCloud(text_string1,"#Word_chart1");
});
      // drawWordCloud(text_string2,"#Word_chart2");
      // drawWordCloud(text_string3,"#Word_chart3");
      // drawWordCloud(text_string4,"#Word_chart4");
      function drawWordCloud(text_string,element){
        var common = text_string;
        var word_count = {};
        var words = text_string.split(/[ '\-\(\)\*":;\[\]|{},.!?]+/);
          if (words.length == 1){
            word_count[words[0]] = 1;
          } else {
            words.forEach(function(word){
              var word = word.toLowerCase();
              if (word != "" && common.indexOf(word)==-1 && word.length>1){
                if (word_count[word]){
                  word_count[word]++;
                } else {
                  word_count[word] = 1;
                }
              }
            })
          }
        var svg_location = element;
        var width = $(element).width();
        var height = '400';//$("#chart").height();
        var fill = d3.scale.category20();
        var word_entries = d3.entries(word_count);
        var xScale = d3.scale.linear()
           .domain([0, d3.max(word_entries, function(d) {
              return d.value;
            })
           ])
           .range([10,100]);
        d3.layout.cloud().size([width, height])
          .timeInterval(20)
          .words(word_entries)
          .fontSize(function(d) { return xScale(+d.value); })
          .text(function(d) { return d.key; })
          .rotate(function() { return ~~(Math.random() * 2) * 90; })
          .font("Impact")
          .on("end", draw)
          .start();
        function draw(words) {
          d3.select(svg_location).append("svg")
              .attr("width", width)
              .attr("height", height)
            .append("g")
              .attr("transform", "translate(" + [width >> 1, height >> 1] + ")")
            .selectAll("text")
              .data(words)
            .enter().append("text")
              .style("font-size", function(d) { return xScale(d.value) + "px"; })
              .style("font-family", "Impact")
              .style("fill", function(d, i) { return fill(i); })
              .attr("text-anchor", "middle")
              .attr("transform", function(d) {
                return "translate(" + [d.x, d.y] + ")rotate(" + d.rotate + ")";
              })
              .text(function(d) { return d.key; });
        }
        d3.layout.cloud().stop();
      }
    
    $(window).resize(function(){
    	$("#Word_chart1").empty();
      $("#Word_chart2").empty();
      $("#Word_chart3").empty();
      $("#Word_chart4").empty();
    	drawWordCloud(text_string1,"#Word_chart1");
      // drawWordCloud(text_string2,"#Word_chart2");
      // drawWordCloud(text_string3,"#Word_chart3");
      // drawWordCloud(text_string4,"#Word_chart4");
	});
    // $('.tab01').click(function(){
    //   drawWordCloud(text_string1,"#Word_chart1");
    // });
    // $('.tab02').click(function(){
       
    //   drawWordCloud(text_string2,"#Word_chart2");
    // });
    // $('.tab03').click(function(){
    //   drawWordCloud(text_string3,"#Word_chart3");
    // });
    // $('.tab04').click(function(){
    //   drawWordCloud(text_string4,"#Word_chart4");
    // });
