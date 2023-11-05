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

              @foreach ($this?->competitions as $competition)
                  <div class="swiper-slide tour-slide">
                      {{-- TODO: add tournoment show page and fix this route --}}
                      <a
                          class="text-decoration-none"
                          title="{{ $competition?->name }}"
                          style="color: #6f6f6f;"
                          {{-- href="{{ route('tournament.show', ['tour_id' => $competition?->id, 'tour_name' => $competition?->name]) }}" --}}
                      >
                          <div class="rounded-2 tour-card bg-white pb-3 shadow">
                              <img
                                  class="tour-card-img"
                                  src="{{ $competition?->game?->cover }}"
                                  alt="{{ $competition?->game?->name }}"
                              >
                              <div
                                  class="position-relative d-flex flex-wrap"
                                  style="bottom: 10px;"
                              >
                                  <div class="rounded-pill text-truncate mx-auto bg-white px-2 py-1 text-center">
                                      <img
                                          class="rounded-circle mx-1"
                                          src="{{ $competition?->game?->icon }}"
                                          title="{{ $competition?->game?->name }}"
                                          alt="{{ $competition?->game?->name }}"
                                          width="20px"
                                          height="20px"
                                      >
                                      <span>{{ $competition?->game?->name }}</span>
                                  </div>
                              </div>
                              <div class="text-dark text-truncate text-center">
                                  {{ mb_substr($competition?->name, 0, 21, 'UTF-8') }}
                                  @if ($competition?->gameResults?->count() > 0)
                                      @if ($competition?->gameResults?->last()?->stats?->isAccepted)
                                          <i
                                              class="fa fa-flag-checkered mx-1"
                                              title="{{ __('words.finished') }}"
                                              style="font-size: 14px;"
                                          ></i>
                                      @endif
                                  @endif
                              </div>
                              <div
                                  class="d-flex mt-1 flex-wrap"
                                  style="font-size: 14px;"
                              >
                                  <div class="w-50 text-truncate text-center">
                                      <img
                                          class="mx-1"
                                          src="{{ $competition?->state?->country?->icon }}"
                                          height="20px"
                                      >
                                      @if (\Lang::has('state.' . $competition?->state?->name))
                                          {{ mb_substr(__('state.' . $competition?->state?->name), 0, 10, 'UTF-8') }}
                                      @else
                                          {{ mb_substr($competition?->state?->name, 0, 8, 'UTF-8') }}
                                      @endif
                                  </div>
                                  <div class="w-50 text-center">
                                      {{ trans_choice('words.tour_members', $competition?->users?->count(), ['member' => $competition?->users?->count()]) }}
                                  </div>
                              </div>
                          </div>
                      </a>
                  </div>
              @endforeach
          </div>
      </div>
  </div>
