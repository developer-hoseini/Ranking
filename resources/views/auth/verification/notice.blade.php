@extends('layouts.app')

@section('title', __('words. - ') . __('words.About_Ranking'))

@section('header')
    @parent
@endsection

@section('content')

    <div class="bg-light container rounded p-5">

        @if (session('resent'))
            <div
                class="alert alert-success"
                role="alert"
            >
                A fresh verification link has been sent to your email address.
            </div>
        @endif

        Before proceeding, please check your email for a verification link. If you did not receive the email,
        <form
            class="d-inline"
            action="{{ route('verification.resend') }}"
            method="POST"
        >
            @csrf
            <button
                class="d-inline btn btn-link p-0"
                type="submit"
            >
                click here to request another
            </button>.
        </form>
    </div>

@endsection
