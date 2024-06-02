<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signal Data</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div>
        <canvas id="signalChart" width="400" height="200"></canvas>
    </div>
    <div>
        <button id="startBtn">Start Fetching</button>
        <button id="stopBtn">Stop Fetching</button>
    </div>

    <script>
        const ctx = document.getElementById('signalChart').getContext('2d');
        const signalChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['modem_rsrp', 'modem_rssi', 'modem_rsrq', 'modem_sinr'],
                datasets: [{
                    label: 'Signal Data',
                    data: [0, 0, 0, 0],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                scales: {
                    x: {
                        stacked: true,
                    },
                    y: {
                        stacked: true,
                    }
                },
            }
        });

        let fetchInterval;

        function updateChart(data) {
            signalChart.data.datasets[0].data = [
                data.modem_rsrp,
                data.modem_rssi,
                data.modem_rsrq,
                data.modem_sinr
            ];
            signalChart.update();
        }

        function fetchSignalData() {
            $.ajax({
                url: '/signal-data',
                method: 'GET',
                success: function (data) {
                    updateChart(data);
                },
                error: function (error) {
                    console.error('Error fetching signal data:', error);
                }
            });
        }

        $('#startBtn').click(function() {
            fetchSignalData();
            fetchInterval = setInterval(fetchSignalData, 1000);
        });

        $('#stopBtn').click(function() {
            clearInterval(fetchInterval);
        });
    </script>
</body>
</html>
