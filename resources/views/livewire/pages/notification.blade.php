<div class="card events_content">

    <div class="card-body">

        <h2 class="txt-red text-center">{{ __('words.Notifications') }}</h2>

        <section class="cd-timeline js-cd-timeline">
            <div class="container--lg cd-timeline__container container">
                @foreach ($this->notifications as $notification)
                    <div class="cd-timeline__block">
                        <div class="cd-timeline__img cd-timeline__img--picture">
                            <img src="{{ asset('assets/img/event/Info.png') }}">
                        </div>
                        <div class="cd-timeline__content text-component">
                            <h6>{{ $notification->data['message'] ?? '' }}</h6>
                            <p class="text--subtle"></p>

                            <div class="flex--space-between flex--center-y flex">
                                <span class="cd-timeline__date">
                                    {{ $notification->created_at }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </section>

        <div class="pagination-links">
            {{ $this->notifications->links() }}
        </div>

    </div>
</div>

@push('styles')
    <script
        src="{{ asset('assets/js/timeline.js') }}"
        defer
    ></script>
    <link
        href="{{ asset('assets/css/timeline.css') }}"
        rel="stylesheet"
    >

    <style>
        .cd-timeline__content {
            box-shadow: 0px 0px 16px hsl(0, 0%, 57%);
            border-radius: 15px;
            color: #555;
        }

        .cd-timeline {
            background-color: #f2f2f2;
            border-radius: 10px;
            clear: both;
        }

        .cd-timeline__img--picture {
            background-color: #fff;
        }

        .cd-timeline__img img {
            width: 100%;
            height: 100%;
        }

        .cd-timeline__date {
            color: #000;
        }
    </style>
@endpush
