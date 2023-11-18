@extends('layouts.app')

@section('title', __('words. - ') . __('games.' . $game->name) . ' (' . __('words.double') . ')')

@section('header')
    @parent
@endsection

@section('content')

    <div class="card ranks_content">
        <div class="card-header gameinfo text-center">
            <h1>{{ $game->name }}</h1>
            <h6>{{ __('words.Total Members: ') . $users->total() }}</h6>
            <h6>{{ __('words.Total Played: ') . $game->invites_count }}</h6>
        </div>
        <div class="card-body">

            <section class="ranks_table">
                <div class="card text-center">
                    <div class="card-body">

                        <table class="table-striped table">
                            <thead>
                                <tr>
                                    <th scope="col"><i class="fas fa-hashtag"></i></th>
                                    <th
                                        class="photo-name"
                                        scope="col"
                                    >{{ __('words.First Name') }}</th>
                                    <th scope="col">{{ __('words.Score') }}</th>
                                    <th
                                        class="text-center"
                                        scope="col"
                                    ><img
                                            src="{{ asset('assets/img/coin.png') }}"
                                            width="16"
                                        ></th>
                                    <th scope="col"><i class="fa fa-heart liked-color no-pointer"></i></th>
                                    @auth
                                        <th scope="col"></th>
                                    @endauth
                                </tr>
                            </thead>

                            @foreach ($users as $row => $user)
                                <tr @if ($user->id == auth()->user()?->id) class="auth_selected" @endif>
                                    <td>{{ $row + 1 }}</td>

                                    <td class="photo-name">
                                        <a
                                            href="{{ route('profile.show', ['user' => $user->id]) }}"
                                            title="{{ $user->profile?->fullname ?? $user->name }}"
                                        >
                                            <img
                                                class="user_photo"
                                                src="{{ $user->avatar }}"
                                                alt="{{ $user->profile?->fullname }}"
                                                width="40"
                                            >
                                            <span>{{ $user->profile?->fullname ?? $user->name }}</span>
                                        </a>
                                    </td>

                                    <td>{{ $user->user_score_achievements_sum_count }}</td>

                                    <td class="text-center">{{ $user->user_coin_achievements_sum_count }}</td>

                                    <td class="text-center">{{ $user->likes_count }}</td>

                                    @auth
                                        <td>
                                            @if ($user->id != auth()->user()?->id)
                                                <a
                                                    class="invitation"
                                                    href="{{ route('games.page.index', ['game' => $game->id, 'opponent' => $user->id]) }}"
                                                >{{ __('words.invitation') }}</a>
                                            @endif
                                        </td>
                                    @endauth
                                </tr>
                            @endforeach
                        </table>

                    </div>
                </div>
            </section>

            <div class="pagination-links">
                {{ $users->links() }}
            </div>

        </div>
    </div>

@endsection
