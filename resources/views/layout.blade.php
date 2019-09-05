<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" type="text/css" href="{{ url('/css/style.css') }}" />
    <title></title>
</head>

<body>
    <ul>
        @if (request()->session()->has('login'))
            <li><a href={{ route('logout') }}>{{ trans('Logout') }}</a></li>
            <li><a href={{ route('product.products') }}>products.php</a></li>
            <li><a href={{ route('orders.index') }}>orders.php</a></li>
        @else
            <li><a href={{ route('login') }}>{{ trans('Login') }}</a></li>
        @endif

        <li><a href={{ route('product.show') }}>index.php</a></li>

        @if (request()->session()->has('cart') && request()->session()->get('cart')->items)
            <li><a href={{ route('cart.index') }}>{{ trans('Go to cart') }}</a></li>
        @else
            <li>{{ trans('Cart is empty') }}</li>
        @endif
    </ul>
    <div>
        @yield('content')
    </div>
</body>

</html>
