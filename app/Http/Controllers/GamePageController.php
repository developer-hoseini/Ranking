<?php

namespace App\Http\Controllers;

use App\Enums\EventTypeEnum;
use App\Enums\ReasonEnum;
use App\Enums\StatusEnum;
use App\Http\Requests\GamePageInviteRequest;
use App\Mail\InvitedUserMail;
use App\Mail\InviteHasAcceptedMail;
use App\Models\Competition;
use App\Models\Event;
use App\Models\Game;
use App\Models\GameType;
use App\Models\Invite;
use App\Models\Status;
use App\Models\User;
use Auth;
use DB;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\In;
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
            return redirect()->route('profile.complete-profile')->withErrors([
                'message' => __('message.Please complete your profile first'),
            ]);
        }

        /*
        $game_results = DB::table('invite')
            ->join('game_result', 'invite.id', '=', 'game_result.invite_id')
            ->where('game_id', $game_id)->where(
            function ($query) use ($user_id) {
                $query->where('inviter_id', $user_id)->orWhere('invited_id', $user_id);
            })
            ->whereNotIn('status', [$status['Pending'], $status['Rejected'], $status['Canceled']])
            ->orderBy('invite.id', 'desc')->get();
*/

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
        /*[
            'game' => $game,
            'opponent' => $opponent,
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
            'one_submit_results_count' => $one_submit_results_count,
        ];*/

        return view('games.page.index', compact('game', 'opponent'));
    }

    /**
     * @throws \Throwable
     */
    public function accept(int $inviteId): ?\Illuminate\Http\RedirectResponse
    {
        $invite = Invite::where([
            'id' => $inviteId,
            'invited_user_id' => Auth::user()->id,
        ])->whereHas('confirmStatus', fn ($q) => $q->where('name', StatusEnum::PENDING->value))
            ->with(['inviterUser', 'invitedUser', 'game'])
            ->first();

        // yes, really received the invite
        if ($invite) {
            DB::beginTransaction();

            try {
                $invite->confirm_status_id = Status::where('name', StatusEnum::ACCEPTED->value)->first()?->id;
                $invite->save();

                $statusPendingId = Status::where('name', StatusEnum::PENDING->value)->first()?->id;

                $invite->competitions()->create([
                    'name' => "invite - {$invite->invitedUser?->username} vs {$invite->inviterUser?->username}",
                    'capacity' => 2,
                    'game_id' => $invite->game_id,
                    'status_id' => $statusPendingId,
                    'start_at' => now(),
                ]);

                $invite->competitions->first()->gameResults()->createMany([
                    [
                        'playerable_id' => $invite->invited_user_id,
                        'playerable_type' => User::class,
                        'game_result_status_id' => $statusPendingId,
                        'user_status_id' => $statusPendingId,
                    ],
                    [
                        'playerable_id' => $invite->inviter_user_id,
                        'playerable_type' => User::class,
                        'game_result_status_id' => $statusPendingId,
                        'user_status_id' => $statusPendingId,
                    ],
                ]);

                Event::create([
                    'user_id' => $invite->inviter_user_id,
                    'invite_id' => $invite->id,
                    'type' => EventTypeEnum::INFO,
                    'reason' => ReasonEnum::INVITE_ACCEPTED,
                    'seen' => 0,
                ]);

                DB::commit();

                // email
                $email = $invite->inviterUser?->email;
                if ($email) {
                    Mail::to($email)
                        ->queue(new InviteHasAcceptedMail($invite, $invite->game));
                }
            } catch (\Throwable $e) {
                DB::rollBack();

                throw $e;
            }

            return redirect()->back()->with('success', __('message.invite_accepted'));

        }

        return redirect()->back()->withErrors([
            'message' => __('message.you_already_accepted'),
        ]);
    }

    public function reject(int $inviteId): ?\Illuminate\Http\RedirectResponse
    {
        $invite = Invite::where([
            'id' => $inviteId,
            'invited_user_id' => Auth::user()->id,
        ])->whereHas('confirmStatus', fn ($q) => $q->where('name', StatusEnum::PENDING->value))
            ->first();

        // yes, really received the invite
        if ($invite) {
            $invite->confirm_status_id = Status::where('name', StatusEnum::REJECTED->value)->first()?->id;
            $invite->save();

            Event::create([
                'user_id' => $invite->inviter_user_id,
                'invite_id' => $invite->id,
                'type' => EventTypeEnum::INFO,
                'reason' => ReasonEnum::INVITE_REJECTED,
                'seen' => 0,
            ]);

            return redirect()->back()->with('success', __('message.invite_rejected'));

        }

        return redirect()->back()->withErrors([
            'message' => __('message.you_already_rejected'),
        ]);
    }

    public function cancel(int $inviteId): ?\Illuminate\Http\RedirectResponse
    {
        $invite = Invite::where([
            'id' => $inviteId,
            'inviter_user_id' => Auth::user()->id,
        ])->whereHas('confirmStatus', fn ($q) => $q->where('name', StatusEnum::PENDING->value))
            ->first();

        // yes, really received the invite
        if ($invite) {
            $invite->confirm_status_id = Status::where('name', StatusEnum::CANCELED->value)->first()?->id;
            $invite->save();

            Event::create([
                'user_id' => $invite->invited_user_id,
                'invite_id' => $invite->id,
                'type' => EventTypeEnum::INFO,
                'reason' => ReasonEnum::INVITE_CANCELED,
                'seen' => 0,
            ]);

            return redirect()->back()->with('success', __('message.invite_canceled'));
        }

        return redirect()->back()->withErrors(
            [
                'message' => __('message.you_already_canceled'),
            ]
        );
    }

    public function invite(GamePageInviteRequest $request, Game $game): \Illuminate\Http\RedirectResponse
    {
        $gameType = [];

        if ($request->input('in_club', false)) {
            $gameType[] = 'in_club';
        }

        if ($request->input('with_image', false)) {
            $gameType[] = 'with_image';
        }

        $invite = Invite::create([
            'inviter_user_id' => auth()->id(),
            'invited_user_id' => $request->input('userId'),
            'game_id' => $game->id,
            'club_id' => $request->input('club'),
            'confirm_status_id' => Status::where('name', StatusEnum::PENDING->value)->first('id')?->id,
        ]);

        if (count($gameType) > 0) {
            $gameTypes = GameType::select('id')
                ->whereIn('name', $gameType)
                ->pluck('id');

            $invite->gameType()->attach($gameTypes->toArray());
        }

        Event::create([
            'user_id' => $request->input('userId'),
            'invite_id' => $invite->id,
            'type' => EventTypeEnum::INFO,
            'reason' => ReasonEnum::INVITE_RECEIVED,
            'seen' => 0,
        ]);

        // email
        $email = $invite->invitedUser?->email;
        if ($email) {
            Mail::to($email)
                ->queue(new InvitedUserMail($invite, $game));
        }

        return redirect()->route('games.page.index', ['game' => $game->id])
            ->with('success', __('message.invite_sent_successfully', ['username' => $request->input('username')]));
    }

    public function submitResult(Request $request, Invite $invite)
    {
        $validatedData = $request->validate([
            'result' => 'required',
            'image' => 'required_if:with_referee,1|image|max:8192',
        ]);

        //        $invite = Invite::with(['club', 'inviter', 'invited'])->where('id', $invite_id)->first();

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
            'type' => EventTypeEnum::INFO,
            'reason' => ReasonEnum::OPPONENT_SUBMITTED,
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
