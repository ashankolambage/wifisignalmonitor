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
        <canvas id="signalChart" width="600" height="100"></canvas>
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
                labels: ['RSRP', 'RSSI', 'RSRQ', 'SINR'],
                datasets: [{
                    label: 'Signal Data',
                    data: [0, 0, 0, 0],
                    backgroundColor: [
                        'rgba(255, 0, 0, 0.5)', // Red for Poor
                        'rgba(255, 165, 0, 0.5)', // Orange for Fair
                        'rgba(255, 255, 0, 0.5)', // Yellow for Good
                        'rgba(0, 255, 0, 0.5)' // Green for Excellent
                    ],
                    borderColor: [
                        'rgba(255, 0, 0, 1)',
                        'rgba(255, 165, 0, 1)',
                        'rgba(255, 255, 0, 1)',
                        'rgba(0, 255, 0, 1)'
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
                        ticks: {
                            stepSize: 1 // Fixed step size for y-axis
                        }
                    }
                },
            }
        });
    
        // Function to update the chart with new data
        function updateChart(data) {
            signalChart.data.datasets[0].data = [
                data.modem_rsrp,
                data.modem_rssi,
                data.modem_rsrq,
                data.modem_sinr
            ];
            signalChart.update();
        }
    
        // Function to fetch signal data from the server
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
    
        // Click event handler for the start button
        $('#startBtn').click(function() {
            fetchSignalData();
            fetchInterval = setInterval(fetchSignalData, 1000);
        });
    
        // Click event handler for the stop button
        $('#stopBtn').click(function() {
            clearInterval(fetchInterval);
        });
    </script>
    
</body>
</html>
