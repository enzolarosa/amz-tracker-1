<x-dashboard>
    <livewire:accuweather-current-conditions-tile position="a1:a2"/>
    <livewire:accuweather-five-day-forecast-tile position="b1:d2"/>


    <livewire:chart-tile chartClass="{{\App\Charts\DailyUsersChart::class}}" position="b3:d4"/>


    <livewire:forge-server-tile position="a3:a10"/>
    <livewire:forge-recent-events-tile position="b5:b10"/>

    <livewire:chart-tile chartClass="{{\App\Charts\DailyProductsChart::class}}" position="c5:d6"/>
    <livewire:chart-tile chartClass="{{\App\Charts\DailyProductAnalyzeChart::class}}" position="c7:d8"/>


</x-dashboard>