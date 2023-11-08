<?php

namespace App\Http\Controllers;

use App\Enums\StatusEnum;
use App\Models\Competition;
use App\Models\Game;
use App\Models\Invite;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use DB;
use File;
use Illuminate\Http\Request;
use Image;

class GamePageController extends Controller
{
    public function index(Game $game, User $opponent = null)
    {

        $competition = Competition::query()
            ->whereHas('users', fn ($q) => $q->where('users.id', auth()->id()))
            ->where('game_id', $game->id)
            ->with(['users', 'game'])
            ->first();

        if (! $competition) {
            return redirect()->route('games.index');
        }

        // cant invite yourself!
        if ($opponent && $opponent?->id === auth()->id()) {
            return redirect()->route('games.index');
        }

        // after 10 plays, must complete his profile
        $invitesCount = Invite::where(function ($query) {
            $query->where('inviter_user_id', auth()?->id())
                ->orWhere('invited_user_id', auth()?->id());
        })->whereHas('gameStatus', fn ($q) => $q->where('name', StatusEnum::FINISHED))
            ->count();

        if ($invitesCount >= config('setting.profile_middleware_count') && empty(auth()->user()?->profile?->bio)) {
            return redirect()->route('edit_profile')->withErrors([
                'message' => __('message.Please complete your profile first'),
            ]);
        }

        //        $setting = config('setting');

        //        $game = \App\Game::withCount(['scores', 'invites', 'in_club', 'with_image'])->where('id', $game_id)->first();

        //$score = \App\User_Score::where(['user_id'=>$user_id, 'game_id'=>$game_id])->first();

        /* $inclub_stars = \App\Invite::inclub_stars($user_id, $game_id, $status, $setting);
         $image_stars = \App\Invite::image_stars($user_id, $game_id, $status, $setting);

         $team_played_stars = \App\Team_Played_User::whereHas('team', function ($query) use ($game_id) {
             $query->where('game_id', $game_id);
         })->where([
             ['user_id', '=', $user_id],
             ['dt', '>=', $setting['days_ago']],
         ])->count();*/

        //Warnings
        /*$false_result = \App\Event::where(['user_id' => $user_id, 'type' => config('event.Warning'), 'reason' => config('reason.False_Result')])->count();
        $no_submit = \App\Event::where(['user_id' => $user_id, 'type' => config('event.Warning'), 'reason' => config('reason.No_Submit')])->count();
        $you_absent = \App\Event::where(['user_id' => $user_id, 'type' => config('event.Warning'), 'reason' => config('reason.You_Absent')])->count();
        $false_image = \App\Event::where(['user_id' => $user_id, 'type' => config('event.Warning'), 'reason' => config('reason.False_Image')])->count();
        $false_club = \App\Event::where(['user_id' => $user_id, 'type' => config('event.Warning'), 'reason' => config('reason.False_Club')])->count();*/
        /*
                $sent = \App\Invite::with(['invited', 'club'])->where([
                    ['inviter_id', $user_id],
                    ['game_id', $game_id],
                    ['status', $status['Pending']],
                ])->get();

                $received = \App\Invite::with(['inviter', 'inviter.profile', 'club'])->where([
                    ['invited_id', $user_id],
                    ['game_id', $game_id],
                    ['status', $status['Pending']],
                ])->get();

                $game_results = DB::table('invite')->join('game_result', 'invite.id', '=', 'game_result.invite_id')->where('game_id', $game_id)->where(
                    function ($query) use ($user_id) {
                        $query->where('inviter_id', $user_id)->orWhere('invited_id', $user_id);
                    })->whereNotIn('status', [$status['Pending'], $status['Rejected'], $status['Canceled']])->orderBy('invite.id', 'desc')->get();

                $no_submit_results_count = \App\Invite::whereHas('no_submit_result')->whereRaw('game_id=? and (inviter_id=? or invited_id=?)', [$game_id, $user_id, $user_id])->count();
                $one_submit_results_count = \App\Invite::whereHas('one_submit_result')->whereRaw('game_id=? and (inviter_id=? or invited_id=?)', [$game_id, $user_id, $user_id])->count();

                if ($opponent_id) {
                    $user_score = \App\User_Score::where(
                        ['user_id' => $opponent_id, 'game_id' => $game_id, 'is_join' => $status['Yes']]
                    )->first();

                    if ($user_score) {
                        $opponent = \App\User::with(['scores'])->where('id', $opponent_id)->first();
                    } else {
                        $opponent = null;
                    }
                } else {
                    $opponent = null;
                }*/

        return view('games.page.index', [
            'game' => $game,
            /* 'score' => $score,
            'inclub_stars' => $inclub_stars,
            'image_stars' => $image_stars,
            'team_played_stars' => $team_played_stars,
            'false_result' => $false_result,
            'no_submit' => $no_submit,
            'you_absent' => $you_absent,
            'false_image' => $false_image,
            'false_club' => $false_club,
            'sent' => $sent,
            'received' => $received,
            'game_results' => $game_results,
            'no_submit_results_count' => $no_submit_results_count,
            'one_submit_results_count' => $one_submit_results_count,*/
            'opponent' => $opponent,
        ]);
    }

