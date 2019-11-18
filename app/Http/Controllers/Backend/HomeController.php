<?php

namespace App\Http\Controllers\Backend;

use View;
use Response;
use Request;
use Cache;
use DB;
use Carbon\Carbon;
use Suitcore\Models\SuitModel;
use Suitcore\Repositories\SuitRepository;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserBooking;

class HomeController extends BackendController
{
    // METHODS
    /**
     * Override Default Constructor.
     */
    public function __construct()
    {
        parent::__construct(new SuitRepository(), new SuitModel());
        // page ID
        $this->setID('A1');
        View::share('title', 'Dashboard');
        View::share('pageTitle', 'Dashboard');
    }

    private function getDateRangeParameters($request) {
        $date_from = Carbon::createFromFormat('Y-m-d', '2017-02-01'); // Carbon::now()->subMonth(1)->startOfDay();
        try {
            if ($request->has('date_from')) {
                $date_from = Carbon::createFromFormat('Y-m-d', $request->get('date_from'))->startOfDay();
            }
        } catch (Exception $e) { } 
        $date_to = Carbon::now()->endOfDay();
        try {
            if ($request->has('date_to')) {
                $date_to = Carbon::createFromFormat('Y-m-d', $request->get('date_to'))->endOfDay();
            }
        } catch (Exception $e) { } 
        $databaseFormatFrom = $date_from->toDateTimeString();
        $databaseFormatTo = $date_to->toDateTimeString();
        return [$date_from, $date_to, $databaseFormatFrom, $databaseFormatTo];
    }

    public function getIndex()
    {
        list($date_from, $date_to, $databaseFormatFrom, $databaseFormatTo) = $this->getDateRangeParameters(request());

        // USERS STATISTIC
        $appActiveUsers = Cache::remember('app_active_users_' . $date_from->format('d_m_Y') . '_' . $date_to->format('d_m_Y'), 1, function() use($databaseFormatFrom, $databaseFormatTo) {
            return User::where('created_at', '>=', $databaseFormatFrom)->where('created_at', '<=', $databaseFormatTo)->where('status', User::STATUS_ACTIVE)->count('id');
        });
        $appInactiveUsers = Cache::remember('app_inactive_users_' . $date_from->format('d_m_Y') . '_' . $date_to->format('d_m_Y'), 1, function() use($databaseFormatFrom, $databaseFormatTo) {
            return User::where('created_at', '>=', $databaseFormatFrom)->where('created_at', '<=', $databaseFormatTo)->where('status', User::STATUS_INACTIVE)->count('id');
        });
        $appArtistUsers = Cache::remember('company_active_users_' . $date_from->format('d_m_Y') . '_' . $date_to->format('d_m_Y'), 1, function() use($databaseFormatFrom, $databaseFormatTo) {
            return UserProfile::where('created_at', '>=', $databaseFormatFrom)->where('created_at', '<=', $databaseFormatTo)->whereHas('user', function($q) {
                $q->where('status', User::STATUS_ACTIVE);
            })->count('id');
        });
        $appBannedUsers = Cache::remember('company_closed_users_' . $date_from->format('d_m_Y') . '_' . $date_to->format('d_m_Y'), 1, function() use($databaseFormatFrom, $databaseFormatTo) {
            return User::where('created_at', '>=', $databaseFormatFrom)->where('created_at', '<=', $databaseFormatTo)->where('status', User::STATUS_BANNED)->count('id');
        });

        // BOOKINGS STATISTIC
        $acceptedBookings = Cache::remember('accepted_bookings_' . $date_from->format('d_m_Y') . '_' . $date_to->format('d_m_Y'), 1, function() use($databaseFormatFrom, $databaseFormatTo) {
            return UserBooking::where('created_at', '>=', $databaseFormatFrom)->where('created_at', '<=', $databaseFormatTo)->where('status', UserBooking::STATUS_APPROVED)->count('id');
        });
        $rejectedBookings = Cache::remember('rejected_bookings_' . $date_from->format('d_m_Y') . '_' . $date_to->format('d_m_Y'), 1, function() use($databaseFormatFrom, $databaseFormatTo) {
            return UserBooking::where('created_at', '>=', $databaseFormatFrom)->where('created_at', '<=', $databaseFormatTo)->where('status', UserBooking::STATUS_REJECTED)->count('id');
        });
        $canceledBoookings = Cache::remember('canceled_bookings_' . $date_from->format('d_m_Y') . '_' . $date_to->format('d_m_Y'), 1, function() use($databaseFormatFrom, $databaseFormatTo) {
            return UserBooking::where('created_at', '>=', $databaseFormatFrom)->where('created_at', '<=', $databaseFormatTo)->where('status', UserBooking::STATUS_CANCELED)->count('id');
        });
        $doneBookings = Cache::remember('done_bookings_' . $date_from->format('d_m_Y') . '_' . $date_to->format('d_m_Y'), 1, function() use($databaseFormatFrom, $databaseFormatTo) {
            return UserBooking::where('created_at', '>=', $databaseFormatFrom)->where('created_at', '<=', $databaseFormatTo)->where('status', UserBooking::STATUS_DONE)->count('id');
        });

        // RENDER
        return View::make('backend.dashboard', compact('date_from', 'date_to', 'appActiveUsers', 'appInactiveUsers', 'appArtistUsers', 'appBannedUsers', 'acceptedBookings', 'rejectedBookings', 'canceledBoookings', 'doneBookings'));
    }

