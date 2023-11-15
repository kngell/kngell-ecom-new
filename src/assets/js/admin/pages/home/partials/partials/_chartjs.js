import Chart from "chart.js/auto";
window.colors = {
  red: "#fd397a",
  blue: "#0d6efd",
  green: "#28a745",
  yellow: "#ffc107",
  gray: "#e3eaef",
  purple: "#9833d5",
};
class Chartjs {
  constructor() {}
  doughnutChart(elementId) {
    let element = document.getElementById(elementId);
    if (!element) return false;
    new Chart(element, {
      type: "doughnut",
      data: {
        labels: ["Direct", "Affilliate", "Sponsored", "Email"],
        datasets: [
          {
            label: "My First Dataset",
            data: [300, 50, 100, 30],
            backgroundColor: [
              window.colors.red,
              window.colors.blue,
              window.colors.green,
              window.colors.yellow,
            ],
          },
        ],
      },
    });
  }

  lineChart(elementId) {
    let element = document.getElementById(elementId);
    if (!element) return false;
    new Chart(element.getContext("2d"), {
      type: "line",
      data: {
        labels: [
          "January",
          "February",
          "March",
          "April",
          "May",
          "June",
          "July",
        ],
        datasets: [
          {
            label: "Affilliate",
            backgroundColor: window.colors.red,
            borderColor: window.colors.red,
            data: [0, 2, 5, 8, 10, 20, 25],
          },
          {
            label: "Sponsored",
            backgroundColor: window.colors.green,
            borderColor: window.colors.green,
            data: [0, 4, 6, 10, 13, 25, 30],
          },
          {
            label: "Email",
            backgroundColor: window.colors.blue,
            borderColor: window.colors.blue,
            data: [0, 6, 8, 15, 18, 30, 36],
          },
          {
            label: "Direct",
            backgroundColor: window.colors.yellow,
            borderColor: window.colors.yellow,
            data: [0, 10, 17, 20, 30, 30, 45],
          },
        ],
      },
      options: {},
    });
  }

  progressBarChart(elementId) {
    let element = document.getElementById(elementId);
    var progress = document.getElementById("animationProgress");
    if (!element) return false;
    let config = {
      type: "line",
      data: {
        labels: [
          "January",
          "February",
          "March",
          "April",
          "May",
          "June",
          "July",
        ],
        datasets: [
          {
            label: "This Year",
            fill: false,
            borderColor: window.colors.red,
            backgroundColor: window.colors.red,
            data: [100, 200, 500, 400, 100, 560, 700],
          },
          {
            label: "Previous Year",
            fill: false,
            borderColor: window.colors.blue,
            backgroundColor: window.colors.blue,
            data: [50, 100, 200, 500, 800, 600, 300],
          },
        ],
      },
      options: {
        animation: {
          duration: 2000,
        },
      },
    };
    new Chart(element.getContext("2d"), config);
  }

  barChart(elementId) {
    let element = document.getElementById(elementId);
    if (!element) return false;
    var ctx = element.getContext("2d");
    var data = {
      labels: [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "Jun",
        "Jul",
        "Aug",
        "Sep",
        "Oct",
        "Nov",
        "Dec",
      ],
      datasets: [
        {
          label: "Actual",
          backgroundColor: window.colors.blue,
          borderColor: window.colors.blue,
          hoverBackgroundColor: window.colors.blue,
          hoverBorderColor: window.colors.blue,
          data: [55, 58, 70, 65, 57, 45, 38, 44, 77, 65, 91, 80],
          barPercentage: 0.7,
          categoryPercentage: 0.5,
        },
        {
          label: "Expect",
          backgroundColor: window.colors.gray,
          borderColor: window.colors.gray,
          hoverBackgroundColor: window.colors.gray,
          hoverBorderColor: window.colors.gray,
          data: [66, 53, 65, 72, 34, 55, 45, 64, 23, 76, 67, 77],
          barPercentage: 0.7,
          categoryPercentage: 0.5,
        },
      ],
    };
    var options = {
      maintainAspectRatio: false,
      legend: {
        display: false,
      },
      scales: {
        yAxes: [
          {
            gridLines: {
              display: false,
              color: "rgba(0,0,0,0.06)",
            },
            stacked: false,
            ticks: {
              stepSize: 20,
            },
          },
        ],
        xAxes: [
          {
            stacked: false,
            gridLines: {
              color: "rgba(0,0,0,0.02)",
            },
          },
        ],
      },
    };
    var config = {
      type: "bar",
      data: data,
      options: options,
    };
    new Chart(ctx, config);
  }
}

export default Chartjs;
