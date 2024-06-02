<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signal Data</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div>
        <canvas id="signalChart"></canvas>
    </div>

    <script>
        const ctx = document.getElementById('signalChart').getContext('2d');
        const signalChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['modem_rsrp', 'modem_rssi', 'modem_rsrq', 'modem_sinr'],
                datasets: [{
                    label: 'Signal Data',
                    data: [],
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
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        function updateChart(data) {
            signalChart.data.datasets[0].data = [
                data.modem_rsrp,
                data.modem_rssi,
                data.modem_rsrq,
                data.modem_sinr
            ];
            signalChart.update();
        }

        async function fetchSignalData() {
            try {
                const response = await fetch('/signal-data');
                const data = await response.json();
                data.forEach(updateChart);
            } catch (error) {
                console.error('Error fetching signal data:', error);
            }
        }

        // Fetch signal data when the page loads
        window.onload = fetchSignalData;
    </script>
</body>
</html>
