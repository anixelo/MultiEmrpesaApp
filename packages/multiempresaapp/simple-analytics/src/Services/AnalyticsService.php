<?php

namespace MultiempresaApp\SimpleAnalytics\Services;

use Carbon\Carbon;
use Illuminate\Http\Request;
use MultiempresaApp\SimpleAnalytics\Models\PageVisit;

class AnalyticsService
{
    /**
     * Record a page visit if it does not already exist for ip+path+date.
     */
    public function track(Request $request): void
    {
        $today = Carbon::today()->toDateString();

        $alreadyExists = PageVisit::where('ip', $request->ip())
            ->where('path', $request->path())
            ->where('date', $today)
            ->exists();

        if ($alreadyExists) {
            return;
        }

        PageVisit::create([
            'url'        => $request->fullUrl(),
            'path'       => '/' . ltrim($request->path(), '/'),
            'ip'         => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referer'    => $request->headers->get('referer'),
            'method'     => $request->method(),
            'user_id'    => auth()->id(),
            'date'       => $today,
        ]);
    }

    /**
     * Total visits today.
     */
    public function visitsToday(): int
    {
        return PageVisit::whereDate('date', Carbon::today())->count();
    }

    /**
     * Total visits yesterday.
     */
    public function visitsYesterday(): int
    {
        return PageVisit::whereDate('date', Carbon::yesterday())->count();
    }

    /**
     * Total visits in the current calendar month.
     */
    public function visitsThisMonth(): int
    {
        return PageVisit::whereYear('date', Carbon::now()->year)
            ->whereMonth('date', Carbon::now()->month)
            ->count();
    }

    /**
     * All-time total visits.
     */
    public function totalVisits(): int
    {
        return PageVisit::count();
    }

    /**
     * Most visited pages.
     *
     * @return \Illuminate\Support\Collection
     */
    public function topPages(int $limit = 10)
    {
        return PageVisit::selectRaw('path, COUNT(*) as total')
            ->groupBy('path')
            ->orderByDesc('total')
            ->limit($limit)
            ->get();
    }

    /**
     * Visits grouped by day for the last N days.
     *
     * @return \Illuminate\Support\Collection
     */
    public function visitsByDay(int $days = 30)
    {
        $from = Carbon::now()->subDays($days - 1)->startOfDay();

        return PageVisit::selectRaw('date, COUNT(*) as total')
            ->where('date', '>=', $from->toDateString())
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    /**
     * Visits for a specific path (all time).
     *
     * @return \Illuminate\Support\Collection
     */
    public function visitsByPage(string $path)
    {
        return PageVisit::where('path', $path)
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Most recent visits.
     *
     * @return \Illuminate\Support\Collection
     */
    public function latestVisits(int $limit = 20)
    {
        return PageVisit::with('user')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Delete all page visit records for today for the given IP address.
     */
    public function forgetTodayByIp(string $ip): void
    {
        PageVisit::where('ip', $ip)
            ->where('date', Carbon::today()->toDateString())
            ->delete();
    }
}
