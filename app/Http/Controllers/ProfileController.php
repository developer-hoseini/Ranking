<?php

namespace App\Http\Controllers;

use App\Enums\ReasonReportEnum;
use App\Enums\StatusEnum;
use App\Models\Competition;
use App\Models\Game;
use App\Models\GameResult;
use App\Models\Like;
use App\Models\Report;
use App\Models\Status;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show(User $user)
    {
        $user->load([
            'profile.state.country',
        ])->loadSum('coinAchievements', 'count')
            ->loadCount('likes');

        if (auth()->check()) {
            $user->loadCount([
                'likes as is_like' => fn ($q) => $q->where('liked_by_user_id', auth()->id()),
            ]);
        }
        //        $daysAgo = Carbon::now()->subDays(config('setting.days_ago'));

        $statues = Status::select(['id', 'name'])
            ->where('model_type', GameResult::class)
            ->get();

        $userGames = Game::query()
            ->whereHas('gameCompetitionsUsers', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->withCount([
                'gameCompetitionsGameResults as win_count' => function ($query) use ($user, $statues) {
                    $query->where('playerable_type', User::class)
                        ->where('playerable_id', $user->id)
                        ->where('game_results.game_result_status_id', $statues->where('name', StatusEnum::GAME_RESULT_WIN->value)->first()?->id);
                },
                'gameCompetitionsGameResults as win_absent' => function ($query) use ($user, $statues) {
                    $query->where('playerable_type', User::class)
                        ->where('playerable_id', $user->id)
                        ->where('game_results.game_result_status_id', $statues->where('name', StatusEnum::GAME_RESULT_ABSENT->value)->first()?->id);
                },
                'gameCompetitionsGameResults as lose_count' => function ($query) use ($user, $statues) {
                    $query->where('playerable_type', User::class)
                        ->where('playerable_id', $user->id)
                        ->where('game_results.game_result_status_id', $statues->where('name', StatusEnum::GAME_RESULT_LOSE->value)->first()?->id);
                },
            ])
            ->withSum([
                'gameCompetitionsScoreOccurredModel' => function ($query) use ($user) {
                    $query->where('achievementable_type', User::class)
                        ->where('achievementable_id', $user->id);
                },
            ], 'count')
            ->orderByDesc('game_competitions_score_occurred_model_sum_count')
            ->get();

        // TODO: Certificates
        //        $certificates = Certificate::where(['exported' => $status['Yes'], 'user_id' => $user->id])->with('bracket.competition')->get();

        return view('profile.show', compact('user', 'userGames'));

    }

    public function like(Request $request): bool
    {
        if ($request->ajax() && Auth::check() && $request->has('liked_id')) {
            $likedId = $request->get('liked_id');

            $likedUser = User::find($likedId);

            if (! $likedUser) {
                return false;
            }

            $userLike = Like::where([
                'liked_by_user_id' => Auth::id(),
                'likeable_id' => $likedId,
                'likeable_type' => User::class,
            ])->get();

            if ($userLike->count() === 0) {
                $likedUser->likes()->create([
                    'liked_by_user_id' => Auth::id(),
                ]);
            } else {
                $likedUser->likes()
                    ->where('liked_by_user_id', Auth::id())
                    ->delete();
            }

            return true;

        }

        return false;
    }

    public function report(Request $request): void
    {
        if ($request->ajax() && Auth::check() && $request->has('user_id')) {

            $userId = $request->get('user_id');

            $report = Report::where([
                'reporter_id' => Auth::id(),
                'reported_id' => $userId,
                'status_id' => Status::where('name', StatusEnum::PENDING->value)->first()?->id,
            ])->get();

            if ($report->count() === 0) {
                Report::create([
                    'reporter_id' => Auth::id(),
                    'reported_id' => $userId,
                    'status_id' => Status::where('name', StatusEnum::PENDING->value)->first()?->id,
                    'reason' => ReasonReportEnum::PROFILE_PHOTO->value,
                ]);
            }
        }
    }

    public function competitions(Request $request)
    {
        if ($request->ajax() && $request->has('user_id')) {
            $userId = $request->input('user_id');
            $data = null;

            $tournaments = Competition::whereHas('users', function ($query) use ($userId) {
                $query->where('users.id', $userId);
            })->orwhereHas('teams', function ($query) use ($userId) {
                $query->where('teams.id', $userId);
            })->with([
                'teams', 'users',
            ])->orderBy('start_at', 'DESC')->get();

            foreach ($tournaments as $tournament) {
                if ($tournament->teams->count()) {
                    //                    $bracketRoute = 'bracket';
                    $tournamentType = '<span class="mx-2">'.__('words.only').'</span><i class="fa fa-user"></i>';
                } else {
                    //                    $bracketRoute = 'team_bracket';
                    $tournamentType = '<span class="mx-2">'.__('words.team').'</span><i class="fa fa-users"></i>';
                }

                $bracket = '';

                //                if ($tournament->final_bracket_id != null) {
                //                    if ($tournament->is_team == $status['No']) {
                //                        $bracket_title = $tournament->final_bracket->title;
                //                    } else {
                //                        $bracket_title = $tournament->team_final_bracket->title;
                //                    }
                //                    $bracket = '<a href="'.route($bracket_route, ['bracket_id' => $tournament->final_bracket_id, 'tour_name' => $tournament->name, 'bracket_title' => $bracket_title]).'" class="float-right-rtl profile-menu-responsive mx-2 mx-md-4 mx-lg-4" style="color: #ff4800;"><img src="'.url('img/tournament_icon.png').'" width="18px" class="mx-1">'.__('words.tournament_bracket').'</a>';
                //                } else {
                //                    $bracket = '';
                //                }

                $data .= '<div class="bg-light border rounded px-3 py-2 mt-2" style="height: 40px;"><a href="'.route('tournaments.show', $tournament->id).'" class="float-right-rtl">'.$tournament->name.'</a>'.$bracket.'<div class="float-left-rtl">'.$tournamentType.'</div></div>';
            }
            /*foreach ($tournaments_teams as $tournament){
                $data .= '<div class="bg-light border rounded px-3 py-2 mt-2" style="height: 40px;"><a href="'.route('tournament.show',['tournament'=>$tournament->id]).'" class="float-right-rtl">'.$tournament->name.'</a><div class="float-left-rtl"><span class="mx-2">'.$tournament->team_name.'</span><i class="fa fa-users"></i></div></div>';
            }*/

            if ($data == '') {
                $data = '<div class="w-100 text-center pt-2">'.__('words.dont_yet_any_tournament_register').' ...</div>';
            }

            return response()->json($data);
        }
    }

    public function teamCertificates()
    {
        //TODO : refactor
        //        $auth_user_id = Auth::user()->id;

        //        $certificates = \App\Team_Certificate::with(['bracket.competition', 'team'])
        //            ->where('exported', config('status.Yes'))
        //            ->whereHas('team', function ($query) use ($auth_user_id) {
        //                $query->whereHas('members', function ($query) use ($auth_user_id) {
        //                    $query->where('user_id', $auth_user_id);
        //                })->orWhere('capitan_id', $auth_user_id);
        //            })->orderBy('id', 'desc')->get();

        $certificates = collect([]);

        return view('profile.teamCertificates', [
            'certificates' => $certificates,
        ]);
    }
}
