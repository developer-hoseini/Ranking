<div id="app2" class="card result_box"
     x-init="setInterval(function(){@this.dispatchSelf('reloadGameResults');},10000)">
    <a data-toggle="collapse" href="#Results" role="button" aria-expanded="false" aria-controls="Results"
       class="collapsed">
        <div class="card-header text-center">
            <h5>
                {{ __('words.Results').' ('.$inviteGameResults->count().')' }}
                <i class="fas fa-chevron-up"></i>
                <i class="fas fa-chevron-down"></i>
            </h5>
        </div>
    </a>
    <div class="card-body collapse" id="Results">

        <ul class="list-group" wire:key="game-{{$game->id}}">
            @foreach($inviteGameResults as $inviteGameResult)
                <livewire:pages.games.page.index.submit-game-result :game="$game"
                                                                    wire:key="invite-{{$inviteGameResult->id}}"
                                                                    :invite-game-result="$inviteGameResult"/>
            @endforeach
        </ul>

    </div>
</div>
