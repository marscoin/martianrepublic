<div wire:init="loadVotes">
    <canvas id="voteChart" width="400" height="400"></canvas>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener('livewire:load', function () {
        const ctx = document.getElementById('voteChart').getContext('2d');
        const voteChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Yay', 'Nay'],
                datasets: [{
                    label: 'Vote Breakdown',
                    data: [@this.yayPercent, @this.nayPercent], // Using Livewire property binding
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 99, 132, 0.6)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        enabled: true
                    }
                }
            }
        });

        // Listen for Livewire events to update the chart if needed
        Livewire.on('votesUpdated', data => {
            voteChart.data.datasets[0].data = [data.yayPercent, data.nayPercent];
            voteChart.update();
        });
    });
    </script>
    @endpush
</div>
