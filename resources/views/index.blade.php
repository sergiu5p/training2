@extends('ajaxLayout')

@section('title', 'spa')

@section('content')
<!-- The index page -->
<div class="page index">
    <!-- The index element where the products list is rendered -->
    <table class="list"></table>

    <!-- A link to go to the cart by changing the hash -->
    <a href="#cart" class="button">Go to cart</a>
    <a href="#logout">Logout</a>
</div>

<!-- The cart page -->
<div class="page cart">
    <!-- The cart element where the products list is rendered -->
    <table class="list"></table>

    <!-- A link to go to the index by changing the hash -->
    <a href="#" class="button">Go to index</a>
    <a href="#logout">Logout</a>

    <form class="order_form">
        <input type="text" id="name" name="name" placeholder={{ trans('Name') }} required>
        <br>
        <br>
        <input type="email" id="email" name="email" placeholder={{ trans('E-mail') }} required>
        <br>
        <br>
        <input type="text" id='comments' name="comments" placeholder={{ trans("Comments") }}>
        <br>
        <br>
        <input type="submit" name="checkout" value="{{ trans('Checkout') }}">
    </form>
</div>

<!-- The login page -->
<div class="page login">

    <span class="login_message"></span>

    <form class="login_form">
        <input type="text" id="user_login" name="username" placeholder={{ trans('username') }}>
        <br>
        <br>
        <input type="password" id="pass_login" name="password" placeholder={{ trans('password') }}>
        <br>
        <br>
        <input type="submit" value={{ trans('Login') }}>
    </form>

    <!-- A link to go to the index by changing the hash -->
    <a href="#" class="button">Go to index</a>
</div>

<div class="page products">
    <a href="#logout">Logout</a>
    <a href="#cart" class="button">Go to cart</a>
    <a href="#" class="button">Go to index</a>

    <table class="products"></table>
    <a href="spa#product">{{ trans('Add') }}</a>

</div>

<div class="page product">
    <form class="product_form" enctype="multipart/form-data">
        <input id="product_id" type="hidden" name="product_id" value="">
        Title: <input id="product_title" type="text" name="title" value="" required>
        <br>
        <br>
        Description: <input id="product_description" type="text" name="description" value="" required>
        <br>
        <br>
        Price: <input id="product_price" type="number" step="0.01" name="price" value="" required>
        <br>
        <br>
        <input id="product_image" type="file" name="image" placeholder={{ trans("Image") }}>
        <br>
        <br>
        <input type="submit" name="save" value={{ trans("Save") }}>
    </form>
</div>

<div class="page orders">

    <div class="orders_container"></div>
</div>

<div class="page order">

    <div class="order_container"></div>
</div>
@endsection()
