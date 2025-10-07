<?php

namespace Leobsst\LaravelCmsCore\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;
use Leobsst\LaravelCmsCore\Models\Features\Pages\PageStat;

class Last30DaysVisit extends BaseWidget
{
    protected static ?int $sort = -4;

    protected ?string $pollingInterval = null;

    public ?Model $record = null;

    protected function getStats(): array
    {
        return [
            $this->buildStat('Visites des 7 derniers jours', $this->getVisitsData(today()->subDays(7), now(), 'DATE')),
            $this->buildStat('Visites des 30 derniers jours', $this->getVisitsData(today()->subDays(30), now(), 'DATE')),
        ];
    }

    protected function getColumns(): int
    {
        return 2;
    }

    private function buildStat(string $title, array $visitsData): Stat
    {
        return Stat::make($title, $visitsData['total'])
            ->color('primary')
            ->chart($visitsData['visits_per_interval']);
    }

    private function getVisitsData($startDate, $endDate, $interval): array
    {
        // Récupérer les visites uniques par jour
        $visitsPerInterval = PageStat::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw("{$interval}(created_at) as {$interval}, ip");

        if (filled($this->record)) {
            $visitsPerInterval->where('page_id', $this->record->id);
        }

        $visitsPerInterval = $visitsPerInterval
            ->groupBy($interval, 'ip')
            ->orderBy($interval)
            ->get()
            ->groupBy($interval)
            ->map(fn ($dayGroup) => $dayGroup->count())
            ->toArray();

        return [
            'visits_per_interval' => $visitsPerInterval,
            'total' => array_sum($visitsPerInterval),
        ];
    }

    public static function isDiscovered(): bool
    {
        return \Illuminate\Support\Facades\Schema::hasTable('features') && feature()->active('pages');
    }
}
