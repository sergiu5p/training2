@extends('layout')

@section('title', 'index')

@section('content')

    @foreach($products as $product)
        <div>
            <img src={{ "/images/".$product->id.".".$product->image_extension }} alt="{{ $product->title }}" width="150" height="150">
            <h4>{{ $product->title }}</h4>
            <p>{{ $product->description }}</p>
            <h4>{{ $product->price }}</h4>
            <a href="add/{{$product->id}}">{{ trans("Add") }}</a>
        </div>
    @endforeach
        <div>
            <a href="/cart">Go to Cart!</a>
        </div>

@endsection
