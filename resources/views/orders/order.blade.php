@extends('layout')

@section('title', 'orders')

@section('content')

    <div class="order">
        <h4>{{ trans("Name: ").$order[0]->name }}</h4>
        <h4>{{ trans("E-mail: ").$order[0]->email }}</h4>
        <h4>{{ trans("Comments: ").$order[0]->comments }}</h4>
        @foreach ($order as $ord)
            <h4>{{ $ord->title }}</h4>
        @endforeach
    </div>

@endsection
