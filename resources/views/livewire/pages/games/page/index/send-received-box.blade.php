<div x-init="setInterval(function(){@this.dispatchSelf('reloadCurrentUser');},10000)">

    <div class="card box sent_box">
        <a data-toggle="collapse" href="#Sent_Invites" role="button" aria-expanded="false"
           aria-controls="Sent_Invites" class="collapsed">
            <div class="card-header text-center">
                <h5>
                    {{ __('words.Sent_Invites').' ('.$currentUser->inviter->count().')' }}
                    <i class="fas fa-chevron-up"></i>
                    <i class="fas fa-chevron-down"></i>
                </h5>
            </div>
        </a>
        <div class="card-body collapse" id="Sent_Invites">

            <ul class="list-group">
                @foreach($currentUser->inviter as $invite)
                    <li class="list-group-item">

                        <div class="sent-user-info">
                            <a href="{{ route('profile.show',['user'=>$invite->invited_user_id]) }}"
                               target="_blank" title="{{$invite->invitedUser?->username}}">
                                <img src="{{ $invite->invitedUser?->avatar }}" class="user_photo mb-2"
                                     width="80">
                                <div>{{ $invite->invitedUser->username9 }}</div>
                            </a>
                            <div>{{__('words.Rank: ')}} {{ \App\Services\Actions\User\GetGameRank::handle($invite->invited_user_id,$game->id) }}</div>
                            <div>{{__('words.Score: ')}} {{ $invite->invitedUser?->score_achievements_sum_count }}</div>
                        </div>


                        <div class="sent-info">
                            <div>
                                @if($invite->gameType?->whereIn('name','in_club')->count())
                                    <i class="fas fa-check txt-green"></i>
                                @else
                                    <i class="fas fa-times txt-red"></i>
                                @endif
                                <b>{{ __('words.In Club') }}</b>
                                @if($invite?->club)
                                    {{ '('.$invite->club?->name.')' }}
                                @endif
                            </div>
                            <div>
                                @if($invite->gameType?->whereIn('name','with_image')->count())
                                    <i class="fas fa-check txt-green"></i>
                                @else
                                    <i class="fas fa-times txt-red"></i>
                                @endif
                                <b>{{ __('words.With Referee') }}</b>
                            </div>

                            <div class="invite-dt"><span class="btn-dark btn-sm"><i class="fas fa-clock"></i> {{ $invite->created_at?->format("Y/m/d (H:i)") }}</span>
                            </div>

                            <div class="mb-3">
                                <b>{{ __('words.Status:') }}</b> {{ $invite?->confirmStatus?->name }}</div>
                            <div class="gamepage-buttons">
                                <a href="{{ route('chat.page',['user'=>$invite->invited_user_id]) }}"
                                   class="btn btn-primary btn-sm" target="_blank"><i
                                        class="fa fa-comments"></i> {{ __('words.Chat') }}</a>
                                <a href="{{ route('games.page.cancel',['inviteId'=>$invite->id]) }}"
                                   class="btn btn-danger btn-sm">
                                    <i class="fa fa-window-close"></i> {{ __('words.Cancel') }}</a>
                            </div>
                        </div>

                    </li>
                @endforeach
            </ul>

        </div>
    </div>

    <div class="card box received_box">
        <a data-toggle="collapse" href="#Received_Invites" role="button" aria-expanded="false"
           aria-controls="Received_Invites" class="collapsed">
            <div class="card-header text-center">
                <h5>
                    {{ __('words.Received_Invites').' ('.$currentUser->invited->count().')' }}
                    <i class="fas fa-chevron-up"></i>
                    <i class="fas fa-chevron-down"></i>
                </h5>
            </div>
        </a>
        <div class="card-body collapse" id="Received_Invites">

            <ul class="list-group">
                @foreach($currentUser->invited as $invite)
                    <li class="list-group-item">

                        <div class="receive-user-info">
                            <a href="{{ route('profile.show',['user'=>$invite->inviter_user_id]) }}"
                               target="_blank" title="{{$invite->inviterUser?->username}}">
                                <img src="{{ $invite->inviterUser?->avatar }}" class="user_photo mb-2"
                                     width="80">
                                <div>{{ $invite->inviterUser->username9 }}</div>
                            </a>
                            <div>{{__('words.Rank: ')}} {{ \App\Services\Actions\User\GetGameRank::handle($invite->inviter_user_id,$game->id) }}</div>
                            <div>{{__('words.Score: ')}} {{ $invite->inviterUser?->score_achievements_sum_count }}</div>
                        </div>

                        <div class="receive-info">
                            <div>
                                <div>
                                    @if($invite->gameType?->whereIn('name','in_club')->count())
                                        <i class="fas fa-check txt-green"></i>
                                    @else
                                        <i class="fas fa-times txt-red"></i>
                                    @endif
                                    <b>{{ __('words.In Club') }}</b>
                                    @if($invite?->club)
                                        {{ '('.$invite->club?->name.')' }}
                                    @endif
                                </div>
                                <div>
                                    @if($invite->gameType?->whereIn('name','with_image')->count())
                                        <i class="fas fa-check txt-green"></i>
                                    @else
                                        <i class="fas fa-times txt-red"></i>
                                    @endif
                                    <b>{{ __('words.With Referee') }}</b>
                                </div>
                            </div>
                            <div class="invite-dt">
                                         <span class="btn-dark btn-sm">
                                             <i class="fas fa-clock"></i>
                                             {{ $invite->created_at?->format("Y/m/d (H:i)") }}
                                         </span>
                            </div>

                            <div class="mb-3 txt-black mobile-number">
                                @if($invite->inviterUser?->profile?->show_mobile)
                                    <i class="fas fa-mobile-alt"></i>
                                    {{ $invite->inviterUser?->profile?->mobile }}
                                @endif
                            </div>

                            <div class="gamepage-buttons">
                                <a href="{{ route('games.page.accept',['inviteId'=>$invite->id]) }}"
                                   class="btn btn-success btn-sm">
                                    <i class="fa fa-check"></i> {{ __('words.Accept') }}</a>
                                <a href="{{ route('games.page.reject',['inviteId'=>$invite->id]) }}"
                                   class="btn btn-danger btn-sm">
                                    <i class="fa fa-ban"></i> {{ __('words.Reject') }}</a>
                                <a href="{{ route('chat.page',['user'=>$invite->inviter_user_id]) }}"
                                   class="btn btn-primary btn-sm ml-2" target="_blank"><i
                                        class="fa fa-comments"></i> {{ __('words.Chat') }}</a>
                            </div>
                        </div>

                    </li>
                @endforeach
            </ul>

        </div>
    </div>

</div>
