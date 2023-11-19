<div class="add_member">
    <form
        {{-- method="post" --}}
        {{-- action="{{ route('add_member') }}" --}}
        wire:submit.prevent='formSubmit'
    >

        <div class="form-group row justify-content-center">

            <div
                class="col-md-7"
                wire:ignore.self
                x-data="{ show: {{ $searchUser != '' ? 'true' : 'false' }} }"
            >

                <input
                    class="form-control @error('form.user_id') is-invalid  @enderror username_search_box"
                    type="text"
                    placeholder="search user ..."
                    wire:model.live.debounce.250ms='searchUser'
                    x-on:input="show=true"
                    x-trap="true"
                >
                @error('form.user_id')
                    <div class="text-danger">
                        <span>{{ $message }}</span>
                    </div>
                @enderror

                <ul
                    class="position-absolute w-100 form-select overflow-auto border"
                    style="z-index: 10; background: white; max-height: 150px; max-width: 92%; padding-top: 10px; padding-bottom: 10px;
                                    "
                    x-show="show"
                >
                    <li wire:loading>
                        loading...
                    </li>
                    @forelse($users as $key => $user)
                        @php
                            $avatarName = isset($user['profile']['avatar_name']) ? $user['profile']['avatar_name'] : '';
                        @endphp

                        <li
                            style="cursor: pointer"
                            wire:loading.remove
                            x-on:click="show=false;$dispatch('user-selected',{userId:'{{ $user['id'] }}',avatarName:'{{ $avatarName }}'})"
                        >
                            {{ $avatarName }}
                        </li>
                        <br>
                    @empty
                        <li wire:loading.remove>not found.</li>
                    @endforelse
                </ul>

            </div>

        </div>

        <div class="form-group row justify-content-center mb-5">
            <div class="col-md-6 offset-md-4">
                <button class="btn btn-danger">
                    {{ __('words.invite to team') }}
                </button>
            </div>
        </div>

        <div
            class="userinfo-part"
            {{-- v-show="user_selected" --}}
        >
            <img
                class="user_photo userinfo_img"
                width="130"
            >
            <div class="userinfo_info">
                <div>
                    <a
                        {{-- :href="profileLink + '/' + selected_username" --}}
                        target="_blank"
                        {{-- :title="selected_username" --}}
                    >
                        {{-- {{ selected_username9 }} --}}
                    </a>
                </div>

            </div>
        </div>

    </form>
</div>
