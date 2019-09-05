@extends('layout')

@section('title', 'index')

@section('content')

    @foreach($products as $product)
        <div>
            <img src={{ "/images/".$product->id.".".$product->image_extension }} alt={{ $product->title }} width="150" height="150">
            <h4>{{ $product->title }}</h4>
            <p>{{ $product->description }}</p>
            <h4>{{ $product->price }}</h4>
            <form method="POST" action={{ route('cart.store') }}>
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="submit" name="add" placeholder={{ trans("Add") }}>
            </form>
        </div>
    @endforeach

@endsection
