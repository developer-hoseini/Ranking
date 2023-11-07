  <!---------- Solo Tournaments ---------->
  <div class="rounded-2 border bg-white p-2">
      <div class="border-bottom d-flex flex-wrap px-2 py-1">
          <div class="w-50 d-flex flex-wrap">
              {{-- Todo: it doesnt work in orginal --}}
              <a
                  class="text-ranking"
                  title="{{ __('words.solo_tournaments') }}"
                  {{-- href="{{ route('tournament.search') }}?type=solo" --}}
              >
                  <h2 class="text-ranking">
                      {{ __('words.solo_tournaments') }}
                  </h2>
              </a>
          </div>
          <div class="w-50 d-flex more-tour-link flex-wrap">
              {{-- Todo: it doesnt work in orginal --}}
              <a
                  class="text-dark"
                  title="{{ __('words.solo_tournaments') }}"
                  {{-- href="{{ route('tournament.search') }}?type=solo" --}}
              >
                  {{-- {{ __('words.see_all') }} --}}
              </a>
          </div>
      </div>
      <div class="swiper-container solo-tour-slider px-2 py-3">
          <div class="swiper-wrapper">

              @foreach ($this?->cups as $cup)
                  @php
                      $game = $cup->competitions?->first()?->game;
                  @endphp
                  <div class="swiper-slide tour-slide">
                      {{-- TODO: add tournoment show page and fix this route --}}
                      <a
                          class="text-decoration-none"
                          href="{{ route('tournaments.show', $cup->id) }}"
                          title="{{ $cup->name }}"
                          style="color: #6f6f6f;"
                      >
                          <div class="rounded-2 tour-card bg-white pb-3 shadow">
                              <img
                                  class="tour-card-img"
                                  src="{{ $cup->game?->cover }}"
                                  alt="{{ $cup->game?->name }}"
                              >
                              <div
                                  class="position-relative d-flex flex-wrap"
                                  style="bottom: 10px;"
                              >
                                  <div class="rounded-pill text-truncate mx-auto bg-white px-2 py-1 text-center">
                                      <img
                                          class="rounded-circle mx-1"
                                          src="{{ $cup->game?->icon }}"
                                          title="{{ $cup->game?->name }}"
                                          alt="{{ $cup->game?->name }}"
                                          width="20px"
                                          height="20px"
                                      >
                                      <span>{{ $cup->game?->name }}</span>
                                  </div>
                              </div>
                              <div class="text-dark text-truncate text-center">
                                  {{ $cup->name }}
                                  @if ($cup->isFinished)
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
                                          src="{{ $cup->cupCompetitionsState?->country?->icon }}"
                                          height="20px"
                                      >
                                      {{ $cup->cupCompetitionsState?->name }}
                                  </div>
                                  <div class="w-50 text-center">
                                      {{ trans_choice('words.tour_members', $cup->registered_users_count, ['member' => $cup->registered_users_count]) }}
                                  </div>
                              </div>
                          </div>
                      </a>
                  </div>
              @endforeach
          </div>
      </div>
  </div>
