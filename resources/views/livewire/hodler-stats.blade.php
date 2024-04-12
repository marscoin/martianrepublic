<div wire:poll.15s="loadCoinCount">
    <h4 class="portlet-title"> <u>Your balance / MARS Circulation</u> </h4>
    <div class="portlet-body">
        <div id="pie-chart" class="chart-holder-250"></div>
    </div>
    <script>

        document.addEventListener('livewire:load', function () {
            function drawChart() {
                var data = [
                    {
                        label: `Global Marscoin (${Livewire.entangle('coincount').defer}) MARS`,
                        data: Math.floor(Livewire.entangle('coincount').defer)
                    },
                    {
                        label: `Your holdings (${Livewire.entangle('balance').defer}) MARS`,
                        data: Math.floor(Livewire.entangle('balance').defer)
                    }
                ];
                var chartOptions = {
                series: {
                    pie: {
                        show: true,
                        innerRadius: 0,
                        stroke: {
                            width: 4
                        }
                    }
                },
                legend: {
                    show: false,
                    position: 'ne'
                },
                tooltip: true,
                tooltipOpts: {
                    content: '%s: %y'
                },
                grid: {
                    hoverable: true
                },
                colors: mvpready_core.layoutColors
            };
                var holder = $('#pie-chart');
                if (holder.length) {
                    $.plot(holder, data, chartOptions);
                }
            }

            drawChart(); // Initial draw

            Livewire.on('coinDataUpdated', () => {
                drawChart(); // Redraw when data updates
            });
        });

    </script>
</div>