@extends('layout')

@section('title', 'edit')

@section('content')

    <div>
        <form method="POST" action={{ route('update', $product->id) }} enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="_method" value="PUT">
            Title: <input type="text" name="title" value="{{ $product->title }}" required>
            <br>
            <br>
            Description: <input type="text" name="description" value="{{ $product->description }}" required>
            <br>
            <br>
            Price: <input type="number" step="0.01" name="price" value="{{ $product->price }}" required>
            <br>
            <br>
            <input type="file" name="image" placeholder={{ trans("Image") }}>
            <br>
            <br>
            <input type="submit" name="save" value={{ trans("Save") }}>
        </form>
    </div>

    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

@endsection
