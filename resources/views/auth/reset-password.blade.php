@extends('layouts.app')

@section('title', 'Reset password')

@section('header')
    @parent
@endsection

@section('content')

    <div class="container">
        <div
            class="card mx-auto p-3"
            style="width: 25rem; margin-top: 8rem"
        >
            <h1>Reset Password</h1>
            <div class="card-body">
                <form
                    method="POST"
                    action="{{ route('password.update') }}"
                >
                    @csrf
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input
                            class="form-control @error('email') is-invalid @enderror"
                            id="email"
                            name="email"
                            type="email"
                            aria-describedby="emailHelp"
                            placeholder="Enter email"
                        >
                        @error('email')
                            <div class="text-danger">
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input
                            class="form-control @error('password') is-invalid @enderror"
                            id="password"
                            name="password"
                            type="password"
                            placeholder="Enter password"
                        >
                        @error('password')
                            <div class="text-danger">
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Password Confirmation</label>
                        <input
                            class="form-control @error('password_confirmation') is-invalid @enderror"
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            placeholder="Enter password confirmation"
                        >
                        @error('password_confirmation')
                            <div class="text-danger">
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>
                    <input
                        name="token"
                        value="{{ $token }}"
                        hidden
                    >

                    <div class="d-flex mt-4 justify-items-center">
                        <button
                            class="btn btn-danger mr-1"
                            type="submit"
                        >
                            Reset Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
