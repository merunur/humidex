<html>
<head>

    <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
    <script src="https://d3js.org/d3.v3.min.js"></script>
    <link rel="stylesheet" href="styles.css" type="text/css" />

    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.7.0/moment.min.js" type="text/javascript"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script type="text/javascript" src="https://code.highcharts.com/highcharts.js"></script>
    <script type="text/javascript" src="https://code.highcharts.com/modules/series-label.js"></script>
    <script type="text/javascript" src="https://code.highcharts.com/modules/exporting.js"></script>
</head>
<body>

<script type="text/javascript">
    var auto_refresh = setInterval(
        function ()
        {
            $('#refr').load('index2.php');
        }, 300000);
</script>
<?php
include 'db.php';
if(CONNECTED){

    //query to get data from the table
    $query = $connection->query("SELECT * 
                           FROM airquality ORDER BY id DESC 
                           LIMIT 12 ");

    $query2 = $connection->query("SELECT * 
                           FROM energy ORDER BY id DESC 
                           LIMIT 12 ");
    //loop through the returned data
    $data_temp = array();
    $data_humidity = array();
    $data_humidex = array();
    $data_energy = array();
    $data_carbon = array();
    $j = 0;
    foreach ($query as $row) {
        $data_temp[$j] = (int)$row['temperature'];
        $data_humidity[$j] = (int)$row['humidity'];
        $data_humidex[$j] = (int)$row['humidex'];
        $j++;
    }
    $i = 0;
    foreach ($query2 as $row) {
        $data_energy[$i] = (float)$row['energy'];
        $data_carbon[$i] = (float)$row['carbon'];
        $i++;

    }

    //free memory associated with result
    $query->close();
    $query2->close();


    //now print the data
    $temp = json_encode($data_temp[0]);
    $humidity = json_encode($data_humidity[0]);
    $humidex = json_encode($data_humidex[0]);
    $energy = json_encode($data_energy[0]);
    $carbon = json_encode($data_carbon[0]);


} ?>

<div id="refr">
    <div class="header">
        <h2>Air Quality Monitoring System</h2>
    </div>

    <div class="topnav">
        <a href="#"></a>
    </div>
    <div class="row" >
        <div class="leftcolumn">
            <div class="left">
                <div class="card">
                    <h2>TEMPERATURE</h2>
                    <div id="tempValue" style="height:110px;">
                        <div class="temperature">

                            <div class="temp-svg" id="temp-d3">

                            </div>
                            <script>
                                var a = <?php  echo $temp; ?>;
                                var j = (a * 100) / 40;
                                var g = (j * 100) / 100;

                                var svgContainer = d3.select("#temp-d3").append("svg")
                                    .attr("width", 50)
                                    .attr("height", 100)
                                    .attr("background", "gray");
                                if(g >= 57.5 && g <= 70){
                                    var rectangle = svgContainer.append("rect")
                                        .attr("x", 0)
                                        .attr("y",100 - g)
                                        .attr("width", 50)
                                        .attr("height", g)
                                        .attr("fill","green");
                                }else{
                                    var rectangle = svgContainer.append("rect")
                                        .attr("x", 0)
                                        .attr("y",100-g)
                                        .attr("width", 50)
                                        .attr("height", g)
                                        .attr("fill","red");
                                }
                            </script>
                            <div class="temp-value">
                                <div class="high-temp">
                                    <p>40</p>
                                </div>
                                <div class="current-temp">
                                    <p><?php  echo $temp; ?></p>
                                </div>
                                <div class="low-temp">
                                    <p>0</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="middle">
                <div class="card">
                    <h2>HUMIDITY</h2>
                    <div id="humidityValue" style="height:110px;">
                        <div class="temperature ">

                            <div class="temp-svg" id="humidity-d3">
                            </div>
                            <script>
                                var a = <?php  echo $humidity; ?>;



                                var percent = a;



                                var ratio=percent/100;

                                var pie=d3.layout.pie()
                                    .value(function(d){return d})
                                    .sort(null);

                                var w=200,h=200;

                                var outerRadius=(w/2)-10;
                                var innerRadius=45;


                                var color = ['#ececec','red','#888888','green'];

                                var colorOld='#F00';
                                var colorNew='#0F0';

                                var arc=d3.svg.arc()
                                    .innerRadius(innerRadius)
                                    .outerRadius(outerRadius)
                                    .startAngle(0)
                                    .endAngle(Math.PI);


                                var arcLine=d3.svg.arc()
                                    .innerRadius(innerRadius)
                                    .outerRadius(outerRadius)
                                    .startAngle(0);

                                var svg=d3.select("#humidity-d3")
                                    .append("svg")
                                    .attr({
                                        width:w,
                                        height:h,
                                        class:'shadow'
                                    }).append('g')
                                    .attr({
                                        transform:'translate('+w/2+','+h/2+')'
                                    });



                                var path=svg.append('path')
                                    .attr({
                                        d:arc,
                                        transform:'rotate(-90)'
                                    }).attr({
                                        'stroke-width':"1",
                                        stroke:"#666666"
                                    })
                                    .style({
                                        fill:color[0]
                                    });

                                if(a >= 30 && a <= 65){
                                    var pathForeground=svg.append('path')
                                        .datum({endAngle:0})
                                        .attr({
                                            d:arcLine,
                                            transform:'rotate(-90)'
                                        })
                                        .style({
                                            fill: function (d,i) {
                                                return color[3];
                                            }
                                        });
                                }else{
                                    var pathForeground=svg.append('path')
                                        .datum({endAngle:0})
                                        .attr({
                                            d:arcLine,
                                            transform:'rotate(-90)'
                                        })
                                        .style({
                                            fill: function (d,i) {
                                                return color[1];
                                            }
                                        });
                                }

                                var middleCount=svg.append('text')
                                    .datum(0)
                                    .text(function(d){
                                        return d;
                                    })
                                    .attr({
                                        class:'middleText',
                                        'text-anchor':'middle',
                                        dy:0,
                                        dx:5
                                    })
                                    .style({
                                        fill:d3.rgb('#000000'),
                                        'font-size':'30px'



                                    });

                                var oldValue=0;
                                var arcTween=function(transition, newValue,oldValue) {
                                    transition.attrTween("d", function (d) {
                                        var interpolate = d3.interpolate(d.endAngle, ((Math.PI))*(newValue/100));

                                        var interpolateCount = d3.interpolate(oldValue, newValue);

                                        return function (t) {
                                            d.endAngle = interpolate(t);
                                            middleCount.text(Math.floor(interpolateCount(t))+'%');

                                            return arcLine(d);
                                        };
                                    });
                                };


                                pathForeground.transition()
                                    .duration(750)
                                    .ease('cubic')
                                    .call(arcTween,percent,oldValue);
                            </script>


                        </div>
                    </div>
                </div>
            </div>

            <div class="right">
                <div class="card">
                    <h2>HUMIDEX</h2>
                    <div class="humidexValue" style="height:110px;">
                        <div class="temperature">

                            <div class="temp-svg" id="humidex-d3">
                            </div>
                            <script>
                                var a = <?php  echo $humidex; ?> - 15;
                                var j = (a*100)/25;
                                var g = (100*j)/100;

                                var svgContainer = d3.select("#humidex-d3").append("svg")
                                    .attr("width", 50)
                                    .attr("height", 100)
                                    .attr("background", "gray");
                                if(g >= 32 && g <= 52){
                                    var rectangle = svgContainer.append("rect")
                                        .attr("x", 0)
                                        .attr("y", 100 - g)
                                        .attr("width", 150)
                                        .attr("height", g)
                                        .attr("fill","green");
                                    console.log(g);
                                }else{
                                    var rectangle = svgContainer.append("rect")
                                        .attr("x", 0)
                                        .attr("y", 100 - g)
                                        .attr("width", 50)
                                        .attr("height", g)
                                        .attr("fill","red");
                                }
                            </script>
                            <div class="humidix-value">
                                <div class="high-temp">
                                    <p>40</p>
                                </div>
                                <div class="current-temp">
                                    <p><?php  echo $humidex; ?></p>
                                </div>
                                <div class="low-temp">
                                    <p>15</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="rightcolumn">
            <div class="cardsmall">
                <h3 style="height:10px;">Co2 Emission</h3>
                <div class="fakeimg" style="height:30px;"><?php echo $carbon ?> CO2/bit</div>
            </div>
            <div class="cardsmall">
                <h3 style="height:10px;">Energy Consumption</h3>
                <div class="fakeimg"  style="height:30px;"><p><?php echo $energy ?> Joules/bit</p></div>
            </div>
        </div>

    </div>
    <div class="card">
        <div class="container">
            <div class="col-md-12">
                <div class="col-md-4">
                    <div id="temperatureChart" style="width: 310px; height: 330px; margin: 0 auto"></div>
                </div>
                <div class="col-md-4">
                    <div id="humidityChart" style="min-width: 310px; height: 330px; margin: 0 auto"></div>
                </div>
                <div class="col-md-4">
                    <div id="humidexChart" style="min-width: 310px; height: 330px; margin: 0 auto"></div>
                </div>
            </div>
            <br>
            <div class="col-md-12">
                <div class="col-md-4 col-md-offset-2">
                    <div id="energyChart" style="min-width: 310px; height: 330px; margin: 0 auto"></div>
                </div>
                <div class="col-md-4">
                    <div id="carbonChart" style="min-width: 310px; height: 330px; margin: 0 auto"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <p class="chooserange"> Choose date range to see history </p>
        <form name="f1" method="post" action="search.php">
            <input type="date" class="inputdate" id="startDate" name="startDate" placeholder="start date">
            <input type="date"  class="inputdate" id="endDate" name="endDate" placeholder="end date">
            <input type="submit" class="searchbutton" value="Search"/></br>
        </form>

    </div>
    <div class="footer">
        <h5>Developed by PERCCOM Group 4</h5>
    </div>
</div>
<script>
    var temperatureSeries = [];
    var humiditySeries = [];
    var humidexSeries = [];

    function renderHumidityChart(humiditySeries) {
        Highcharts.chart('humidityChart', {
            chart: {
                type: 'spline'
            },
            title: {
                text: ''
            },
            legend: {
                enabled: false
            },
            xAxis: {
                type: 'datetime',
                dateTimeLabelFormats: { // don't display the dummy year
                    month: '%e. %b',
                    year: '%b'
                },
                title: {
                    text: 'Date'
                }
            },
            yAxis: {
                title: {
                    text: 'Unit'
                },
                min: 0
            },
            tooltip: {
                headerFormat: '<b>{series.name}</b><br>',
                pointFormat: '{point.x:%e. %b}: {point.y:.2f} m'
            },

            plotOptions: {
                spline: {
                    marker: {
                        enabled: true
                    }
                }
            },
            exporting: {
                enabled: false
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'Humidity',
                data: humiditySeries
            }]
        });
    }

    function renderHumidexChart(humidexSeries) {
        Highcharts.chart('humidexChart', {
            chart: {
                type: 'spline'
            },
            title: {
                text: ''
            },
            legend: {
                enabled: false
            },
            xAxis: {
                type: 'datetime',
                dateTimeLabelFormats: { // don't display the dummy year
                    month: '%e. %b',
                    year: '%b'
                },
                title: {
                    text: 'Date'
                }
            },
            yAxis: {
                title: {
                    text: 'Unit'
                },
                min: 0
            },
            tooltip: {
                headerFormat: '<b>{series.name}</b><br>',
                pointFormat: '{point.x:%e. %b}: {point.y:.2f} m'
            },

            plotOptions: {
                spline: {
                    marker: {
                        enabled: true
                    }
                }
            },
            exporting: {
                enabled: false
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'Humidex',
                data: humidexSeries
            }]
        });
    }

    function renderTemperatureChart(temperatureSeries) {
        Highcharts.chart('temperatureChart', {
            chart: {
                type: 'spline'
            },
            title: {
                text: ''
            },
            legend: {
                enabled: false
            },
            xAxis: {
                type: 'datetime',
                dateTimeLabelFormats: { // don't display the dummy year
                    month: '%e. %b',
                    year: '%b'
                },
                title: {
                    text: 'Date'
                }
            },
            yAxis: {
                title: {
                    text: 'Unit'
                },
                min: 0
            },
            tooltip: {
                headerFormat: '<b>{series.name}</b><br>',
                pointFormat: '{point.x:%e. %b}: {point.y:.2f} m'
            },

            plotOptions: {
                spline: {
                    marker: {
                        enabled: true
                    }
                }
            },
            exporting: {
                enabled: false
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'Temperature',
                color: '#2fff7c',
                data: temperatureSeries
            }]
        });
    }

    function renderEnergyChart() {
        Highcharts.chart('energyChart', {
            chart: {
                type: 'spline'
            },
            title: {
                text: ''
            },
            legend: {
                enabled: false
            },
            xAxis: {
                type: 'datetime',
                dateTimeLabelFormats: { // don't display the dummy year
                    month: '%e. %b',
                    year: '%b'
                },
                title: {
                    text: 'Date'
                }
            },
            yAxis: {
                title: {
                    text: 'Unit'
                },
                min: 0
            },
            tooltip: {
                headerFormat: '<b>{series.name}</b><br>',
                pointFormat: '{point.x:%e. %b}: {point.y:.2f} m'
            },

            plotOptions: {
                spline: {
                    marker: {
                        enabled: true
                    }
                }
            },
            exporting: {
                enabled: false
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'Energy Consumption',
                color: '#2fff7c',
                data: [
                    [Date.UTC(1970, 10, 25), 0],
                    [Date.UTC(1970, 11, 6), 0.25],
                    [Date.UTC(1970, 11, 20), 1.41],
                    [Date.UTC(1970, 11, 25), 1.64],
                    [Date.UTC(1971, 0, 4), 1.6],
                    [Date.UTC(1971, 0, 17), 2.55],
                    [Date.UTC(1971, 0, 24), 2.62],
                    [Date.UTC(1971, 1, 4), 2.5],
                    [Date.UTC(1971, 1, 14), 2.42],
                    [Date.UTC(1971, 2, 6), 2.74],
                    [Date.UTC(1971, 2, 14), 2.62],
                    [Date.UTC(1971, 2, 24), 2.6],
                    [Date.UTC(1971, 3, 2), 2.81],
                    [Date.UTC(1971, 3, 12), 2.63],
                    [Date.UTC(1971, 3, 28), 2.77],
                    [Date.UTC(1971, 4, 5), 2.68],
                    [Date.UTC(1971, 4, 10), 2.56],
                    [Date.UTC(1971, 4, 15), 2.39],
                    [Date.UTC(1971, 4, 20), 2.3],
                    [Date.UTC(1971, 5, 5), 2],
                    [Date.UTC(1971, 5, 10), 1.85],
                    [Date.UTC(1971, 5, 15), 1.49],
                    [Date.UTC(1971, 5, 23), 1.08]
                ]
            }]
        });
    }

    function renderCarbonChart() {
        Highcharts.chart('carbonChart', {
            chart: {
                type: 'spline'
            },
            title: {
                text: ''
            },
            legend: {
                enabled: false
            },
            xAxis: {
                type: 'datetime',
                dateTimeLabelFormats: { // don't display the dummy year
                    month: '%e. %b',
                    year: '%b'
                },
                title: {
                    text: 'Date'
                }
            },
            yAxis: {
                title: {
                    text: 'Unit'
                },
                min: 0
            },
            tooltip: {
                headerFormat: '<b>{series.name}</b><br>',
                pointFormat: '{point.x:%e. %b}: {point.y:.2f} m'
            },

            plotOptions: {
                spline: {
                    marker: {
                        enabled: true
                    }
                }
            },
            exporting: {
                enabled: false
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'Carbon/bit',
                data: [
                    [Date.UTC(1970, 10, 25), 0],
                    [Date.UTC(1970, 11, 6), 0.25],
                    [Date.UTC(1970, 11, 20), 1.41],
                    [Date.UTC(1970, 11, 25), 1.64],
                    [Date.UTC(1971, 0, 4), 1.6],
                    [Date.UTC(1971, 0, 17), 2.55],
                    [Date.UTC(1971, 0, 24), 2.62],
                    [Date.UTC(1971, 1, 4), 2.5],
                    [Date.UTC(1971, 1, 14), 2.42],
                    [Date.UTC(1971, 2, 6), 2.74],
                    [Date.UTC(1971, 2, 14), 1.62],
                    [Date.UTC(1971, 2, 24), 3.6],
                    [Date.UTC(1971, 3, 2), 2.81],
                    [Date.UTC(1971, 3, 12), 2.63],
                    [Date.UTC(1971, 3, 28), 2.77],
                    [Date.UTC(1971, 4, 5), 2.68],
                    [Date.UTC(1971, 4, 10), 2.56],
                    [Date.UTC(1971, 4, 15), 2.39],
                    [Date.UTC(1971, 4, 20), 2.3],
                    [Date.UTC(1971, 5, 5), 2],
                    [Date.UTC(1971, 5, 10), 2.85],
                    [Date.UTC(1971, 5, 15), 1.49],
                    [Date.UTC(1971, 5, 23), 1.08]
                ]
            }]
        });
    }

    window.onload = function(){
        var ajaxurl = 'getData.php',
            data =  {'action': "getData"};
        $.post(ajaxurl, data, function (response) {
            var data = jQuery.parseJSON(response);

            for (var i = 0; i < data.length; i++) {
                var tempTempArray = [];
                var tempHumArray = [];
                var tempHumidexArray = [];

                var dateTime = new Date(data[i].timestamp);
                dateTime = moment(dateTime).valueOf();

                tempTempArray.push(dateTime);
                tempTempArray.push(Number(data[i].temperature));
                temperatureSeries.push(tempTempArray);

                tempHumArray.push(dateTime);
                tempHumArray.push(Number(data[i].humidity));
                humiditySeries.push(tempHumArray);

                tempHumidexArray.push(dateTime);
                tempHumidexArray.push(Number(data[i].humidex));
                humidexSeries.push(tempHumidexArray);
            }

            renderTemperatureChart(temperatureSeries);
            renderHumidexChart(humidexSeries);
            renderHumidityChart(humiditySeries);

            renderCarbonChart();
            renderEnergyChart();
        });
    };

    //Refresh interval
    setInterval(fetchLiveData, 15000);

    function fetchLiveData() {
        var lastAccessedEntryTimestamp = temperatureSeries[0][0];

        var ajaxurl = 'getData.php',
            data =  {'action': "getLiveData"};
        $.post(ajaxurl, data, function (response) {
            var data = jQuery.parseJSON(response);

            console.log(data);

            if (moment(new Date(data[0].timestamp)).valueOf() === lastAccessedEntryTimestamp) {
                // do nothing....
            } else {
                for (var i = 0; i < data.length; i++) {
                    var tempTempArray = [];
                    var tempHumArray = [];
                    var tempHumidexArray = [];

                    var dateTime = new Date(data[i].timestamp);
                    dateTime = moment(dateTime).valueOf();

                    tempTempArray.push(dateTime);
                    tempTempArray.push(Number(data[i].temperature));
                    temperatureSeries.unshift(tempTempArray);

                    tempHumArray.push(dateTime);
                    tempHumArray.push(Number(data[i].humidity));
                    humiditySeries.unshift(tempHumArray);

                    tempHumidexArray.push(dateTime);
                    tempHumidexArray.push(Number(data[i].humidex));
                    humidexSeries.unshift(tempHumidexArray);
                }

                console.log(temperatureSeries);

                if (temperatureSeries.length > 10) {
                    temperatureSeries.pop();
                }

                if (humiditySeries.length > 10) {
                    humiditySeries.pop();
                }

                if (humidexSeries.length > 10) {
                    humidexSeries.pop();
                }

                renderTemperatureChart(temperatureSeries);
                renderHumidexChart(humidexSeries);
                renderHumidityChart(humiditySeries);

                renderCarbonChart();
                renderEnergyChart();
            }
        });
    }
</script>
</body>
</html>
