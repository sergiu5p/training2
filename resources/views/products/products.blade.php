@extends('layout')

@section('title', 'products')


@section('content')

    @foreach($products as $product)
        <div>
            <img src={{ "/images/".$product->id.".".$product->image_extension }} alt="{{ $product->title }}" width="150" height="150">
            <h4>{{ $product->title }}</h4>
            <p>{{ $product->description }}</p>
            <h4>{{ $product->price }}</h4>
            <a href="{{ route('product.edit', $product->id) }}">{{ trans("Edit") }}</a>
            <a href="{{ route('product.destroy', $product->id) }}">{{ trans("Remove") }}</a>
        </div>
    @endforeach
    <a href="{{ route('product.edit') }}">{{ trans('Add') }}</a>

@endsection
