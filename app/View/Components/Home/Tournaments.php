<?php

namespace App\View\Components\Home;

use App\Enums\StatusEnum;
use App\Models\Competition;
use App\Models\Gallery;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Tournaments extends Component
{
    public function render(): View
    {
        ////////// Tournaments //////////
        $tournaments = Competition::query()
            ->whereHas('status', function ($query) {
                $query->whereIn('name', [
                    StatusEnum::ACTIVE->value,
                    StatusEnum::FINISHED->value,
                ]);
            })
            ->orderBy('id', 'DESC')
            ->with(['users', 'game', 'teams', 'state.country'])
            ->take(12)
            ->get();

        ////////// Tournament images //////////
        //        $tournamentImages = Gallery::whereNull('address')
        //            ->orderBy('id', 'DESC')
        //            ->whereHas('competition', function ($query) {
        //                $query->whereIn('status', [
        //                    config('status.Active'), config('status.Finished'), config('status.Pending_Finished')]);
        //            })
        //            ->with('competition')
        //            ->take(12)
        //            ->get();

        $tournamentImages = [];

        return view('components.home.tournaments', compact('tournaments', 'tournamentImages'));
    }
}
