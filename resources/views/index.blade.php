<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Load the jQuery JS library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <!-- Custom JS script -->
    <script type="text/javascript">
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            /**
             * A function that takes a products array and renders it's html
             *
             * The products array must be in the form of
             * [{
             *     "title": "Product 1 title",
             *     "description": "Product 1 desc",
             *     "price": 1
             * },{
             *     "title": "Product 2 title",
             *     "description": "Product 2 desc",
             *     "price": 2
             * }]
             */
            function renderList(products, productsList) {
                html = [
                    '<tr>',
                    '<th>Title</th>',
                    '<th>Description</th>',
                    '<th>Price</th>',
                    '<th>Action</th>',
                    '</tr>'
                ].join('');

                $.each(products, function (key, product) {
                    html += [
                        '<tr>',
                        '<td>' + product.title + '</td>',
                        '<td>' + product.description + '</td>',
                        '<td>' + product.price + '</td>',
                        '<td>' + (productsList ? '<a href="" class="add_to_cart" data-product-id="'+product.id+'">Add to Cart</a>' :
                            '<a href="" class="remove_from_cart" data-product-id="'+product.id+'">Remove</a>'),
                        '</tr>'
                    ].join('');
                });

                return html;
            }

            /**
             * URL hash change handler
             */
            window.onhashchange = function () {
                // First hide all the pages
                $('.page').hide();

                switch(window.location.hash) {
                    case '#cart':
                        // Show the cart page
                        $('.cart').show();
                        // Load the cart products from the server
                        $.ajax('{{ route('cart.index') }}', {
                            method: 'GET',
                            dataType: 'json',
                            success: function (response) {
                                // Render the products in the cart list
                                $('.cart .list').html(renderList(response));
                            }
                        });
                        break;
                    case '#login':
                        $('.login').show();
                        break;
                    default:
                        // If all else fails, always default to index
                        // Show the index page
                        $('.index').show();
                        // Load the index products from the server
                        $.ajax('{{ route('product.show') }}', {
                            method: 'GET',
                            dataType: 'json',
                            success: function (response) {
                                // Render the products in the index list
                                $('.index .list').html(renderList(response, 1));
                            }
                        });
                        break;
                }
            }

            window.onhashchange();
        });

        $(document).on('click', '.add_to_cart', function () {
            console.log('add_to_cart');
            $.ajax('{{ route('cart.store') }}', {
                method: 'POST',
                dataType: 'json',
                data: {
                    product_id: $(this).attr('data-product-id')
                },
                success: function (response) {
                    // Render the products in the index list
                    if (response.success) {
                        alert('Product added to cart');
                    } else {
                        alert('Failed to add');
                    }
                }
            });
        });

        $(document).on('click', '.remove_from_cart', function () {
            console.log('remove_from_cart');
            var data = $(this).attr('data-product-id');
            $.ajax("{{ url('cart') }}" + "/" + data, {
                method: 'DELETE',
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        alert('Product removed from cart');
                    } else {
                        alert('Failed to remove');
                    }
                }
            })
        })

        $(document).on('submit', '.login_form', function (event) {
            console.log('login');
            var username = $("#user_login").val();
            var password = $("#pass_login").val();
            console.log(username, password);
            $.ajax("{{ route('checkLogin') }}", {
                method: 'POST',
                dataType: 'json',
                data : {
                    username: username,
                    password: password
                },
                success: function (response) {
                    if (response.success) {
                        alert('Login succesfull');
                        window.location.hash = 'cart';
                    } else {
                        alert('Login failed');
                    }
                }
            });
            event.preventDefault();
        })
    </script>
</head>
<body>
<!-- The index page -->
<div class="page index">
    <!-- The index element where the products list is rendered -->
    <table class="list"></table>

    <!-- A link to go to the cart by changing the hash -->
    <a href="#cart" class="button">Go to cart</a>
</div>

<!-- The cart page -->
<div class="page cart">
    <!-- The cart element where the products list is rendered -->
    <table class="list"></table>

    <!-- A link to go to the index by changing the hash -->
    <a href="#" class="button">Go to index</a>
</div>

<!-- The login page -->
<div class="page login">

    <span class="login_message"></span>

    <form class="login_form">
        <input type="text" id="user_login" name="username" placeholder={{ trans('username') }}>
        <input type="password" id="pass_login" name="password" placeholder={{ trans('password') }}>
        <input type="submit" value={{ trans('Login') }}>
    </form>

    <!-- A link to go to the index by changing the hash -->
    <a href="#" class="button">Go to index</a>
</div>
</body>
</html>
