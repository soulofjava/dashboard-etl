function columnchart(dataArray) {
    
const dataSource = {
    chart: {
      decimals: "1",
      theme: "fint"
    },
    data: []
  };
  dataArray.forEach(dataItem => {
    dataSource.data.push({
        label: dataItem.nama,
        value: dataItem.total
    });
});
  FusionCharts.ready(function() {
    var myChart = new FusionCharts({
      type: "column3d",
      renderAt: "chart-container",
      width: "100%",
      height: "50%",
      dataFormat: "json",
      dataSource
    }).render();
  });

  FusionCharts.ready(function() {
    var myChart = new FusionCharts({
      type: "pie3d",
      renderAt: "pie-container",
      width: "100%",
      height: "40%",
      dataFormat: "json",
      dataSource
    }).render();
  });
}