    public function get_country(Request $request)
    {
        if ($request->ajax()) {
            $data = \App\Country::whereHas('state', function ($query) {
                $query->whereHas('clubs');
            })->get();

            return response()->json($data);
        }
    }

    public function get_states(Request $request)
    {
        if ($request->ajax()) {
            $data = \App\State::whereHas('clubs')
                ->where('country_id', $request->get('country_id'))->orderBy('name', 'asc')->get();

            return response()->json($data);
        }
    }

    public function get_clubs(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('club')
                ->where(['state_id' => $request->state_id, 'active' => config('status.Yes')])->orderBy('sort')->get();

            return response()->json($data);
        }
    }

    public function random_users(Request $request)
    {
        if ($request->ajax()) {
            $game_id = $request->get('game_id');
            $auth_state_id = Auth::user()->profile->state_id;

            $data = \App\User::with(['scores'])->whereHas('scores', function ($query) use ($game_id) {
                $query->where(['game_id' => $game_id, 'is_join' => config('status.Yes')]);
            })->whereHas('profile', function ($query) use ($auth_state_id) {
                $query->where(['state_id' => $auth_state_id]);
            })->where([
                ['id', '!=', Auth::user()->id],
                ['status', config('status.Active')],
            ])->inRandomOrder()->take(config('setting.random_users'))->get();

            return response()->json($data);
        }
    }

    public function search_user(Request $request)
    {
        if ($request->ajax()) {
            $fullname_fa = str_replace('ي', 'ی', str_replace('ك', 'ک', $request->get('username')));
            $fullname_ar = str_replace('ی', 'ي', str_replace('ک', 'ك', $request->get('username')));
            $game_id = $request->get('game_id');

            if (! empty($fullname_fa)) {
                $data = \App\User::with(['profile', 'scores' => function ($query) use ($game_id) {
                    $query->where(['game_id' => $game_id, 'is_join' => config('status.Yes')]);
                }])
                    ->whereHas('profile', function ($query) use ($fullname_fa, $fullname_ar) {
                        $query->where(DB::raw('CONCAT(fname," ",lname)'), 'LIKE', '%'.$fullname_fa.'%')
                            ->orWhere(DB::raw('CONCAT(fname," ",lname)'), 'LIKE', '%'.$fullname_ar.'%');
                    })
                    ->orWhere([
                        ['username', 'LIKE', '%'.$fullname_fa.'%'],
                    ])
                    ->where([
                        ['id', '!=', Auth::user()->id],
                        ['status', config('status.Active')],
                    ])->get();
            } else {
                $data = '';
            }

            return response()->json($data);
        }
    }

    public function select_user(Request $request)
    {
        if ($request->ajax()) {
            $user_id = $request->get('user_id');
            $game_id = $request->get('game_id');

            $data = \App\User_Score::where(['user_id' => $user_id, 'game_id' => $game_id])->first();

            return response()->json($data);
        }
    }

    public function check_gamepage_invites(Request $request)
    {
        if ($request->ajax()) {
            $sent_count = $request->get('sent_count');
            $received_count = $request->get('received_count');
            $results_count = $request->get('results_count');
            $one_submit_results_count = $request->get('one_submit_results_count');
            $game_id = $request->get('game_id');

            $user_id = Auth::user()->id;

            $new_sent_count = \App\Invite::where([
                ['inviter_id', $user_id],
                ['game_id', $game_id],
                ['status', config('status.Pending')],
            ])->count();

            $new_received_count = \App\Invite::where([
                ['invited_id', $user_id],
                ['game_id', $game_id],
                ['status', config('status.Pending')],
            ])->count();

            $new_results_count = \App\Invite::whereHas('no_submit_result')->whereRaw('game_id=? and (inviter_id=? or invited_id=?)', [$game_id, $user_id, $user_id])->count();

            $new_one_submit_results_count = \App\Invite::whereHas('one_submit_result')->whereRaw('game_id=? and (inviter_id=? or invited_id=?)', [$game_id, $user_id, $user_id])->count();

            $data = false;

            if ($new_results_count < $results_count) {
                $request->session()->flash('message', __('message.your_opponent_submitted_the_result'));
                $request->session()->flash('alert-class', 'alert-success');
                $data = true;
            } elseif ($new_results_count > $results_count) {
                $request->session()->flash('message', __('message.your_invite_has_accepted'));
                $request->session()->flash('alert-class', 'alert-success');
                $data = true;
            } elseif ($new_one_submit_results_count < $one_submit_results_count) {
                $request->session()->flash('message', __('message.opponent_submitted_and_final_result_determined'));
                $request->session()->flash('alert-class', 'alert-success');
                $data = true;
            } elseif ($new_received_count > $received_count) {
                $received = \App\Invite::with(['inviter'])->where([
                    ['invited_id', $user_id],
                    ['game_id', $game_id],
                    ['status', config('status.Pending')],
                ])->orderBy('id', 'desc')->first();

                $request->session()->flash('message', __('message.you_have_new_invite',
                    ['username' => $received->inviter->username]));
                $request->session()->flash('alert-class', 'alert-success');
                $data = true;
            } elseif (($new_sent_count < $sent_count) || ($new_received_count < $received_count)) {
                $data = true;
            }

            return response()->json($data);
        }
    }

    public function accept(Request $request, $invite_id)
    {
        $invite = \App\Invite::where([
            'id' => $invite_id,
            'invited_id' => Auth::user()->id,
            'status' => config('status.Pending'),
        ])->first();

        // yes, really received the invite
        if ($invite) {
            $invite->status = config('status.Accepted');
            $invite->save();
            $game_result = \App\Game_Result::create(['invite_id' => $invite_id]);

            /*if( $invite->inviter_id == Auth::user()->id )
              $opponent_id = $invite->invited_id;
            else
              $opponent_id = $invite->inviter_id;*/

            \App\Event::create([
                'user_id' => $invite->inviter_id,
                'invite_id' => $invite->id,
                'type' => config('event.Info'),
                'reason' => config('reason.Invite_Accepted'),
                'dt' => date('Y-m-d H:i:s', time()),
                'seen' => config('status.No'),
            ]);

            // SMS
            $mobile = $invite->inviter->profile->sms_mobile;
            if ($mobile) {
                $message = __('message.sms_your_invite_has_accepted',
                    ['fullname' => $invite->invited->profile->fullname]);

                \App\SMS::send($mobile, $message, route('gamepage', ['game_id' => $invite->game->id]));
            }

            $request->session()->flash('message', __('message.invite_accepted'));
            $request->session()->flash('alert-class', 'alert-success');

            return redirect()->back();
        } else {
            $request->session()->flash('message', __('message.you_already_accepted'));
            $request->session()->flash('alert-class', 'alert-danger');

            return redirect()->back();
        }
    }

    public function reject(Request $request, $invite_id)
    {
        $invite = \App\Invite::where([
            'id' => $invite_id,
            'invited_id' => Auth::user()->id,
            'status' => config('status.Pending'),
        ])->first();

        // yes, really received the invite
        if ($invite) {
            $invite->status = config('status.Rejected');
            $invite->save();

            /*if($invite->inviter_id==Auth::user()->id)
              $opponent_id = $invite->invited_id;
            else
              $opponent_id = $invite->inviter_id;*/

            \App\Event::create([
                'user_id' => $invite->inviter_id,
                'invite_id' => $invite->id,
                'type' => config('event.Info'),
                'reason' => config('reason.Invite_Rejected'),
                'dt' => date('Y-m-d H:i:s', time()),
                'seen' => config('status.No'),
            ]);

            $request->session()->flash('message', __('message.invite_rejected'));
            $request->session()->flash('alert-class', 'alert-success');

            return redirect()->back();
        } else {
            $request->session()->flash('message', __('message.you_already_rejected'));
            $request->session()->flash('alert-class', 'alert-danger');

            return redirect()->back();
        }
    }

    public function cancel(Request $request, $invite_id)
    {
        $invite = \App\Invite::where([
            'id' => $invite_id,
            'inviter_id' => Auth::user()->id,
            'status' => config('status.Pending'),
        ])->first();

        // yes, really received the invite
        if ($invite) {
            $invite->status = config('status.Canceled');
            $invite->save();
            //$invite->delete();

            /*if($invite->inviter_id==Auth::user()->id)
            $opponent_id = $invite->invited_id;
            else
            $opponent_id = $invite->inviter_id;*/

            \App\Event::create([
                'user_id' => $invite->invited_id,
                'invite_id' => $invite->id,
                'type' => config('event.Info'),
                'reason' => config('reason.Invite_Canceled'),
                'dt' => date('Y-m-d H:i:s', time()),
                'seen' => config('status.No'),
            ]);

            $request->session()->flash('message', __('message.invite_canceled'));
            $request->session()->flash('alert-class', 'alert-success');

            return redirect()->back();
        } else {
            $request->session()->flash('message', __('message.you_already_canceled'));
            $request->session()->flash('alert-class', 'alert-danger');

            return redirect()->back();
        }
    }

    public function invite(Request $request)
    {
        $status = config('status');
        $setting = config('setting');

        $validatedData = $request->validate([
            'username' => 'required',
            'club' => 'required_if:in_club,1',
        ]);

        $game_id = $request->input('game_id');

        $invited_id = \App\User::whereHas('scores', function ($query) use ($game_id) {
            $query->where(['game_id' => $game_id, 'is_join' => config('status.Yes')]);
        })->where([
            ['username', '=', $request->input('username')],
            ['id', '!=', Auth::user()->id],
            ['status', config('status.Active')],
        ])->first('id')['id'];

        // username not found
        if (! $invited_id) {
            $request->session()->flash('message', __('message.username_not_found'));
            $request->session()->flash('alert-class', 'alert-danger');

            return redirect()->route('gamepage', ['game_id' => $game_id]);
        }

        // club not found
        if ($request->input('club')) {
            $club_count = \App\Club::where('id', $request->input('club'))->count();
            if (! $club_count) {
                return redirect()->route('gamepage', ['game_id' => $game_id]);
            }
        }

        // **** Received list of opponent is full? ****
        $received_count = \App\Invite::where([
            'invited_id' => $invited_id,
            'game_id' => $game_id,
            'status' => $status['Pending'],
        ])->count();

        if ($received_count >= $setting['max_received']) {
            $request->session()->flash('message', __('message.received_list_of_opponent_is_full'));
            $request->session()->flash('alert-class', 'alert-danger');

            return redirect()->route('gamepage', ['game_id' => $game_id]);
        }

        $user_id = Auth::user()->id;

        // **** Sent list is full? ****
        $sent_count = \App\Invite::where([
            'inviter_id' => $user_id,
            'game_id' => $game_id,
            'status' => $status['Pending'],
        ])->count();

        if ($sent_count >= $setting['max_sent']) {
            $request->session()->flash('message', __('message.your_sent_list_is_full', ['sent_max' => $setting['max_sent']]));
            $request->session()->flash('alert-class', 'alert-danger');

            return redirect()->route('gamepage', ['game_id' => $game_id]);
        }

        // **** Already Sent ****
        $already_send = \App\Invite::where(function ($query) use ($invited_id, $user_id) {
            $query->where(['inviter_id' => $user_id, 'invited_id' => $invited_id])
                ->orWhereRaw('invited_id=? AND inviter_id=?', [$user_id, $invited_id]);
        })->where('game_id', $game_id)
            ->whereIn('status',
                [$status['Pending'], $status['Accepted'],
                    $status['Wait_Image_Verify'], $status['Wait_Club_Verify'],
                ])->count();

        if ($already_send > 0) {
            $request->session()->flash('message', __('message.you_already_sent'));
            $request->session()->flash('alert-class', 'alert-danger');

            return redirect()->route('gamepage', ['game_id' => $game_id]);
        }

        // **** No Repeat ****

        if (! $request->input('club')) {
            // Have you played with this player in days ago?
            $last_send = DB::select('select count(*) as cnt from invite where dt > :dt and ((inviter_id = :user and invited_id = :opponent) or (invited_id = :userr and inviter_id = :opponentt)) and (game_id = :game_id and status = :status)', [
                ':dt' => Carbon::now()->subDays($setting['no_repeat_days']),
                ':user' => $user_id, ':opponent' => $invited_id, ':userr' => $user_id, ':opponentt' => $invited_id,
                ':game_id' => $game_id, ':status' => $status['End_True'],
            ])[0]->cnt;

            if ($last_send > 0) { // Yes
                // Do you enough played in days ago to play again with this player?
                $last_played = \App\Invite::where(function ($query) use ($user_id) {
                    $query->where('inviter_id', $user_id)
                        ->orWhere('invited_id', $user_id);
                })->where(['game_id' => $game_id, 'status' => $status['End_True']])
                    ->orderBy('id', 'desc')->take($setting['no_repeat_played'])->get(['inviter_id', 'invited_id']);

                if ($last_played->contains('inviter_id', $invited_id) ||
                    $last_played->contains('invited_id', $invited_id)) { // No
                    $request->session()->flash('message', __('message.days_not_take_or_latest_played_not_enough', ['days_count' => $setting['no_repeat_days'],
                        'played_count' => $setting['no_repeat_played']]));
                    $request->session()->flash('alert-class', 'alert-danger');

                    return redirect()->route('gamepage', ['game_id' => $game_id]);
                }
            }
        }

        // **** Save ****
        $game_type = '';

        if ($request->input('in_club')) {
            $game_type = $status['In_Club'];
        }

        if ($request->input('with_image')) {
            $game_type = $game_type.','.$status['With_Image'];
        }

        $invite = \App\Invite::create([
            'inviter_id' => $user_id,
            'invited_id' => $invited_id,
            'game_id' => $game_id,
            'game_type' => $game_type,
            'club_id' => $request->input('club'),
        ]);

        \App\Event::create([
            'user_id' => $invited_id,
            'invite_id' => $invite->id,
            'type' => config('event.Info'),
            'reason' => config('reason.Invite_Received'),
            'dt' => date('Y-m-d H:i:s', time()),
            'seen' => $status['No'],
        ]);

        // SMS
        $mobile = $invite->invited->profile->sms_mobile;
        if ($mobile) {
            $game_name = \App\Game::where('id', $game_id)->first()->name;
            $message = __('message.sms_you_have_new_invite',
                ['fullname' => $invite->inviter->profile->fullname, 'game' => __('games.'.$game_name)]);

            \App\SMS::send($mobile, $message, route('gamepage', ['game_id' => $game_id]));
        }

        $request->session()->flash('message', __('message.invite_sent_successfully', ['username' => $request->input('username')]));
        $request->session()->flash('alert-class', 'alert-success');

        return redirect()->route('gamepage', ['game_id' => $game_id]);
    }

    public function submit_result(Request $request, $invite_id)
    {
        $validatedData = $request->validate([
            'result' => 'required',
            'image' => 'required_if:with_referee,1|image|max:8192',
        ]);

        $invite = \App\Invite::with(['club', 'inviter', 'invited'])->where('id', $invite_id)->first();

        $user_id = Auth::user()->id;

        // take sure that invite is for auth (dont edit invite id in client side!)
        if ($invite->inviter_id != $user_id && $invite->invited_id != $user_id) {
            $request->session()->flash('message', __('message.invite_not_for_you'));
            $request->session()->flash('alert-class', 'alert-danger');

            return redirect()->back();
        }

        // does invite results already submited?
        if (($invite->inviter_id == $user_id && $invite->inviter_res != null)
            || ($invite->invited_id == $user_id && $invite->invited_res != null)) {
            $request->session()->flash('message', __('message.invite_already_submitted'));
            $request->session()->flash('alert-class', 'alert-danger');

            return redirect()->back();
        }

        $res = $request->input('result');
        $results = config('result');
        // is submitted result one of results array
        if (! array_search($res, $results)) {
            $request->session()->flash('message', __('message.submitted_result_unknown'));
            $request->session()->flash('alert-class', 'alert-danger');

            return redirect()->back();
        }

        // save image
        if ($invite->is_with_image) {
            $image = $request->file('image');
            $date = date('Y/m', strtotime($invite->dt));
            $dest = "uploads/players/$date/";

            if (! file_exists($dest)) {
                File::makeDirectory($dest, 0777, true);
            }

            $filename = $invite->id.'-'.$user_id.'.jpg';
            Image::make($image)->resize(600, 360)->save($dest.$filename);
        }

        // save result
        $setting = config('setting');
        $status = config('status');

        $game_result = \App\Game_Result::where('invite_id', $invite_id)->first();

        if ($invite->inviter_id == $user_id) {
            $game_result->inviter_res = $res;
            if ($invite->invited_res == null) {
                $game_result->result_dt = date('Y-m-d H:i:s', time());
            }
            $opponent_id = $invite->invited_id;
        } else {
            $game_result->invited_res = $res;
            if ($invite->inviter_res == null) {
                $game_result->result_dt = date('Y-m-d H:i:s', time());
            }
            $opponent_id = $invite->inviter_id;
        }

        $game_result->save(); // result saved

        \App\Event::create([
            'user_id' => $opponent_id,
            'invite_id' => $invite->id,
            'type' => config('event.Info'),
            'reason' => config('reason.Opponent_Submitted'),
            'dt' => date('Y-m-d H:i:s', time()),
            'seen' => $status['No'],
        ]);

        // change status
        if ($game_result->inviter_res != null && $game_result->invited_res != null) {
            if ($invite->is_with_image) {
                $game_result->verify_image = $status['Pending'];
                $invite->status = $status['Wait_Image_Verify'];
                if ($invite->is_inclub) {
                    $game_result->verify_club = $status['Pending'];
                }
            } elseif ($invite->is_inclub) {
                $game_result->verify_club = $status['Pending'];
                $invite->status = $status['Wait_Club_Verify'];
            } else {
                \App\Final_Result::End($invite, $game_result);
            }

            $game_result->save(); // save "accept_image" & "accept_club"
            $invite->save(); // save "status"
        }

        $request->session()->flash('message', __('message.result_saved'));
        $request->session()->flash('alert-class', 'alert-success');

        return redirect()->back();

    }
}