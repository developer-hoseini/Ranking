<?php

namespace App\Http\Controllers;

use App\Models\Cup;

class CupController extends Controller
{
    public function show($cupId)
    {
        $cup = Cup::whereId($cupId)
            ->withWhereHas('competitions')
            ->with([
                'competitions.gameResults.gameResultStatus',
                'competitions.users.media',
            ])
            ->firstOrFail();

        return view('cups.show', compact('cup'));
    }
}
