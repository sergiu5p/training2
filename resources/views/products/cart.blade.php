@extends('layout')

@section('title', 'cart')

@section('content')

    @foreach($products as $product)
        <div>
            <img alt={{ $product->title }} src={{ "/images/".$product->id.".".$product->image_extension }} width="150" height="150">
            <h4>{{ $product->title }}</h4>
            <p>{{ $product->description }}</p>
            <h4>{{ $product->price }}</h4>
            <a href={{ route('product.remove', $product->id) }}>{{ trans("Remove") }}</a>
        </div>
    @endforeach

    <form method="POST" action={{ route('sendMail') }}>
        @csrf

        <input type="text" name="name" placeholder={{ trans('Name') }} required>
        <br>
        <br>
        <input type="email" name="email" placeholder={{ trans('E-mail') }} required>
        <br>
        <br>
        <input type="text" name="comments" placeholder={{ trans("Comments") }}>
        <br>
        <br>
        <button name="checkout">{{ trans("Checkout") }}</button>
    </form>
@endsection
