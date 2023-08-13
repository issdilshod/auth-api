@extends('layouts.master')

@section('title', $title)

@section('content')
<section class="container main-block">
    <img src="{{ asset('assets/img/logo.png') }}" width="50px" />
    <h4>{{ __('auth.title') }}</h4>
    <a class="btn btn-primary" href="{{ route('googleAuth') }}">{{ __('auth.via_google') }}</a>
    <a class="btn btn-primary" href="{{ route('yandexAuth') }}">{{ __('auth.via_yandex') }}</a>
</section>
@endsection