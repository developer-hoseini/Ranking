<div class="my_teams">
    @foreach ($this->teams as $team)
        <div class="team d-flex">
            <div class="team_name text-center">
                {{-- TODO: add route show team --}}
                <a
                    {{-- href="{{ route('team_profile', ['teamname' => $team->name]) }}" --}}
                    target="_blank"
                >
                    <img
                        class="user_photo"
                        src="{{ $team->avatar }}"
                        width="100"
                    >
                    <div>{{ $team->name }}</div>
                </a>
                <div>
                    <span>
                        ({{ $team?->game?->name }})
                    </span>
                </div>
            </div>

            <div class="team_details">
                <table class="table">
                    <tbody>
                        <tr>
                            <th>{{ __('words.Score') }}</th>
                            <th>{{ __('words.Rank') }}</th>
                            <th>{{ __('words.Member_Count') }}</th>
                        </tr>
                        <tr>
                            <td>
                                {{ $team?->score_achievements_sum_count }}
                            </td>
                            <td>
                                {{ $team?->team_rank }}
                            </td>
                            <td>
                                {{ $team?->users_count }}
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="team_buttons">
                    @if ($team->users_count > 0)
                        <div class="mb-3">
                            <a href="{{ route('teams.show', $team->id) }}">
                                <i class="fa fa-handshake"></i> {{ __('words.Invitation_to_Compete') }}</a>
                        </div>
                    @endif
                    <div class="mb-3">
                        <a href="{{ route('teams.me.show.memebers', $team->id) }}">
                            <i class="fa fa fa-users"></i> {{ __('words.Members List') }}</a>
                    </div>
                    {{-- 

                    <div class="mb-3">
                        <a href="{{ route('team_rank_position', ['team_id' => $team->id]) }}">
                            <i class="fa fa-trophy"></i> {{ __('words.team_position') }}</a>
                    </div>

                    <div class="mb-3">
                        <a href="{{ route('team_page', ['team_id' => $team->id]) . '#Info' }}">
                            <i class="fa fa-info-circle"></i> {{ __('words.team_info') }}</a>
                    </div>

                    <div>
                        <a href="{{ route('edit_team', ['team_id' => $team->id]) }}">
                            <i class="fa fa-edit"></i> {{ __('words.Edit Team') }}</a>
                    </div> --}}
                </div>
            </div>
        </div>
    @endforeach

</div>
