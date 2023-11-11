@extends('layouts.app')

@section('title', ' - 500')

@section('header')
    @parent
@endsection

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card-body text-center">

                    <h1 style="font-size: 5vh;"><i class="fas fa-times"></i> {{ __('500') }} - Server Error</h1>

                </div>

            </div>
        </div>
    </div>

@endsection
