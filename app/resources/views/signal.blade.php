<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signal Data</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
</head>
<body>
    <div>
        <canvas id="signalChart" width="150" height="200"></canvas>
    </div>

    <div id="dataValues" class="data-values-container">
        <p>RSRP: <span id="rsrpValue">0</span></p>
        <p>RSSI: <span id="rssiValue">0</span></p>
        <p>RSRQ: <span id="rsrqValue">0</span></p>
        <p>SINR: <span id="sinrValue">0</span></p>
    </div>

    <div id="dataValues" class="data-values-container">
        <p>RSRP: <span id="">-65 dBm above</span></p>
        <p>RSSI: <span id="">-50 dBm above</span></p>
        <p>RSRQ: <span id=""> -3 dB to -6 dB</span></p>
        <p>SINR: <span id=""> 25 dB and above</span></p>
    </div>

    <style>
        .data-values-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            width: 100%;
            font-size: 20px;
        }

        .data-values-container p {
            flex: 0 0 calc(25% - 10px); /* Adjust the width of each data value */
            margin-bottom: 10px; /* Add margin between each row */
        }

        @media only screen and (max-width: 600px) {
            .data-values-container p {
                flex: 0 0 calc(50% - 10px); /* Adjust width for smaller screens */
            }
        }

    </style>

    <div>
        <button id="startBtn">Start Fetching</button>
        <button id="stopBtn">Stop Fetching</button>
        <p>Total Requests: <span id="requestCount">0</span></p>
    </div>

    <script>
        let requestCounter = 0;
        const ctx = document.getElementById('signalChart').getContext('2d');
        const signalChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['RSRP', 'RSSI', 'RSRQ', 'SINR'],
                datasets: [{
                    label: 'Signal Data',
                    data: [0, 0, 0, 0],
                    backgroundColor: [
                        'rgba(0, 255, 0, 0.5)', // Green for Excellent
                        'rgba(255, 255, 0, 0.5)', // Yellow for Good
                        'rgba(255, 165, 0, 0.5)', // Orange for Fair
                        'rgba(255, 0, 0, 0.5)' // Red for Poor
                    ],
                    borderColor: [
                        'rgba(0, 255, 0, 1)',
                        'rgba(255, 255, 0, 1)',
                        'rgba(255, 165, 0, 1)',
                        'rgba(255, 0, 0, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
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

            $('#rsrpValue').text(data.raw_modem_rsrp);
            $('#rssiValue').text(data.raw_modem_rssi);
            $('#rsrqValue').text(data.raw_modem_rsrq);
            $('#sinrValue').text(data.raw_modem_sinr);
        }
    
        // Function to fetch signal data from the server
        function fetchSignalData() {
            $.ajax({
                url: '/signal-data',
                method: 'GET',
                success: function (data) {
                    updateChart(data);
                    
                    requestCounter++;
                    $('#requestCount').text(requestCounter);
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
