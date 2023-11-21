<li class="list-group-item">
    <div class="col-md offset-md">
        <form action="#">

            <div class="result-user-info" wire:ignore>
                <a href="{{ route('profile.show',['user'=>$user?->id]) }}" target="_blank"
                   title="{{$user?->username}}">
                    <img src="{{ $user?->avatar }}" class="user_photo mb-2" width="80">
                    <div>{{ $user?->username9 }}</div>
                </a>
                <div>{{__('words.Rank: ')}} {{ $user?->game_rank }}</div>
                <div>{{__('words.Score: ')}} {{ $user?->user_score_achievements_sum_count }}</div>
            </div>

            <div class="result-info">

                <div class="mb-2">

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
                                           wire:model="image"
                                           aria-describedby="inputGroupFileAddon01">
                                    <label class="custom-file-label" for="inputGroupFile01">
                                        {{$image?mb_substr($image?->getFilename(),0,20).'...':__('words.image')}}
                                    </label>
                                </div>
                                <div>
                                    @error('image') <span class="error  text-danger">{{ $message }}</span> @enderror
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


                <div class="btn-group btn-group-toggle mb-2">
                    <label
                        class="btn btn-secondary mr-1 mb-1 mt-2 btn-won {{$result===\App\Enums\StatusEnum::GAME_RESULT_WIN->value?'active':''}}"
                        wire:click="updateResult('{{ \App\Enums\StatusEnum::GAME_RESULT_WIN->value }}')"
                    > {{ __('words.I Won') }}
                    </label>
                    <label
                        class="btn btn-secondary mr-1 mb-1 mt-2 btn-lost {{$result===\App\Enums\StatusEnum::GAME_RESULT_LOSE->value?'active':''}}"
                        wire:click="updateResult('{{ \App\Enums\StatusEnum::GAME_RESULT_LOSE->value }}')">
                        {{ __('words.I Lost') }}
                    </label>
                    <br>
                    <label
                        class="btn btn-secondary mr-1 btn-sm btn-heAbsent {{$result===\App\Enums\StatusEnum::GAME_RESULT_HE_ABSENT->value?'active':''}}"
                        wire:click="updateResult('{{ \App\Enums\StatusEnum::GAME_RESULT_HE_ABSENT->value }}')">
                        {{ __('words.Opponent was absent / left the playing') }}
                    </label>
                    <label
                        class="btn btn-secondary btn-sm btn-iAbsent {{$result===\App\Enums\StatusEnum::GAME_RESULT_I_ABSENT->value?'active':''}}"
                        wire:click="updateResult('{{ \App\Enums\StatusEnum::GAME_RESULT_I_ABSENT->value }}')">
                        {{ __('words.I was absent / left the playing') }}
                    </label>

                    <div>
                        @error('result') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                @php
                    $status = \App\Enums\StatusEnum::SUBMIT_RESULT->value;
                  }
                  else{
                    $status = \App\Enums\StatusEnum::WAIT_OPPONENT_RESULT->value;
                  }
              }
                @endphp


                <div class="form-group row mt-4">
                    @if( !$activeResult )
                        <div class="col-md-3.5 offset-md-2 result-btn-box">
                            <button wire:click="submitResult({{$inviteGameResult->id}})"
                                    class="btn btn-danger btn-submit-result" type="button">
                                {{ __('words.Submit') }}
                            </button>
                        </div>
                    @endif

                    <span class="result_status">
                              @if($inviteGameResult->confirmStatus->name == \App\Enums\StatusEnum::FINISHED->value)
                            <b>{{ __('words.Final Result:') }}</b>
                        @else
                            <b>{{ __('words.Status:') }}</b>
                        @endif
                        {{\App\Enums\StatusEnum::tryFrom($status)?->getLabel() }}
                            </span>
                </div>

            </div>

        </form>
    </div>
</li>
