<!-- MultiStep Form -->

@php
    $authUser = auth()->user();
@endphp

<div
    class="container-fluid"
    id="grad1"
>
    <div class="row justify-content-center mt-0">
        <div class="col-11 col-sm-9 col-md-7 col-lg-6 mb-2 mt-3 p-0 text-center">
            <div class="card mb-3 mt-3 px-0 pb-0 pt-4">
                @if ($authUser->isProfileCompleted)
                    <h2><strong>Edit Your Profile</strong></h2>
                @else
                    <h2><strong>Please Complete Your Profile</strong></h2>
                    <p>
                        Fill all form field to get
                        <span class="font-weight-bold">
                            {{ config('ranking.achievements.completeProfile.coin') }}
                            MGC Coin
                        </span>
                    </p>
                @endif

                <div class="row">
                    <div class="col-md-12 mx-0">
                        <form id="complete-profile-form">
                            <!-- progressbar -->
                            <ul id="progressbar">
                                <li
                                    class="{{ $step == 'account' ? 'active' : '' }}"
                                    id="account"
                                    style="cursor: pointer"
                                    wire:click="$set('step','account')"
                                >
                                    <strong>Account</strong>
                                </li>
                                <li
                                    class="{{ $step == 'location' ? 'active' : '' }}"
                                    id="location"
                                    style="cursor: pointer"
                                    wire:click="$set('step','location')"
                                >
                                    <strong>Location</strong>
                                </li>
                                <li
                                    class="{{ $step == 'complete' ? 'active' : '' }}"
                                    id="confirm"
                                >
                                    <strong>Finish</strong>
                                </li>
                            </ul>
                            <!-- fieldsets -->
                            @if ($step == 'account')
                                <fieldset>
                                    <div class="form-card">
                                        <h2 class="fs-title">Account Information</h2>
                                        <input
                                            class="@error('accountInformation.fname') is-invalid @else is-valid @enderror"
                                            type="text"
                                            wire:model="accountInformation.fname"
                                            placeholder="First Name"
                                        />
                                        <div class="error">
                                            @error('accountInformation.fname')
                                                <span>{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <input
                                            class="@error('accountInformation.lname') is-invalid @else is-valid @enderror"
                                            type="text"
                                            wire:model="accountInformation.lname"
                                            placeholder="Last Name"
                                        />
                                        <div class="error">
                                            @error('accountInformation.lname')
                                                <span>{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <input
                                            class="@error('accountInformation.mobile') is-invalid @else is-valid @enderror"
                                            type="text"
                                            wire:model="accountInformation.mobile"
                                            placeholder="Phone Number"
                                        />
                                        <div class="error">
                                            @error('accountInformation.mobile')
                                                <span>{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <input
                                            class="@error('accountInformation.avatar_name') is-invalid @else is-valid @enderror"
                                            type="text"
                                            wire:model="accountInformation.avatar_name"
                                            placeholder="Avatar Name"
                                        />
                                        <div class="error">
                                            @error('accountInformation.avatar_name')
                                                <span>{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <input
                                            class="@error('accountInformation.avatar') is-invalid @else is-valid @enderror"
                                            type="file"
                                            wire:model="accountInformation.avatar"
                                            placeholder="Avatar Picture"
                                        />
                                        <div class="error">
                                            @error('accountInformation.avatar')
                                                <span>{{ $message }}</span>
                                            @enderror
                                        </div>
                                        @if (
                                            !isset($accountInformation['avatar']) &&
                                                auth()->user()->media('avatar')->first())
                                            <img
                                                src="{{ auth()->user()->avatar }}"
                                                width="150"
                                                height="150"
                                            />
                                        @endif
                                        @if (isset($accountInformation['avatar']))
                                            <img
                                                src="{{ $accountInformation['avatar']?->temporaryUrl() }}"
                                                width="150"
                                                height="150"
                                            >
                                        @endif
                                    </div>
                                    <button
                                        class="next action-button"
                                        name="next"
                                        type="button"
                                        wire:click='saveAccountInformation'
                                        wire:loading.attr="disabled"
                                    >
                                        Save & Next Step
                                    </button>
                                    <x-loading.spiner
                                        wire:loading
                                        wire:target="saveAccountInformation"
                                    />
                                </fieldset>
                            @endif
                            @if ($step == 'location')
                                <fieldset>
                                    <div class="form-card">
                                        <h2 class="fs-title mb-2">Location Information</h2>

                                        <div class="row">
                                            <div class="col-3">
                                                <label class="pay">Country *</label>
                                            </div>
                                            <div class="col-9">
                                                <select
                                                    class="list-dt @error('locationInformation.country_id') is-invalid @else is-valid @enderror"
                                                    id="month"
                                                    wire:model.live='locationInformation.country_id'
                                                >
                                                    <option selected>please choose your country</option>
                                                    @foreach ($this->countries as $country)
                                                        <option value="{{ $country['id'] }}">{{ $country['name'] }}
                                                        </option>
                                                    @endforeach

                                                </select>

                                            </div>
                                        </div>
                                        @error('locationInformation.country_id')
                                            <div class="error">
                                                <span>{{ $message }}</span>
                                            </div>
                                        @enderror
                                        <div class="row">
                                            <div class="col-3">
                                                <label class="pay">State *</label>
                                            </div>
                                            <div class="col-9">
                                                <select
                                                    class="list-dt @error('locationInformation.state_id') is-invalid @else is-valid @enderror"
                                                    id="month"
                                                    wire:model='locationInformation.state_id'
                                                >
                                                    <option selected>please choose your State</option>
                                                    @foreach ($this->states as $state)
                                                        <option value="{{ $state['id'] }}">{{ $state['name'] }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>
                                        @error('locationInformation.country_id')
                                            <div class="error">
                                                <span>{{ $message }}</span>
                                            </div>
                                        @enderror
                                        <div class="row">
                                            <div class="col-3">
                                                <label class="pay">Gender *</label>
                                            </div>
                                            <div class="col-9">
                                                <select
                                                    class="list-dt @error('locationInformation.gender') is-invalid @else is-valid @enderror"
                                                    id="month"
                                                    wire:model='locationInformation.gender'
                                                >
                                                    <option>choose</option>
                                                    @foreach ($gender as $item)
                                                        <option value="{{ $item['value'] }}">{{ $item['key'] }}
                                                        </option>
                                                    @endforeach

                                                </select>

                                            </div>
                                        </div>
                                        @error('locationInformation.gender')
                                            <div class="error">
                                                <span>{{ $message }}</span>
                                            </div>
                                        @enderror

                                        <input
                                            class="@error('locationInformation.birth_date') is-invalid @else is-valid @enderror"
                                            type="date"
                                            wire:model="locationInformation.birth_date"
                                            placeholder="birthday"
                                        />
                                        @error('locationInformation.birth_date')
                                            <div class="error">
                                                <span>{{ $message }}</span>
                                            </div>
                                        @enderror
                                        <input
                                            class="@error('locationInformation.bio') is-invalid @else is-valid @enderror"
                                            type="textarea"
                                            wire:model="locationInformation.bio"
                                            placeholder="Bio"
                                        />
                                        @error('locationInformation.bio')
                                            <div class="error">
                                                <span>{{ $message }}</span>
                                            </div>
                                        @enderror

                                    </div>
                                    <input
                                        class="previous action-button-previous"
                                        name="previous"
                                        type="button"
                                        value="Previous"
                                        wire:click="$set('step','account')"
                                    />
                                    <button
                                        class="next action-button"
                                        name="next"
                                        type="button"
                                        wire:click='saveLocationInformation'
                                        wire:loading.attr="disabled"
                                    >Save & Complete</button>
                                    <x-loading.spiner
                                        wire:loading
                                        wire:target="saveAccountInformation"
                                    />
                                </fieldset>
                            @endif

                            @if ($step == 'complete')
                                <fieldset>
                                    <div class="form-card">
                                        <h2 class="fs-title text-center">Success !</h2>
                                        <br><br>
                                        <div class="row justify-content-center">
                                            <div class="col-3">
                                                <img
                                                    class="fit-image"
                                                    src="https://img.icons8.com/color/96/000000/ok--v2.png"
                                                >
                                            </div>
                                        </div>
                                        <br><br>
                                        <div class="row justify-content-center">
                                            <div class="col-7 text-center">
                                                <h5 class="text-success">You Have Successfully Completed Profile</h5>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        html {
            height: 100%;
        }

        #complete-profile-form .error {
            color: red;
            margin-top: -20px;
            margin-bottom: 20px;
            font-size: 0.7rem;
        }

        #complete-profile-form .is-invalid {
            border-bottom: 2px solid red;
            outline-width: 0;
        }

        #complete-profile-form input:focus,
        /*form styles*/
        #complete-profile-form {
            text-align: center;
            position: relative;
            margin-top: 20px;
        }

        #complete-profile-form fieldset .form-card {
            background: white;
            border: 0 none;
            border-radius: 0px;
            box-shadow: 0 2px 2px 2px rgba(0, 0, 0, 0.2);
            padding: 20px 40px 30px 40px;
            box-sizing: border-box;
            width: 94%;
            margin: 0 3% 20px 3%;
            /*stacking fieldsets above each other*/
            position: relative;
        }

        #complete-profile-form fieldset {
            background: white;
            border: 0 none;
            border-radius: 0.5rem;
            box-sizing: border-box;
            width: 100%;
            margin: 0;
            padding-bottom: 20px;
            /*stacking fieldsets above each other*/
            position: relative;
        }

        /*Hide all except first fieldset*/
        #complete-profile-form fieldset:not(:first-of-type) {
            display: none;
        }

        #complete-profile-form fieldset .form-card {
            text-align: left;
            color: #9E9E9E;
        }

        #complete-profile-form select,
        #complete-profile-form input,
        #complete-profile-form textarea {
            padding: 0px 8px 4px 8px;
            border: none;
            border-bottom: 1px solid #ccc;
            border-radius: 0px;
            margin-bottom: 25px;
            margin-top: 2px;
            width: 100%;
            box-sizing: border-box;
            font-family: montserrat;
            color: #2C3E50;
            font-size: 16px;
            letter-spacing: 1px;
        }

        #complete-profile-form select:focus,
        #complete-profile-form input:focus,
        #complete-profile-form textarea:focus {
            -moz-box-shadow: none !important;
            -webkit-box-shadow: none !important;
            box-shadow: none !important;
            border: none;
            font-weight: bold;
            border-bottom: 2px solid rgb(255, 44, 64);
            outline-width: 0;
        }

        /*Blue Buttons*/
        #complete-profile-form .action-button {
            width: 150px;
            background: rgb(255, 44, 64);
            font-weight: bold;
            color: white;
            border: 0 none;
            border-radius: 0px;
            padding: 10px 5px;
            margin: 10px 5px;
        }

        #complete-profile-form .action-button:hover,
        #complete-profile-form .action-button:focus {
            box-shadow: 0 0 0 2px white, 0 0 0 3px rgb(255, 44, 64);
        }

        /*Previous Buttons*/
        #complete-profile-form .action-button-previous {
            width: 100px;
            background: #616161;
            font-weight: bold;
            color: white;
            border: 0 none;
            border-radius: 0px;
            cursor: pointer;
            padding: 10px 5px;
            margin: 10px 5px;
        }

        #complete-profile-form .action-button-previous:hover,
        #complete-profile-form .action-button-previous:focus {
            box-shadow: 0 0 0 2px white, 0 0 0 3px #616161;
        }

        /*Dropdown List Exp Date*/
        select.list-dt {
            border: none;
            outline: 0;
            border-bottom: 1px solid #ccc;
            padding: 2px 5px 3px 5px;
            margin: 2px;
        }

        select.list-dt:focus {
            border-bottom: 2px solid rgb(255, 44, 64);
        }

        /*The background card*/
        .card {
            z-index: 0;
            border: none;
            border-radius: 0.5rem;
            position: relative;
        }

        /*FieldSet headings*/
        .fs-title {
            font-size: 25px;
            color: #2C3E50;
            margin-bottom: 10px;
            font-weight: bold;
            text-align: left;
            padding-bottom: 20px;
        }

        /*progressbar*/
        #progressbar {
            margin-bottom: 30px;
            overflow: hidden;
            color: lightgrey;
        }

        #progressbar .active {
            color: #000000;
        }

        #progressbar li {
            list-style-type: none;
            font-size: 12px;
            width: 33%;
            float: left;
            position: relative;
        }

        /*Icons in the ProgressBar*/
        #progressbar #account:before {
            font-family: FontAwesome;
            content: "\f023";
        }

        #progressbar #location:before {
            font-family: FontAwesome;
            content: "\f007";
        }

        #progressbar #payment:before {
            font-family: FontAwesome;
            content: "\f09d";
        }

        #progressbar #confirm:before {
            font-family: FontAwesome;
            content: "\f00c";
        }

        /*ProgressBar before any progress*/
        #progressbar li:before {
            width: 50px;
            height: 50px;
            line-height: 45px;
            display: block;
            font-size: 18px;
            color: #ffffff;
            background: lightgray;
            border-radius: 50%;
            margin: 0 auto 10px auto;
            padding: 2px;
        }

        /*ProgressBar connectors*/
        #progressbar li:after {
            content: '';
            width: 100%;
            height: 2px;
            background: lightgray;
            position: absolute;
            left: 0;
            top: 25px;
            z-index: -1;
        }

        /*Color number of the step and the connector before it*/
        #progressbar li.active:before,
        #progressbar li.active:after {
            background: rgb(255, 44, 64);
        }

        /*Imaged Radio Buttons*/
        .radio-group {
            position: relative;
            margin-bottom: 25px;
        }

        .radio {
            display: inline-block;
            width: 204;
            height: 104;
            border-radius: 0;
            background: lightblue;
            box-shadow: 0 2px 2px 2px rgba(0, 0, 0, 0.2);
            box-sizing: border-box;
            cursor: pointer;
            margin: 8px 2px;
        }

        .radio:hover {
            box-shadow: 2px 2px 2px 2px rgba(0, 0, 0, 0.3);
        }

        .radio.selected {
            box-shadow: 1px 1px 2px 2px rgba(0, 0, 0, 0.1);
        }

        /*Fit image in bootstrap div*/
        .fit-image {
            width: 100%;
            object-fit: cover;
        }
    </style>
@endpush
