<?php

namespace App\Charts;

use App\Models\User;
use Carbon\Carbon;
use Chartisan\PHP\Chartisan;
use Fidum\ChartTile\Charts\Chart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DailyUsersChart extends Chart
{
    public function handler(Request $request): Chartisan
    {
        $users = User::query()
            ->select([
                DB::Raw('count(id) as count'),
                DB::Raw('date(created_at) as creation_date'),
            ])
            ->groupBy('creation_date')
            ->where('created_at', '>=', now()->subWeek()->startOfDay())
            ->get()
            ->map(function (User $user) {
                return [
                    'x' => Carbon::parse($user->creation_date)->format('d M'),
                    'y' => $user->count,
                ];
            });

        return Chartisan::build()
            ->labels($users->pluck('x')->toArray())
            ->dataset('Users', $users->toArray());
    }

    public function type(): string
    {
        return 'line';
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
