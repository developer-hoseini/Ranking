<div class="w-100 mt-2 py-1">
    <div class="text-center">
        <h2
            class="pt-3"
            style="font-size: 28px !important;"
        >{{ __('words.tournaments_gallery') }}</h2>
    </div>

    <div class="swiper-container gallery-slider container px-3 py-3">
        <div class="swiper-wrapper mb-3">
            @foreach ($this?->competitions as $competition)
                <div>
                    {{ $competition->name }}
                    {{ $competition->id }}
                </div>
                <div class="swiper-slide tour-slide">

                    {{-- TODO: add route --}}
                    <a {{-- href="{{ route('tournament.show', ['tour_id' => $competitions?->competition_id, 'tour_name' => $competitions?->competition?->name]) }}" --}}>
                        <img
                            class="object-fit-contain rounded border"
                            src="{{ $competition?->image }}"
                            title="{{ $competition?->name }}"
                            alt="{{ $competition?->name }}"
                            width="200px"
                            height="200px"
                        >
                    </a>
                </div>
            @endforeach
        </div>
        <!-- Add Pagination -->
        <div class="swiper-pagination gallery-pagination"></div>
    </div>
</div>
