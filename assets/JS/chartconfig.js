var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
        datasets: [{
            label: '# of Votes',
            data: [12, 14, 30, 54, 21, 3],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1,
            hoverOffset: 10
        }]
    },
    options: {
        layout: {
            padding: 10
        },
        plugins: {
            legend: {
                display: true,
                labels: {
                    color: 'rgb(255, 99, 132)'
                },
                position:'bottom'
            },
            title: {
                display: true,
                text: 'My start chart'
            }
        }
    }
});
function selectTable () {
    cObj("tabular").classList.remove("tables");
    cObj("tabular").classList.add("selected_Option");

    cObj("chartlike").classList.remove("selected_Option");
    cObj("chartlike").classList.add("tables");

    
    cObj("hide_chart_table").classList.remove("selected_Option");
    cObj("hide_chart_table").classList.add("tables");

    cObj("my_purpose_table2").classList.remove("hide");
    cObj("my_purpose_table1").classList.remove("hide");
    //hide tables
    if (cObj("mode_table") != null) {
        cObj("mode_table").classList.remove("hide");
        cObj("purp_table").classList.remove("hide");
    }
    
    //hide the canvas
    if(cObj("purpChartHolder") != null){
        cObj("purpChartHolder").classList.add("hide");
        cObj("modepayChartHolder").classList.add("hide");
        cObj("noticeHold").classList.add("hide");
    }
}

function hideData() {
    cObj("chartlike").classList.add("tables");
    cObj("chartlike").classList.remove("selected_Option");

    cObj("tabular").classList.remove("selected_Option");
    cObj("tabular").classList.add("tables");

    
    cObj("hide_chart_table").classList.add("selected_Option");
    cObj("hide_chart_table").classList.remove("tables");

    cObj("my_purpose_table2")!=null ?  cObj("my_purpose_table2").classList.add("hide") : "";
    cObj("my_purpose_table1")!=null ? cObj("my_purpose_table1").classList.add("hide") : "";

    //hide tables
    if (cObj("mode_table") != null) {
        cObj("mode_table").classList.add("hide");
        cObj("purp_table").classList.add("hide");
    }


    //hide canvas
    if(cObj("purpChartHolder") != null){
        cObj("purpChartHolder").classList.add("hide");
        cObj("modepayChartHolder").classList.add("hide");
        cObj("noticeHold").classList.add("hide");
    }
}

function selectChart () {
    cObj("my_purpose_table2").classList.remove("hide");
    cObj("my_purpose_table1").classList.remove("hide");

    cObj("chartlike").classList.remove("tables");
    cObj("chartlike").classList.add("selected_Option");

    cObj("tabular").classList.remove("selected_Option");
    cObj("tabular").classList.add("tables");

    
    cObj("hide_chart_table").classList.remove("selected_Option");
    cObj("hide_chart_table").classList.add("tables");
    //hide tables
    if (cObj("mode_table") != null) {
        cObj("mode_table").classList.add("hide");
        cObj("purp_table").classList.add("hide");
    }


    //show the canvas
    if(cObj("purpChartHolder") != null){
        cObj("purpChartHolder").classList.remove("hide");
        cObj("modepayChartHolder").classList.remove("hide");
        cObj("noticeHold").classList.remove("hide");
        createPurposeChart();
        createModeChart();
    }
}

function createModeChart() {
    //get the data from the purpose holder
    var modedata = cObj("modepay_jsondata").innerText;
    var parseedata = JSON.parse(modedata);
    //get the datalabels
    var arrLabels = [];
    for(let val in parseedata){
        arrLabels.push(val);
    }
    //get the value for each label
    var arrData = [];
    var arrColor = [];
    for (let index = 0; index < arrLabels.length; index++) {
        const element = arrLabels[index];
        arrData.push(parseedata[element]);
        arrColor.push(getRandomColor());
    }
    createChart2(cObj("modeChart"),"Sort by Purpose Of Pay",arrLabels,arrData,arrColor);
}

function createPurposeChart() {
    //function to get the data from the holders
    var data = cObj("purpose_values_in").innerText;
    var pasreData = JSON.parse(data);
    //get the labels
    var arrLabels = [];
    for(let val in pasreData){
        arrLabels.push(val);
    }
    //lets get the values
    var arrData = [];
    var bgColors = [];
    for (let index = 0; index < arrLabels.length; index++) {
        const element = arrLabels[index];
        arrData.push(pasreData[element]);
        bgColors.push(getRandomColor());
    }
    createChart(cObj("purpChart"),"Sort by Mode",arrLabels,arrData,bgColors);
}
var myChart2;
function createChart2(object,chartLabel,arrLabels,arrData,arrColor) {
    if (myChart2 != null) {
        myChart2.destroy();
    }
    var ctx = object.getContext('2d');
    myChart2 = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: arrLabels,
            datasets: [{
                label: chartLabel,
                data: arrData,
                backgroundColor: arrColor,
                borderColor: arrColor,
                borderWidth: 1,
                hoverOffset: 5
            }]
        },
        options: {layout: {
                padding: 2
            },
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        color: 'black'
                    },
                    position:'bottom'
                },
                title: {
                    display: true,
                    text: chartLabel
                }
            }

        }
    });
}
var myChart;
function createChart(object,chartLabel,arrLabels,arrData,arrColor) {
    if (myChart != null) {
        myChart.destroy();
    }
    var ctx = object.getContext('2d');
    myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: arrLabels,
            datasets: [{
                label: chartLabel,
                data: arrData,
                backgroundColor: arrColor,
                borderColor: arrColor,
                borderWidth: 1,
                hoverOffset: 5
            }]
        },
        options: {layout: {
                padding: 2
            },
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        color: 'black'
                    },
                    position:'bottom'
                },
                title: {
                    display: true,
                    text: chartLabel
                }
            }

        }
    });
}
const getRandomColor = () => {
    const r = getRandomNumber(360);
    const g = getRandomNumber(100);
    const b = getRandomNumber(100);
    var a = getRandomNumber(1);
    if (a == 0) {
        var id = setInterval(() => {
            a = getRandomNumber(10);
            if (a > 0) {
                stopInterval(id);
            }
        }, 100);
    }
    return "rgba("+r+","+g+","+b+","+a+")";  
};
const getRandomNumber = (limit) => {
    return (Math.random() * limit).toFixed(2);
};