@extends('layout')

@section('title', 'create')

@section('content')

    <div>
        <form method="POST" action={{ route('product.store') }} enctype="multipart/form-data">
            @csrf

            Title: <input type="text" name="title" required>
            <br>
            <br>
            Description: <input type="text" name="description" required>
            <br>
            <br>
            Price: <input type="number" step="0.01" name="price" required>
            <br>
            <br>
            <input type="file" name="image" placeholder={{ trans("Image") }} required>
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
