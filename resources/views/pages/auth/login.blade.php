@extends('layouts.app')

@section('title', __('words. - ') . __('words.About_Ranking'))

@section('header')
    @parent
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">

                <div class="card login-page">
                    <div class="card-header">
                        <ul
                            class="nav nav-tabs"
                            id="myTab"
                            role="tablist"
                        >
                            <li class="nav-item">
                                <p
                                    class="nav-link active"
                                    id="login-tab"
                                    data-toggle="tab"
                                    role="tab"
                                    aria-controls="login"
                                    aria-selected="true"
                                >{{ __('words.Login') }}</p>
                            </li>
                        </ul>
                    </div>

                    <div
                        class="tab-content"
                        id="myTabContent"
                    >
                        <div
                            class="tab-pane fade show active"
                            id="login"
                            role="tabpanel"
                            aria-labelledby="login-tab"
                        >
                            <form
                                method="POST"
                                action="{{ route('auth.login', request('callback') ? ['callback' => request('callback')] : '') }}"
                            >
                                @csrf

                                <div class="form-group row">
                                    <label
                                        class="col-md-6 col-form-label"
                                        for="avatar-name"
                                    >
                                        avatar name
                                    </label>

                                    <div class="col-md-6">
                                        <input
                                            class="form-control{{ $errors->has('avatar-name') && !old('register-sign') ? ' is-invalid' : '' }}"
                                            id="avatar-name"
                                            name="avatar-name"
                                            type="text"
                                            value="{{ !old('register-sign') ? old('avatar-name') : '' }}"
                                            required
                                        >

                                        @if ($errors->has('avatar-name') && !old('register-sign'))
                                            <span
                                                class="invalid-feedback"
                                                role="alert"
                                            >
                                                <strong>{{ $errors->first('avatar-name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label
                                        class="col-md-6 col-form-label"
                                        for="password"
                                    >
                                        {{ __('words.Password') }}
                                    </label>

                                    <div class="col-md-6">
                                        <input
                                            class="form-control{{ $errors->has('password') && !old('register-sign') ? ' is-invalid' : '' }}"
                                            id="password"
                                            name="password"
                                            type="password"
                                            required
                                        >

                                        @if ($errors->has('password') && !old('register-sign'))
                                            <span
                                                class="invalid-feedback"
                                                role="alert"
                                            >
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div
                                    class="form-group row"
                                    style="display: none;"
                                >
                                    <div class="col-md-6 offset-md-4">
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                id="remember"
                                                name="remember"
                                                type="checkbox"
                                                {{-- old('remember') ? 'checked' : '' --}}
                                                checked
                                            >

                                            <label
                                                class="form-check-label"
                                                for="remember"
                                            >
                                                {{ __('words.Remember Me') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="login-forgot-custom mx-auto">
                                        <button
                                            class="btn btn-danger"
                                            type="submit"
                                        >
                                            {{ __('words.Login') }}
                                        </button>

                                        @if (Route::has('password.request'))
                                            <a
                                                class="btn btn-link forgot_pass"
                                                href="{{ route('password.request') }}"
                                            >
                                                {{ __('words.Forgot Your Password?') }}
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                <hr>

                                {{-- TODO: Login qr code --}}
                                {{-- <div class="form-group row">
                                    <div class="offset-md-4 login-forgot-custom">
                                        <a
                                            class="btn btn-link"
                                            href="{{ route('login_qrcode') }}"
                                        >
                                            <img
                                                src="{{ url('/img/scan_black.png') }}"
                                                width="20"
                                            >
                                            {{ __('words.login_with_card') }}
                                        </a>
                                    </div>
                                </div> --}}

                            </form>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
