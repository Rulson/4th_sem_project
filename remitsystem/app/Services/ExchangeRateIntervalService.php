<?php

namespace App\Services;

use App\Exceptions\ExchangeRateIntervalException;
use App\Modules\User\Models\ExchangeRate;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ExchangeRateIntervalService
{
    /**
     * @throws ExchangeRateIntervalException
     */
    public function getExchangeRateByInterval(string $interval = null)
    {
        try {
            $query = ExchangeRate::query();
            $now = Carbon::now();
            return match ($interval) {
                'week' => $this->getWeeklyExchangeRates($query, $now),
                'month' => $this->getMonthlyExchangeRates($query, $now),
                'year' => $this->getYearlyExchangeRates($query, $now),
                default => $this->getDailyExchangeRates($query, $now)
            };
        } catch (\Throwable $e) {
            \Log::error("Error while fetching exchange rates by interval: " . $e->getMessage());
            \Log::error("Exchange rate interval error" . $e->getTraceAsString());
            throw new ExchangeRateIntervalException($e);
        }
    }

    /**
     * Get daily exchange rates for the last 30 days
     */
    private function getDailyExchangeRates($query, Carbon $now): Collection
    {
        $startDate = $now->copy()->subDays(30); //Last 30 days
        return $query->whereBetween('created_at', [$startDate, $now])
            ->selectRaw('DATE(created_at) as time_group, exchange_rate, created_at')
            ->orderBy('created_at')
            ->get()
            ->groupBy('time_group')
            ->map(function($group) {
                $latestRate = $group->sortByDesc('created_at')->first();
                return [
                    'time_group' => $latestRate->time_group,
                    'exchange_rate' => (float) $latestRate->exchange_rate,
                ];
            })
            ->values();
    }

    /**
     * Get weekly aggregated exchange rates (last 12 weeks)
     */
    private function getWeeklyExchangeRates($query, Carbon $now): Collection
    {
        $startDate = $now->copy()->subWeeks(12); // Last 12 weeks

        return $query->whereBetween('created_at', [$startDate, $now])
            ->selectRaw('
                YEAR(created_at) as year,
                WEEK(created_at) as week_number,
                MIN(DATE(created_at)) as week_start,
                MAX(DATE(created_at)) as week_end,
                AVG(exchange_rate) as avg_rate,
                MAX(exchange_rate) as max_rate,
                MIN(exchange_rate) as min_rate
            ')
            ->groupBy('year', 'week_number')
            ->orderBy('year')
            ->orderBy('week_number')
            ->get()
            ->map(function($item) {
                return [
                    'time_group' => $item->week_start . ' to ' . $item->week_end,
                    'exchange_rate' => round($item->avg_rate, 4),
                    'avg_rate' => round($item->avg_rate, 4),
                    'max_rate' => round($item->max_rate, 4),
                    'min_rate' => round($item->min_rate, 4),
                ];
            });
    }

    /**
     * Get monthly aggregated exchange rates (last 12 months)
     */
    private function getMonthlyExchangeRates($query, Carbon $now): Collection
    {
        $startDate = $now->copy()->subMonths(12); // Last 12 months

        return $query->whereBetween('created_at', [$startDate, $now])
            ->selectRaw('
                DATE_FORMAT(created_at, "%Y-%m") as time_group,
                AVG(exchange_rate) as avg_rate,
                MAX(exchange_rate) as max_rate,
                MIN(exchange_rate) as min_rate
            ')
            ->groupBy('time_group')
            ->orderBy('time_group')
            ->get()
            ->map(function($item) {
                return [
                    'time_group' => $item->time_group,
                    'exchange_rate' => round($item->avg_rate, 4),
                    'avg_rate' => round($item->avg_rate, 4),
                    'max_rate' => round($item->max_rate, 4),
                    'min_rate' => round($item->min_rate, 4),
                ];
            });
    }

    /**
     * Get yearly aggregated exchange rates (last 5 years)
     */
    private function getYearlyExchangeRates($query, Carbon $now): Collection
    {
        $startDate = $now->copy()->subYears(5); // Last 5 years

        return $query->whereBetween('created_at', [$startDate, $now])
            ->selectRaw('
                YEAR(created_at) as time_group,
                AVG(exchange_rate) as avg_rate,
                MAX(exchange_rate) as max_rate,
                MIN(exchange_rate) as min_rate
            ')
            ->groupBy('time_group')
            ->orderBy('time_group')
            ->get()
            ->map(function($item) {
                return [
                    'time_group' => (string) $item->time_group,
                    'exchange_rate' => round($item->avg_rate, 4),
                    'avg_rate' => round($item->avg_rate, 4),
                    'max_rate' => round($item->max_rate, 4),
                    'min_rate' => round($item->min_rate, 4),
                ];
            });
    }
}
