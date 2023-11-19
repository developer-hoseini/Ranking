<?php

namespace App\Livewire\Pages\Teams\Me\Index;

use App\Enums\StatusEnum;
use App\Models\Invite;
use App\Models\Status;
use App\Models\Team;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class RequestedToJoin extends Component
{
    #[Computed]
    public function invitedToTeam()
    {
        $invites = Invite::query()
            ->where([
                'inviteable_type' => Team::class,
                'invited_user_id' => \Auth::id(),
            ])
            ->with([
                'inviteable',
            ])
            ->latest()
            ->paginate(config('ranking.settings.global.per_page'));

        return $invites;
    }

    #[On('change-status')]
    public function changeStatus($inviteId, $type)
    {
        $authUser = \Auth::user();

        $invite = Invite::query()
            ->where([
                'id' => $inviteId,
                'invited_user_id' => $authUser->id,
                'confirm_status_id' => Status::nameScope(StatusEnum::PENDING->value)->firstOrFail()?->id,
            ])
            ->firstOrFail();

        $team = $invite->inviteable;

        DB::beginTransaction();

        try {
            if ($type === 'accept') {
                $invite->update([
                    'confirm_status_id' => Status::nameScope(StatusEnum::ACCEPTED->value)->firstOrFail()?->id,
                ]);

                $team->users()->attach($authUser);
            }

            if ($type === 'reject') {
                $invite->update([
                    'confirm_status_id' => Status::nameScope(StatusEnum::REJECTED->value)->firstOrFail()?->id,
                ]);

                $team->users()->detach($authUser);
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        session()->flash('success', 'status changed successfully');

        $this->redirect(route('teams.me.index', ['type' => 'requested-to-join']));

    }

    public function render()
    {
        return view('livewire.pages.teams.me.index.requested-to-join');
    }
}
