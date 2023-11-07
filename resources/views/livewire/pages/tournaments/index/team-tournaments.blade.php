 <!---------- Team Tournaments ---------->
 <div class="rounded-2 mt-3 border bg-white p-2">
     <div class="border-bottom d-flex flex-wrap px-2 py-1">
         <div class="w-50 d-flex flex-wrap">
             {{-- Todo: it doesnt work in orginal --}}
             <a
                 class="text-ranking"
                 title="{{ __('words.team_tournaments') }}"
                 {{-- href="{{ route('tournament.search') }}?type=team" --}}
             >
                 <h2 class="text-ranking">{{ __('words.team_tournaments') }}</h2>
             </a>
         </div>

         <div class="w-50 d-flex more-tour-link flex-wrap">
             {{-- Todo: it doesnt work in orginal --}}
             <a
                 class="text-dark"
                 title="{{ __('words.team_tournaments') }}"
                 {{-- href="{{ route('tournament.search') }}?type=team" --}}
             >
                 {{-- {{ __('words.see_all') }} --}}
             </a>
         </div>
     </div>

     <div class="swiper-container team-tour-slider px-2 py-3">
         <div class="swiper-wrapper">

             @foreach ($this->cups as $cup)
                 <div class="swiper-slide tour-slide">
                     <a
                         class="text-decoration-none"
                         title="{{ $cup?->game?->name }}"
                         style="color: #6f6f6f;"
                         {{-- href="{{ route('tournament.show', ['tour_id' => $competition?->id, 'tour_name' => $competition?->name]) }}" --}}
                     >
                         <div class="rounded-2 tour-card bg-white pb-3 shadow">
                             <img
                                 class="tour-card-img"
                                 src="{{ $cup?->game?->cover }}"
                                 alt="{{ $cup?->game?->name }}"
                             >
                             <div
                                 class="position-relative d-flex flex-wrap"
                                 style="bottom: 10px;"
                             >
                                 <div class="rounded-pill text-truncate mx-auto bg-white px-2 py-1 text-center">
                                     <img
                                         class="rounded-circle mx-1"
                                         src="{{ $cup?->game?->icon }}"
                                         title="{{ $cup?->game?->name }}"
                                         alt="{{ $cup?->game?->name }}"
                                         width="20px"
                                         height="20px"
                                     >
                                     <span>{{ $cup?->game?->name }}</span>
                                 </div>
                             </div>
                             <div class="text-dark text-truncate text-center">
                                 <span>{{ $cup?->name }}</span>
                                 @if ($cup?->isFinished)
                                     <i
                                         class="fa fa-flag-checkered mx-1"
                                         title="{{ __('words.finished') }}"
                                         style="font-size: 14px;"
                                     ></i>
                                 @endif
                             </div>
                             <div
                                 class="d-flex mt-1 flex-wrap"
                                 style="font-size: 14px;"
                             >
                                 <div class="w-50 text-truncate text-center">
                                     <img
                                         class="mx-1"
                                         src="{{ $cup?->cupCompetitionsState?->country?->icon }}"
                                         height="20px"
                                     >
                                     <span>{{ $cup?->cupCompetitionsState?->name }}</span>
                                 </div>
                                 <div class="w-50 text-center">
                                     {{ trans_choice('words.tour_members', $cup?->registered_teams_count, ['member' => $cup?->registered_teams_count]) }}
                                 </div>
                             </div>
                         </div>
                     </a>
                 </div>
             @endforeach
         </div>
     </div>
 </div>
