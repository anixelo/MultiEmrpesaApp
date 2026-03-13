<?php

namespace MultiempresaApp\SimpleAnalytics\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use MultiempresaApp\SimpleAnalytics\Services\AnalyticsService;

class AnalyticsDashboardController extends Controller
{
    public function __construct(private AnalyticsService $analytics) {}

    /**
     * Main analytics dashboard.
     */
    public function index()
    {
        $visitsByDayRaw = $this->analytics->visitsByDay(30);

        // Build a complete 30-day series with 0 for missing days
        $days       = collect();
        $labels     = collect();
        $dataPoints = collect();

        for ($i = 29; $i >= 0; $i--) {
            $date   = now()->subDays($i)->format('Y-m-d');
            $label  = now()->subDays($i)->format('d M');
            $record = $visitsByDayRaw->firstWhere('date', $date);

            $labels->push($label);
            $dataPoints->push($record ? $record->total : 0);
        }

        return view('simple-analytics::admin.index', [
            'visitsToday'     => $this->analytics->visitsToday(),
            'visitsYesterday' => $this->analytics->visitsYesterday(),
            'visitsThisMonth' => $this->analytics->visitsThisMonth(),
            'totalVisits'     => $this->analytics->totalVisits(),
            'topPages'        => $this->analytics->topPages(10),
            'latestVisits'    => $this->analytics->latestVisits(20),
            'chartLabels'     => $labels->toJson(),
            'chartData'       => $dataPoints->toJson(),
        ]);
    }

    /**
     * Top visited pages listing.
     */
    public function pages()
    {
        $pages = $this->analytics->topPages(50);

        return view('simple-analytics::admin.pages', [
            'pages' => $pages,
        ]);
    }

    /**
     * Latest visits listing.
     */
    public function visits()
    {
        $visits = $this->analytics->latestVisits(100);

        return view('simple-analytics::admin.visits', [
            'visits' => $visits,
        ]);
    }
}
