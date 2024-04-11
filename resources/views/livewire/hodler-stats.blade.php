<div wire:poll.15s="loadCoinCount">
    <h4 class="portlet-title"> <u>Your balance / MARS Circulation</u> </h4>
    <div class="portlet-body">
        <div id="pie-chart" class="chart-holder-250"></div>
    </div>
    <script>
        document.addEventListener('livewire:load', function () {
            var data = [
                {
                    label: "Global Marscoin ({{ number_format($coincount) }}) MARS",
                    data: Math.floor(@js($coincount))
                },
                {
                    label: "Your holdings ({{ number_format($balance) }}) MARS",
                    data: Math.floor(@js($balance))
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

            Livewire.on('loadCoinCount', () => {
                $.plot(holder, data, chartOptions); // Re-plot the chart when data updates
            });
        });
    </script>
</div>