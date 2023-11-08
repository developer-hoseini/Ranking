@props([
    'game' => collect([]),
    'href' => '',
    'view' => '',
])

<a
    class="text-decoration-none"
    href="{{ $href }}"
>
    <div class="rounded-2 ongames-card bg-white pb-3 shadow">
        <img
            class="tour-card-img"
            src="{{ $game->cover }}"
            alt="{{ $game->name }}"
        >
        <div
            class="position-relative d-flex flex-wrap"
            style="bottom: 10px;"
        >
            <div class="rounded-pill mx-auto bg-white px-2 py-1 text-center">
                <img
                    class="rounded-circle mx-1"
                    src="{{ $game->icon }}"
                    title="{{ $game->name }}"
                    alt="{{ $game->name }}"
                    width="20px"
                    height="20px"
                >
                {{ $game->name }}
            </div>
        </div>
        <span
            class="mt-5"
            title="{{ __('words.viewed_count') }}"
            style="color: green;"
        >
            <i class="fa fa-eye"></i>
            {{ $view }}
        </span>
    </div>
</a>
