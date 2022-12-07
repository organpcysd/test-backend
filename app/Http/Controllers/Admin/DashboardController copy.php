<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Analytics\AnalyticsFacade as Analytics;
use Spatie\Analytics\Period;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $visitors = Analytics::fetchTotalVisitorsAndPageViews(Period::days(7))->pluck('visitors')->toArray();
        $pageviews = Analytics::fetchTotalVisitorsAndPageViews(Period::days(7))->pluck('pageViews')->toArray();
        $datevisit = Analytics::fetchTotalVisitorsAndPageViews(Period::days(7))->pluck('date')->toArray();
        $mostpages = Analytics::fetchMostVisitedPages(Period::days(365), 6);
        $analyticsData = Analytics::performQuery(
            Period::days(365),
            'ga:sessions,ga:visitors,ga:pageviews,ga:bounceRate,ga:avgSessionDuration,ga:percentNewSessions,ga:pageviewsPerSession,ga:newUsers',
        )->totalsForAllResults;

        $date = [];
        foreach ($datevisit as $d) {
            array_push($date, Carbon::createFromFormat('Y-m-d H:i:s', $d)->format('d M Y'));
        }
        return view('admin.dashboard', compact('analyticsData', 'mostpages', 'visitors', 'pageviews', 'date'));
    }
}
