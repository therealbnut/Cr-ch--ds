function renderCharts(data){
    var chart_container = $("#data_results");
    chart_container.empty();
    $.each(data, function(index, d)
    {
        chart_container.append('<div style="width:300px;height:150px;" class="govhackchart" id="'+index+'"></div>');
        drawVisualization(d.DataArray, index, d.ChartLabel);
    });
}

function drawVisualization(data,container_id,chart_title) {
        var dataFormatted = google.visualization.arrayToDataTable(formatDataFromJSON(data, chart_title));
      
        new google.visualization.PieChart(document.getElementById(container_id)).draw(dataFormatted, {title:chart_title, backgroundColor: { fill: "#90a961", stroke: "#596b3b",strokeWidth: 0}, chartArea:{left:15,top:15,width:"300",height:"150"}});
}

function formatDataFromJSON(data, label){
    var returnArray = new Array();
    var header = new Array();
    header.push("");
    header.push(label);
    returnArray.push(header);
    
    for(var i=0; i<data.length; i++){
        var thisRow = new Array();
        thisRow.push(data[i].friendly_label);
        thisRow.push(parseFloat(data[i].value));
        returnArray.push(thisRow);
    }
        
    return returnArray;
}
