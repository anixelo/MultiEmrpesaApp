<?php

namespace MultiempresaApp\SimpleAnalytics\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void  track(\Illuminate\Http\Request $request)
 * @method static int   visitsToday()
 * @method static int   visitsYesterday()
 * @method static int   visitsThisMonth()
 * @method static int   totalVisits()
 * @method static \Illuminate\Support\Collection topPages(int $limit = 10)
 * @method static \Illuminate\Support\Collection visitsByDay(int $days = 30)
 * @method static \Illuminate\Support\Collection visitsByPage(string $path)
 * @method static \Illuminate\Support\Collection latestVisits(int $limit = 20)
 * @method static void  forgetTodayByIp(string $ip)
 *
 * @see \MultiempresaApp\SimpleAnalytics\Services\AnalyticsService
 */
class SimpleAnalytics extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \MultiempresaApp\SimpleAnalytics\Services\AnalyticsService::class;
    }
}
