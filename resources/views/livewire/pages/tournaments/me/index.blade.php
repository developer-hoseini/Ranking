<div>
    <div class="mt-4 text-center">
        <h1 style="font-size: 30px;">{{ __('words.my_tournament') }}</h1>
    </div>
    <div class="container mt-3 rounded bg-white p-2 shadow">

        <div class="w-100 text-sm-center">
            <a
                class="btn btn-success cursor-pointer text-white"
                href="{{ route('tournaments.me.create') }}"
            >
                {{ __('words.create_new_tournament') }}
                <i class="fa fa-plus mx-2"></i>
            </a>
            <button
                class="btn btn-info tournament-btns m-1"
                {{-- onclick="invite_show();" --}}
            >
                {{ __('words.invites') }}
                <i class="fa fa-envelope-open-text mx-2"></i>
                {{-- @if ($invites->count() != 0)
                    <div
                        class="invite-status text-info rounded-circle d-inline-block bg-white"
                        style="width: 18px;height: 18px;line-height: 18px;"
                    >{{ $invites->count() }}</div>
                @endif --}}
            </button>
            <a
                class="btn btn-info tournament-btns m-1"
                {{-- href="{{ route('certificates') }}" --}}
            >{{ __('words.certificate') }}<img
                    class="mx-1"
                    src="{{ url('img/certificate-icon.png') }}"
                    width="17px"
                ></a>
        </div>

        <div class="w-100 d-flex mb-2 mt-5 flex-wrap">
            <button
                class="btn btn-outline-info {{ $type == 'participated' ? 'btn-info text-white' : '' }} px-lg-5 px-md-3 px-2 py-2"
                wire:click="$set('type','participated')"
            >
                {{ __('words.participated_in_tournaments') }}
            </button>
            <button
                class="btn btn-outline-info {{ $type == 'created' ? 'btn-info text-white' : '' }} px-lg-5 px-md-3 mx-1 px-2 py-2"
                wire:click="$set('type','created')"
            >
                {{ __('words.tournaments_created') }}
            </button>
        </div>

        <div
            class="w-100 mb-5"
            style="overflow-x: auto;overflow-y: hidden;"
        >
            @if ($type == 'participated')
                <livewire:pages.tournaments.me.index.participated />
            @endif

            @if ($type == 'created')
                <livewire:pages.tournaments.me.index.created />
            @endif

        </div>

    </div>

</div>

@push('links')
    <style type="text/css">
        @media screen and (max-width: 438px) {
            #new_tournament_btn {
                width: 90%;
            }

            .tournament-btns {
                width: 43%;
            }
        }

        @media screen and (max-width: 740px) {
            .tournament-tabs {
                display: none !important;
            }

            .tournament-sm-tab {
                display: block !important;
            }
        }

        .invite-swal-box {
            max-height: 150px;
            overflow-y: auto;
        }

        .invite-swal-div {
            width: 100%;
            border-bottom: solid 1px #999;
            padding: 0 5px;
        }

        .invite-swal-div:hover {
            background-color: #f5f5f5;
        }

        .invite-confrim-btn {
            margin: 0 5px;
            font-size: 16px;
            color: #3d7b00;
        }

        .invite-confrim-btn:hover {
            color: #499800;
        }

        .invite-cancel-btn {
            margin: 0 5px;
            font-size: 16px;
            color: #f00;
        }

        .invite-cancel-btn:hover {
            color: #a6000c;
        }

        .up_input {
            width: 100%;
            background-color: #fff;
            box-shadow: 0 0 2px #999;
            padding: 10px;
            margin-top: 5px;
            font-size: 16px;
            border: none;
            border-radius: 2px;
        }

        .up_submit {
            width: 100%;
            background-color: #149f00;
            color: #fff;
            border: none;
            font-size: 16px;
            font-weight: bold;
            padding: 10px;
            margin-top: 5px;
            border-radius: 2px;
        }

        .up_submit:hover {
            background-color: #147600;
        }
    </style>
@endpush
