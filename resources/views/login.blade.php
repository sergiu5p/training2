@extends('layout')

@section('title', 'login')

@section('content')

    @if (isset($message))
        <div>
            <p>{{ $message }}</p>
        </div>
    @endif

    <form method="POST" action="{{ route('checkLogin')}}">
        @csrf

        <input type="text" name="username" placeholder="{{ trans('username') }}">
        <input type="password" name="password" placeholder="{{ trans('password') }}">
        <input type="submit" value="{{ trans('Login') }}">
    </form>

@endsection
