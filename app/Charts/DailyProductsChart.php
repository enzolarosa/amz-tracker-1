<?php

namespace App\Charts;

use App\Models\AmzProduct;
use App\Models\User;
use Carbon\Carbon;
use Chartisan\PHP\Chartisan;
use Fidum\ChartTile\Charts\Chart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DailyProductsChart extends Chart
{
    public function handler(Request $request): Chartisan
    {
        $prod = AmzProduct::query()
            ->select([
                DB::Raw('count(id) as count'),
                DB::Raw('date(created_at) as creation_date'),
            ])
            ->groupBy('creation_date')
            ->where('created_at', '>=', now()->subWeek()->startOfDay())
            ->get()
            ->map(function (AmzProduct $product) {
                return [
                    'x' => Carbon::parse($product->creation_date)->toDateString(),
                    'y' => $product->count,
                ];
            });

        return Chartisan::build()
            ->labels($prod->pluck('x')->toArray())
            ->dataset('Products', $prod->toArray());
    }

    public function type(): string
    {
        return 'bar';
    }

    public function options(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];
    }

    public function colors(): array
    {
        return ['#848584'];
    }
}
