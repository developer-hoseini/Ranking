<div id="app2" class="card result_box" x-init="setInterval(function(){@this.dispatchSelf('reloadGameResults');},10000)">
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

        <ul class="list-group">
            @foreach($inviteGameResults as $inviteGameResult)
                <li class="list-group-item">
                    <div class="col-md offset-md">
                        <form action="{{ route('games.page.submit-result',['invite'=>$inviteGameResult->id]) }}"
                              method="post" enctype="multipart/form-data">

                            {{ csrf_field() }}

                            <div class="result-user-info">
                                @php
                                    if( $inviteGameResult->inviter_user_id == auth()->id() )
                                      $user = $inviteGameResult->invitedUser;
                                    else
                                      $user = $inviteGameResult->inviterUser;
                                @endphp
                                <a href="{{ route('profile.show',['user'=>$user?->id]) }}" target="_blank"
                                   title="{{$user?->username}}">
                                    <img src="{{ $user?->avatar }}" class="user_photo mb-2" width="80">
                                    <div>{{ $user?->username9 }}</div>
                                </a>
                                <div>{{__('words.Rank: ')}} {{ \App\Services\Actions\User\GetGameRank::handle($user?->id,$game->id) }}</div>
                                <div>{{__('words.Score: ')}} {{ $user?->user_score_achievements_sum_count }}</div>
                            </div>

                            <div class="result-info">

                                <div class="mb-2">
                                    @php
                                        $inClub = $inviteGameResult->gameType?->whereIn('name','in_club')->count();
                                        $withImage =$inviteGameResult->gameType?->whereIn('name','with_image')->count();
                                        $activeResult = $inviteGameResult->inviteCompetitionsScoreOccurredModel->count()>=2;
                                    @endphp

                                    <div>
                                        @if($inClub)
                                            <i class="fas fa-check txt-green"></i>
                                        @else
                                            <i class="fas fa-times txt-red"></i>
                                        @endif
                                        <b>{{ __('words.In Club') }}</b>
                                        @if($inClub)
                                            {{ '('.$inviteGameResult?->club?->name.')' }}
                                        @endif
                                    </div>
                                    <div>
                                        @if($withImage)
                                            <i class="fas fa-check txt-green"></i>
                                        @else
                                            <i class="fas fa-times txt-red"></i>
                                        @endif
                                        <b>{{ __('words.With Referee') }}</b>
                                    </div>


                                    @if($inviteGameResult->competitions->first()?->end_register_at)
                                        <div>
                                            <i class="fas fa-calendar-check"></i> {{ $inviteGameResult->competitions->first()->end_register_at->format("Y/m/d (H:i)") }}
                                        </div>
                                    @endif



                                    @if( !$activeResult && $withImage )
                                        <div class="upload-image mt-2" style="width: 89%;">
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                      <span class="input-group-text" id="inputGroupFileAddon01">
                                                        <i class="fas fa-image"></i>
                                                      </span>
                                                </div>
                                                <div class="custom-file">
                                                    <input type="file" name="image" accept="image/*"
                                                           class="custom-file-input" id="inputGroupFile01"
                                                           aria-describedby="inputGroupFileAddon01"
                                                           @change="image_name_changed">
                                                    <input type="hidden" name="with_referee" value="1">
                                                    <label class="custom-file-label" for="inputGroupFile01">@{{image_file_name
                                                        }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    @endif


                                </div>

                                @php
                                    if( $inviteGameResult->confirmStatus->name == \App\Enums\StatusEnum::ACCEPTED->value )
                                    {
                                      if( !$activeResult )
                                      {
                                @endphp


                                <div class="btn-group btn-group-toggle mb-2" data-toggle="buttons">
                                    <label class="btn btn-secondary mr-1 mb-1 mt-2 btn-won">
                                        <input type="radio" name="result"
                                               value="{{ config('result.Win') }}"> {{ __('words.I Won') }}
                                    </label>
                                    <label class="btn btn-secondary mr-1 mb-1 mt-2 btn-lost">
                                        <input type="radio" name="result"
                                               value="{{ config('result.Lose') }}"> {{ __('words.I Lost') }}
                                    </label>
                                    <br>
                                    <label class="btn btn-secondary mr-1 btn-sm btn-heAbsent">
                                        <input type="radio" name="result"
                                               value="{{ config('result.He_Absent') }}"> {{ __('words.Opponent was absent / left the playing') }}
                                    </label>
                                    <label class="btn btn-secondary btn-sm btn-iAbsent">
                                        <input type="radio" name="result"
                                               value="{{ config('result.I_Absent') }}"> {{ __('words.I was absent / left the playing') }}
                                    </label>
                                </div>

                                @php
                                    $inviteGameResult->confirmStatus->name = \App\Enums\StatusEnum::SUBMIT_RESULT;
                                  }
                                  else{
                                    $inviteGameResult->confirmStatus->name = \App\Enums\StatusEnum::WAIT_OPPONENT_RESULT;
                                  }
                              }
                                @endphp


                                <div class="form-group row mt-4">
                                    @if( !$activeResult )
                                        <div class="col-md-3.5 offset-md-2 result-btn-box">
                                            <button class="btn btn-danger btn-submit-result">
                                                {{ __('words.Submit') }}
                                            </button>
                                        </div>
                                    @endif

                                    <span class="result_status">
                              @if($inviteGameResult->confirmStatus->name == \App\Enums\StatusEnum::FINISHED)
                                            <b>{{ __('words.Final Result:') }}</b>
                                        @else
                                            <b>{{ __('words.Status:') }}</b>
                                        @endif
                                        {{--                                        TODO Refactor--}}
                                        {{$inviteGameResult->confirmStatus?->message }}
                            </span>
                                </div>

                            </div>

                        </form>
                    </div>
                </li>
            @endforeach
        </ul>

    </div>
</div>
