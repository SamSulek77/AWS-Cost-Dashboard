<!-- resources/views/filament/widgets/spinning-pie-chart.blade.php -->
<div class="w-full flex flex-col items-center">
    <canvas id="awsSpinningPieChart" width="400" height="400"></canvas>
    <button onclick="spinPieChart()" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded shadow">
        Spin the Chart
    </button>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const pieCtx = document.getElementById('awsSpinningPieChart').getContext('2d');

    const pieData = @json($this->getData());

    const pieChart = new Chart(pieCtx, {
        type: 'pie',
        data: pieData,
        options: {
            responsive: true,
            animation: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    let currentAngle = 0;
    let spinInterval;

    function spinPieChart() {
        if (spinInterval) return; // Prevent multiple clicks

        let spins = 5 * 360; // 5 full spins
        let targetAngle = currentAngle + spins;

        spinInterval = setInterval(() => {
            currentAngle += 10;
            if (currentAngle >= targetAngle) {
                clearInterval(spinInterval);
                spinInterval = null;
            } else {
                pieChart.options.rotation = currentAngle * Math.PI / 180;
                pieChart.update();
            }
        }, 16); // ~60 FPS
    }
</script>
