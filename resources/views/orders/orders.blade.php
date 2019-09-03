@extends('layout')

@section('title', 'orders')

@section('content')

    @foreach ($orders as $order)
        <div class="order">
            <h4>{{ trans("Name: ").$order->name }}</h4>
            <h4>{{ trans("E-mail: ").$order->email }}</h4>
            <h4>{{ trans("Comments: ").$order->comments }}</h4>
            <h4>{{ trans("Summed price: ").strval($order->summed_price) }} $</h4>
            <h4>{{ trans("Creation date: ").$order->created_at }}</h4>
            <a href="#">{{ trans("View") }}</a>
        </div>
    @endforeach

@endsection
