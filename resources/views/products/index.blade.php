@extends('layout')

@section('title', 'index')

@section('content')

    @foreach($products as $product)
        <div>
            <img src={{ "/images/".$product->id.".".$product->image_extension }} alt="{{ $product->title }}" width="150" height="150">

        </div>
    @endforeach

@endsection