    public function pattern()
    {
        return view('backend.pattern');
    }

    public function getAppUserSummaryJson(Request $request)
    {
        list($date_from, $date_to, $databaseFormatFrom, $databaseFormatTo) = $this->getDateRangeParameters(request());
        $completeSeries = $this->initDateRangeSet($date_from, $date_to, 4);

        $appActiveUsers = User::where('created_at', '>=', $databaseFormatFrom)->where('created_at', '<=', $databaseFormatTo)->where('status', User::STATUS_ACTIVE)->select(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d') AS date, count(users.id) AS nbObj"))->orderBy('created_at', 'asc')->groupBy(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"))->get();
        foreach ($appActiveUsers as $key => $object) {
            $completeSeries[0][$object->date] = intval($object->nbObj);
        }

        $appInactiveUsers = User::where('created_at', '>=', $databaseFormatFrom)->where('created_at', '<=', $databaseFormatTo)->where('status', User::STATUS_INACTIVE)->select(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d') AS date, count(users.id) AS nbObj"))->orderBy('created_at', 'asc')->groupBy(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"))->get();
        foreach ($appInactiveUsers as $key => $object) {
            $completeSeries[1][$object->date] = intval($object->nbObj);
        }

        $appArtistUsers = UserProfile::where('created_at', '>=', $databaseFormatFrom)->where('created_at', '<=', $databaseFormatTo)->whereHas('user', function($q) {
            $q->where('status', User::STATUS_ACTIVE);
        })->select(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d') AS date, count(user_profiles.id) AS nbObj"))->orderBy('created_at', 'asc')->groupBy(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"))->get();
        foreach ($appArtistUsers as $key => $object) {
            $completeSeries[2][$object->date] = intval($object->nbObj);
        }

        $appBannedUsers = User::where('created_at', '>=', $databaseFormatFrom)->where('created_at', '<=', $databaseFormatTo)->where('status', User::STATUS_BANNED)->select(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d') AS date, count(users.id) AS nbObj"))->orderBy('created_at', 'asc')->groupBy(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"))->get();
        foreach ($appBannedUsers as $key => $object) {
            $completeSeries[3][$object->date] = intval($object->nbObj);
        }
        
        $categories = array_keys($completeSeries[0]);
        $series1 = array_values($completeSeries[0]);
        $series2 = array_values($completeSeries[1]);
        $series3 = array_values($completeSeries[2]);
        $series4 = array_values($completeSeries[3]);
        $title = "User Data " . $date_from->format('d F Y') . " - " . $date_to->format('d F Y');
        $data = [
            'title' => $title,
            'xAxis' => [
                'categories' => $categories,
                'title' => [
                    'text' => 'Date'
                ],
            ],
            'yAxisTitle' => 'New User',
            'series' => [
                [
                    'name' => trans('label.dashboard.active_app_users'),
                    'data' => $series1,
                ],
                [
                    'name' => trans('label.dashboard.inactive_app_users'),
                    'data' => $series2,
                ],
                [
                    'name' => trans('label.dashboard.artist_users'),
                    'data' => $series3,
                ],
                [
                    'name' => trans('label.dashboard.banned_users'),
                    'data' => $series4,
                ]
            ],
        ];
        return $data;
    }

    private function initDateRangeSet(Carbon $start_date, Carbon $end_date, $nbSub)
    {
        $series = [];
        for($i = 0; $i < $nbSub; $i++) {
            $series[$i] = [];
        }
        for($date = clone $start_date; $date->lte($end_date); $date->addDay()) {
            for($i = 0; $i < $nbSub; $i++) {
                $series[$i][$date->format('Y-m-d')] = 0;
            }
        }
        return $series;
    }
}
