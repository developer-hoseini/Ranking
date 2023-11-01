<?php

namespace App\View\Components\Home;

use App\Enums\StatusEnum;
use App\Models\Competition;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Ranks extends Component
{
    public function render(): View
    {

        $competitions = Competition::query()
            ->has('users', '>=', config('setting.home_ranks_table'))
            ->with(['game' => fn ($q) => $q->orderBy('score', 'desc')])
            ->whereHas('status', fn ($q) => $q->where('name', StatusEnum::FINISHED->value))
            ->orderBy('coin', 'desc')
            ->take(config('setting.home_ranks'))
            ->get();

        return view('components.home.ranks', compact('competitions'));
    }
}
