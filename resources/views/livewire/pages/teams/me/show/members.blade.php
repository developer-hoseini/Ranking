<div class="gamepage_center_box">

    <div id="app">

        <div class="card invite_box">
            <div class="card-header text-center">
                <h5>{{ __('words.Add Member') }}</h5>
                <a
                    class="card-back text-left"
                    href="{{ route('teams.me.index') }}"
                    style="color:#000;"
                >{{ __('words.back') }}</a>
            </div>
            <div class="card-body">
                <livewire:pages.teams.me.show.add-member team-id="{{ $this->team->id }}" />

                @if (count($this->team->users) > 0)
                    <div class="members_list">
                        <h4 class="text-center">{{ __('words.Members List') }}</h4>

                        <table class="table-striped table">
                            <tbody>
                                <tr>
                                    <td class="align-middle">
                                        {{-- <b>{{ $cnt++ }}</b> --}}
                                    </td>

                                    <td>
                                        <a
                                            @if ($this->team->capitan) href="{{ route('profile.show', $this->team->capitan?->id) }}" @endif
                                            target="_blank"
                                        >
                                            <img
                                                class="user_photo"
                                                src="{{ $this->team->capitan?->avatar }}"
                                                width="60"
                                            >
                                            <div>{{ $this->team->capitan?->profile?->avatar_name }}</div>
                                        </a>
                                    </td>

                                    <td class="align-middle">
                                        {{ __('words.capitan') }}
                                    </td>

                                    <td class="align-middle">
                                    </td>
                                </tr>

                                @foreach ($this->team->users as $user)
                                    @if ($user->id == $this->team?->capitan?->id)
                                        @continue;
                                    @endif
                                    <tr>
                                        <td class="align-middle">
                                            {{ $loop->index + 1 }}
                                        </td>

                                        <td>
                                            <a
                                                href="{{ route('profile.show', $user->id) }}"
                                                target="_blank"
                                            >
                                                <img
                                                    class="user_photo"
                                                    src="{{ $user->avatar }}"
                                                    width="60"
                                                >
                                                <div>{{ $user->profile?->avatar_name }}</div>
                                            </a>
                                        </td>

                                        <td class="align-middle">
                                            {{ $user->status_msg }}
                                        </td>

                                        <td class="align-middle">
                                            {{-- <delete-user
                                                delete-route="{{ route('delete_user') }}"
                                                user-id="{{ $user->user_id }}"
                                                team-id="{{ $user->team_id }}"
                                                msg-delete-title="{{ __('words.remove_from_team') }}"
                                                msg-will-delete="{{ __('words.remove_player_from_team') }}"
                                                lbl-yes-delete="{{ __('words.delete_it') }}"
                                                lbl-cancel="{{ __('words.Cancel') }}"
                                            ></delete-user> --}}
                                        </td>
                                    </tr>
                                @endforeach
                                @foreach ($this->team->teamInvites as $inviteTable)
                                    @php
                                        $user = $inviteTable?->invitedUser;
                                    @endphp

                                    <tr>
                                        <td class="align-middle">
                                            {{ $loop->index + 1 }}
                                        </td>

                                        <td>
                                            <a
                                                href="{{ route('profile.show', $user->id) }}"
                                                target="_blank"
                                            >
                                                <img
                                                    class="user_photo"
                                                    src="{{ $user->avatar }}"
                                                    width="60"
                                                >
                                                <div>{{ $user->profile?->avatar_name }}</div>
                                            </a>
                                        </td>

                                        <td class="{{ $inviteTable->confirmStatus?->colorClass }} align-middle">
                                            {{ $inviteTable->confirmStatus?->name }} (invited)
                                        </td>

                                        <td class="align-middle">
                                            {{-- <delete-user
                                                delete-route="{{ route('delete_user') }}"
                                                user-id="{{ $user->user_id }}"
                                                team-id="{{ $user->team_id }}"
                                                msg-delete-title="{{ __('words.remove_from_team') }}"
                                                msg-will-delete="{{ __('words.remove_player_from_team') }}"
                                                lbl-yes-delete="{{ __('words.delete_it') }}"
                                                lbl-cancel="{{ __('words.Cancel') }}"
                                            ></delete-user> --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

            </div>
        </div>

    </div>
</div>
