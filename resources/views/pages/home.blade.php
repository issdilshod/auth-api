@extends('layouts.master')

@section('title', $title)

@section('content')
<section class="container main-block">
    <div class="mt-2 mb-2">
        <img src="{{ asset('assets/img/logo.png') }}" width="50px" />
    </div>
    <h4>{{ __('home.welcome_text').auth()->user()->first_name.' '.auth()->user()->last_name }}</h4>
    <div class="mt-2 mb-2">
        <img src="{{ asset('storage/'.auth()->user()->image) }}" width="100px" />
    </div>
    <a href="{{ route('logout') }}" class="btn btn-primary">{{ __('auth.logout') }}</a>
</section>
@endsection