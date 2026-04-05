@props(['title', 'value', 'data' => '[]', 'color' => '#10B981', 'id'])

<div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between">
    <div class="flex items-center justify-between mb-4">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">{{ $title }}</p>
            <h3 class="text-2xl font-outfit font-bold text-gray-900">{{ $value }}</h3>
        </div>
    </div>
    
    <div class="h-16 w-full" x-init="
        const options = {
            series: [{
                name: 'Value',
                data: {{ $data }}
            }],
            chart: {
                type: 'area',
                height: 60,
                sparkline: { enabled: true }
            },
            stroke: { curve: 'smooth', width: 2 },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    opacityTo: 0,
                    stops: [0, 90, 100]
                }
            },
            colors: ['{{ $color }}'],
            tooltip: {
                fixed: { enabled: false },
                x: { show: false },
                y: {
                    title: {
                        formatter: function (seriesName) {
                            return ''
                        }
                    }
                },
                marker: { show: false }
            }
        };

        const chart = new window.ApexCharts($el, options);
        chart.render();
    "></div>
</div>
