<div class="my_teams">
    {{-- @foreach ($my_teams as $team)
        <div class="team">
            <div class="team_name text-center">
                <a
                    href="{{ route('team_profile', ['teamname' => $team->name]) }}"
                    target="_blank"
                >
                    <img
                        class="user_photo"
                        src="{{ $team->photo }}"
                        width="100"
                    >
                    <div>{{ $team->name }}</div>
                </a>
                <div><span>({{ __('games.' . $team->game->name) }})</span></div>
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
                            <td>{{ $team->score->score }}</td>
                            <td>{{ $team->score->rank }}</td>
                            <td>{{ $team->members_count + 1 }}</td>
                        </tr>
                    </tbody>
                </table>

                <div class="team_buttons">
                    @if ($team->members_count > 0)
                        <div class="mb-3">
                            <a href="{{ route('team_page', ['team_id' => $team->id]) }}">
                                <i class="fa fa-handshake"></i> {{ __('words.Invitation_to_Compete') }}</a>
                        </div>
                    @endif

                    <div class="mb-3">
                        <a href="{{ route('team_members', ['team_id' => $team->id]) }}">
                            <i class="fa fa fa-users"></i> {{ __('words.Members List') }}</a>
                    </div>

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
                    </div>
                </div>
            </div>
        </div>
    @endforeach --}}

</div>
