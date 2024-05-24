<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Fall Incident Report</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
    }
    h1 {
      text-align: center;
    }
    .container {
      max-width: 800px;
      margin: auto;
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    .form-group {
      margin-bottom: 20px;
    }
    label {
      display: block;
      margin-bottom: 5px;
    }
    select, input[type="date"] {
      width: 100%;
      padding: 8px;
      margin-bottom: 10px;
    }
    canvas {
      width: 100%;
      max-height: 400px;
    }
  </style>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <h1>Fall Incident Report</h1>
  <div class="container">
    <div class="form-group">
      <label for="date">Select Date:</label>
      <input type="date" id="date" name="date">
    </div>
    <div class="form-group">
      <label for="room">Select Room:</label>
      <select id="room" name="room">
        <option value="101">Room 101</option>
        <option value="102">Room 102</option>
        <option value="103">Room 103</option>
        <option value="104">Room 104</option>
      </select>
    </div>
    <button onclick="loadData()">Load Data</button>
    <canvas id="fallChart"></canvas>
  </div>

  <script>
    let fallChart;

    function loadData() {
      const date = document.getElementById('date').value;
      const room = document.getElementById('room').value;

      if (!date || !room) {
        alert('Please select both date and room.');
        return;
      }

      fetch(`http://localhost:8080/api/falls?date=${date}&room=${room}`)
        .then(response => response.json())
        .then(data => {
          const labels = data.map(d => d.time);
          const fallCounts = data.map(d => d.fall_count);

          const chartData = {
            labels: labels,
            datasets: [{
              label: 'Number of Falls',
              data: fallCounts,
              backgroundColor: 'rgba(255, 99, 132, 0.2)',
              borderColor: 'rgba(255, 99, 132, 1)',
              borderWidth: 1
            }]
          };

          const ctx = document.getElementById('fallChart').getContext('2d');
          if (fallChart) {
            fallChart.destroy();
          }
          fallChart = new Chart(ctx, {
            type: 'bar',
            data: chartData,
            options: {
              scales: {
                y: {
                  beginAtZero: true
                }
              }
            }
          });
        })
        .catch(error => {
          console.error('Failed to load data:', error);
          alert('Failed to load data.');
        });
    }
  </script>
</body>
</html>
