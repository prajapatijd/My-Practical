<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $sort_by = $request->get('sort_by');
        $filter = $request->get('filter');

        $activityQuery = Activity::query();

        if ($sort_by === 'day') {
            $activityQuery->whereDate('performed_at', now());
        } elseif ($sort_by === 'month') {
            $activityQuery->whereMonth('performed_at', now()->month)
                        ->whereYear('performed_at', now()->year);
        } elseif ($sort_by === 'year') {
            $activityQuery->whereYear('performed_at', now()->year);
        }

        $userIds = $activityQuery->pluck('user_id')->unique();

        $users = User::whereIn('id', $userIds)->with('activities')->get();

        $pointsMap = [];
        $users = $users->map(function ($user) use ($sort_by, &$pointsMap) {
            $filteredActivities = $user->activities->filter(function ($activity) use ($sort_by) {
                if ($sort_by === 'day') {
                    return $activity->performed_at->isToday();
                } elseif ($sort_by === 'month') {
                    return $activity->performed_at->isSameMonth(now());
                } elseif ($sort_by === 'year') {
                    return $activity->performed_at->isSameYear(now());
                }
                return true;
            });

            $filteredPoints = $filteredActivities->sum('points');

            $pointsMap[$user->id] = $filteredPoints;

            return $user;
        });

        $users = $users->sortByDesc(function ($user) use ($pointsMap) {
            return $pointsMap[$user->id] ?? 0;
        })->values();

        $rank = 1;
        $prevPoints = null;
        $currentRank = 1;

        foreach ($users as $user) {
            $userPoints = $pointsMap[$user->id] ?? 0;

            if ($prevPoints !== $userPoints) {
                $currentRank = $rank;
            }

            $user->rank = $currentRank;
            $user->save();

            $user->total_point = $userPoints;

            $prevPoints = $userPoints;
            $rank++;
        }

        if ($filter) {
            $searchUser = $users->firstWhere('id', $filter);
            $users = $searchUser ? collect([$searchUser]) : collect();
        }
        // dd($pointsMap);
        return view('welcome', compact('users', 'sort_by', 'filter','pointsMap'));
    }


    public function recalculate()
    {
        return redirect()->route('leaderboard.index');
    }
}
