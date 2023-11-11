@extends('layouts.app')

@section('title', ' - 404')

@section('header')
    @parent
@endsection

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card-body text-center">
                    <h1 style="font-size: 5vh;"><i class="fas fa-times"></i> 404 - Not Found</h1>
                </div>
            </div>
        </div>
    </div>

@endsection
