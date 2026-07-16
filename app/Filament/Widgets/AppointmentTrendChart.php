<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Closure;
use Filament\Facades\Filament;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class AppointmentTrendChart extends ChartWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static bool $pollingDisabled = true;

    protected static ?int $sort = 1;

    private const string COLOR_PRIMARY = '#0d9488';

    private const string COLOR_FILL = 'rgba(13, 148, 136, 0.08)';

    private const string COLOR_POINT_BORDER = '#ffffff';

    private const string CACHE_PREFIX = 'appointment_trend';

    /** @var array<string, int> */
    private const array CACHE_TTLS = [
        'today' => 60,
        'last_7_days' => 300,
        'last_30_days' => 300,
        'last_3_months' => 600,
        'this_year' => 600,
    ];

    public function getHeading(): string|Htmlable|null
    {
        return __('widgets/dashboard.appointment_trend.heading');
    }

    public function getDescription(): string|Htmlable|null
    {
        return __('widgets/dashboard.appointment_trend.description');
    }

    protected function getFilters(): ?array
    {
        return [
            'today' => __('widgets/dashboard.appointment_trend.filters.today'),
            'last_7_days' => __('widgets/dashboard.appointment_trend.filters.last_7_days'),
            'last_30_days' => __('widgets/dashboard.appointment_trend.filters.last_30_days'),
            'last_3_months' => __('widgets/dashboard.appointment_trend.filters.last_3_months'),
            'this_year' => __('widgets/dashboard.appointment_trend.filters.this_year'),
        ];
    }

    protected function getData(): array
    {
        $filter = $this->filter ?? 'last_7_days';

        $ttl = self::CACHE_TTLS[$filter] ?? 300;

        return Cache::remember(
            key: self::CACHE_PREFIX.":{$filter}",
            ttl: $ttl,
            callback: fn (): array => match ($filter) {
                'today' => $this->buildTodayData(),
                'last_7_days' => $this->buildLast7DaysData(),
                'last_30_days' => $this->buildLast30DaysData(),
                'this_year' => $this->buildThisYearData(),
                default => $this->buildLast7DaysData(),
            },
        );
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => true,
            'aspectRatio' => 2,
            'interaction' => [
                'mode' => 'index',
                'intersect' => false,
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                    'align' => 'end',
                    'labels' => [
                        'usePointStyle' => true,
                        'pointStyle' => 'circle',
                        'padding' => 20,
                        'font' => [
                            'size' => 12,
                            'weight' => '500',
                        ],
                    ],
                ],
                'tooltip' => [
                    'enabled' => true,
                    'backgroundColor' => 'rgba(15, 23, 42, 0.9)',
                    'titleColor' => '#f8fafc',
                    'bodyColor' => '#e2e8f0',
                    'borderColor' => 'rgba(13, 148, 136, 0.3)',
                    'borderWidth' => 1,
                    'cornerRadius' => 8,
                    'padding' => 12,
                    'displayColors' => true,
                    'usePointStyle' => true,
                ],
            ],
            'scales' => [
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                    'ticks' => [
                        'font' => [
                            'size' => 11,
                        ],
                        'maxRotation' => 0,
                    ],
                    'border' => [
                        'display' => false,
                    ],
                ],
                'y' => [
                    'beginAtZero' => true,
                    'grid' => [
                        'color' => 'rgba(148, 163, 184, 0.1)',
                        'drawTicks' => false,
                    ],
                    'ticks' => [
                        'font' => [
                            'size' => 11,
                        ],
                        'padding' => 12,
                    ],
                    'border' => [
                        'display' => false,
                    ],
                ],
            ],
            'animation' => [
                'duration' => 800,
                'easing' => 'easeInOutQuart',
            ],
        ];
    }

    private function buildTodayData(): array
    {
        $data = Trend::query(
            Appointment::query()->where('clinic_id', Filament::getTenant()->id)
        )
            ->between(
                start: Carbon::today()->startOfDay(),
                end: Carbon::today()->endOfDay(),
            )
            ->perHour()
            ->count();

        return $this->formatChartResponse(
            values: $data,
            labelFormatter: static fn (TrendValue $value): string => Carbon::parse($value->date)->format('H:00'),
        );
    }

    private function buildLast7DaysData(): array
    {
        $data = Trend::query(
            Appointment::query()->where('clinic_id', Filament::getTenant()->id)
        )
            ->between(
                start: Carbon::today()->subDays(6)->startOfDay(),
                end: Carbon::today()->endOfDay(),
            )
            ->perDay()
            ->count();

        return $this->formatChartResponse(
            values: $data,
            labelFormatter: static fn (TrendValue $value): string => Carbon::parse($value->date)->format('M d'),
        );
    }

    private function buildLast30DaysData(): array
    {
        $data = Trend::query(
            Appointment::query()->where('clinic_id', Filament::getTenant()->id)
        )
            ->between(
                start: Carbon::today()->subDays(29)->startOfDay(),
                end: Carbon::today()->endOfDay(),
            )
            ->perDay()
            ->count();

        return $this->formatChartResponse(
            values: $data,
            labelFormatter: static fn (TrendValue $value): string => Carbon::parse($value->date)->format('M d'),
        );
    }

    private function buildThisYearData(): array
    {
        $data = Trend::query(
            Appointment::query()->where('clinic_id', Filament::getTenant()->id)
        )
            ->between(
                start: Carbon::today()->startOfYear(),
                end: Carbon::today()->endOfDay(),
            )
            ->perMonth()
            ->count();

        return $this->formatChartResponse(
            values: $data,
            labelFormatter: static fn (TrendValue $value): string => Carbon::parse($value->date)->format('M Y'),
        );
    }

    /**
     * @param  Collection<int, TrendValue>  $values
     * @param  Closure(TrendValue): string  $labelFormatter
     */
    private function formatChartResponse(
        Collection $values,
        Closure $labelFormatter,
    ): array {
        return [
            'datasets' => [
                [
                    'label' => __('widgets/dashboard.appointment_trend.datasets.appointments'),
                    'data' => $values->map(fn (TrendValue $value): int => (int) $value->aggregate)->values()->all(),
                    'borderColor' => self::COLOR_PRIMARY,
                    'backgroundColor' => self::COLOR_FILL,
                    'fill' => true,
                    'tension' => 0.4,
                    'borderWidth' => 2.5,
                    'pointRadius' => 4,
                    'pointBackgroundColor' => self::COLOR_PRIMARY,
                    'pointBorderColor' => self::COLOR_POINT_BORDER,
                    'pointBorderWidth' => 2,
                    'pointHoverRadius' => 7,
                    'pointHoverBackgroundColor' => self::COLOR_PRIMARY,
                    'pointHoverBorderColor' => self::COLOR_POINT_BORDER,
                    'pointHoverBorderWidth' => 3,
                ],
            ],
            'labels' => $values->map($labelFormatter)->values()->all(),
        ];
    }
}
