<?php

namespace App\Filament\Widgets;

use App\Models\Expense;
use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class ExpenseChart extends ChartWidget
{
    protected static ?string $heading = 'Expense Chart';

    protected static string $color = 'red';

    protected static ?int $sort = 2;

    public ?string $filter = 'day';

    protected function getData(): array
    {
        $activeFilter = $this->filter;

        $dateRange = match ($activeFilter) {
            'day' => [
                'start' => now()->startOfDay(),
                'end' => now()->endOfDay(),
                'period' => 'perHour'
            ],
            'week' => [
                'start' => now()->startOfWeek(),
                'end' => now()->endOfWeek(),
                'period' => 'perDay'
            ],
            'month' => [
                'start' => now()->startOfMonth(),
                'end' => now()->endOfMonth(),
                'period' => 'perDay'
            ],
            'year' => [
                'start' => now()->startOfYear(),
                'end' => now()->endOfYear(),
                'period' => 'perMonth'
            ]
        };

        $query = Trend::model(Expense::class)
            ->between(
                start: $dateRange['start'],
                end: $dateRange['end']
            );

        if ($dateRange['period'] === 'perHour') {
            $queryFilterRange = $query->perHour();
        } else if ($dateRange['period'] === 'perDay') {
            $queryFilterRange = $query->perDay();
        } else {
            $queryFilterRange = $query->perMonth();
        }

        $data = $queryFilterRange->sum('amount');

        return [
            'datasets' => [
                [
                    'label' => 'Total expenses',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(function (TrendValue $value) use ($dateRange) {
                $date = Carbon::parse($value->date);

                if ($dateRange['period'] === 'perHour') {
                    $dateFormat = $date->format('H:i');
                } else if ($dateRange['period'] === 'perDay') {
                    $dateFormat = $date->format('d M');
                } else {
                    $dateFormat = $date->format('M Y');
                }

                return $dateFormat;
            }),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getFilters(): ?array
    {
        return [
            'day' => 'Today',
            'week' => 'This week',
            'month' => 'This month',
            'year' => 'This year',
        ];
    }
}
