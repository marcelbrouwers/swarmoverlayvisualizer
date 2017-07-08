<?php
include_once 'config.php';
?>
<!DOCTYPE html>
<meta charset="utf-8">
<style>

.links line {
  stroke: #000000;
  stroke-opacity: 1;
}

.nodes circle {
  stroke-width: 2.5px;
}
.text {
  font: 12px helvetica;
  color: rgba(0, 0, 0, 0.3);
  pointer-events: none;
}
div.tooltip {   
  position: absolute;           
  text-align: left;           
  width: 200px;                  
  height: 150px;                 
  padding: 2px;             
  font: 14px sans-serif;        
  background: #aaaaaa;   
  border: 0px;      
  border-radius: 8px;           
  pointer-events: none;         
}

</style>
<p><?=$menu?></p>
<svg width="1400" height="1000"></svg>
<script src="https://d3js.org/d3.v4.min.js"></script>
<script>

var svg = d3.select("svg"),
    width = +svg.attr("width"),
    height = +svg.attr("height");

var color = d3.scaleOrdinal(d3.schemeCategory20);

var simulation = d3.forceSimulation()
    .force("link", d3.forceLink().id(function(d) { return d.id; }).distance(100).strength(0.6))
    .force("charge", d3.forceManyBody().strength(-100))
    .force("center", d3.forceCenter(width / 2, height / 2));
    
    

d3.json("portnetworkdata.php", function(error, graph) {
  if (error) throw error;

  var link = svg.append("g")
      .attr("class", "links")
    .selectAll("line")
    .data(graph.links)
    .enter().append("line")
      .attr("stroke-width", 2);
var div = d3.select("body").append("div")   
    .attr("class", "tooltip")               
    .style("opacity", 0);

  var node = svg.append("g")
      .attr("class", "nodes")
    .selectAll("circle")
    .data(graph.nodes)
    .enter().append("circle")
      .attr("r", function(d) { return d.size*2; })
      .attr("fill", function(d) { return color(d.group); })
      .attr("stroke", function(d){ if(d.firewall === 'ACCEPT'){return '#FF0000';}else if(d.firewall === 'DROP'){return '#00FF00';}else{return '#000000';}})
      .call(d3.drag()
          .on("start", dragstarted)
          .on("drag", dragged)
          .on("end", dragended))
          .on("mouseover", function(d) {      
            div.transition()        
                .duration(200)      
                .style("opacity", .9);      
            div .html(d.tooltip)  
                .style("left", (d3.event.pageX) + "px")     
                .style("top", (d3.event.pageY - 28) + "px");    
            })                  
        .on("mouseout", function(d) {       
            div.transition()        
                .duration(500)      
                .style("opacity", 0);   
        });
    
  var text = svg.append("g")
  .attr("class", "text")
    .selectAll("text")
    .data(graph.nodes)
    .enter().append("text")
	    .text(function(d) { return d.name; })
	      .call(d3.drag()
          .on("start", dragstarted)
          .on("drag", dragged)
          .on("end", dragended));



  simulation
      .nodes(graph.nodes)
      .on("tick", ticked);

  simulation.force("link")
      .links(graph.links);

  function ticked() {
    link
        .attr("x1", function(d) { return d.source.x; })
        .attr("y1", function(d) { return d.source.y; })
        .attr("x2", function(d) { return d.target.x; })
        .attr("y2", function(d) { return d.target.y; });

    node
        .attr("cx", function(d) { return d.x; })
        .attr("cy", function(d) { return d.y; });
    text
        .attr("x", function(d) { return d.x; })
        .attr("y", function(d) { return d.y; });
  }
});

function dragstarted(d) {
  if (!d3.event.active) simulation.alphaTarget(0.1).restart();
  d.fx = d.x;
  d.fy = d.y;
}

function dragged(d) {
  d.fx = d3.event.x;
  d.fy = d3.event.y;
}

function dragended(d) {
  if (!d3.event.active) simulation.alphaTarget(0);
  d.fx = null;
  d.fy = null;
}


</script>
