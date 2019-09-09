<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
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

            function renderList(products, addToCart, edit) {

                html = [
                    '<tr>',
                    '<th>Title</th>',
                    '<th>Description</th>',
                    '<th>Price</th>',
                    '<th>Action</th>',
                    '</tr>'
                ].join('');

                $.each(products, function (key, product) {
                    var button = '';
                    if (edit) {
                        button = '<button class="edit_product" data-product-id="' + product.id + '">Edit</button>' + ' ' +
                            '<button class="delete_product" data-product-id="' + product.id + '">Delete</button>';
                    } else {
                        if (addToCart) {
                            button = '<button class="add_to_cart" data-product-id="' + product.id + '">Add to Cart</button>';
                        } else {
                            button = '<button class="remove_from_cart" data-product-id="' + product.id + '">Remove</button>';
                        }
                    }
                    html += [
                        '<tr>',
                        '<td>' + product.title + '</td>',
                        '<td>' + product.description + '</td>',
                        '<td>' + product.price + '</td>',
                        '<td>' + button + '</td>',
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

                switch (window.location.hash) {
                    case '#cart':
                        // Show the cart page
                        $('.cart').show();
                        // Load the cart products from the server
                        $.ajax('{{ route('cart.index') }}', {
                            method: 'GET',
                            dataType: 'json',
                            success: function (response) {
                                if (response.login) {
                                    hash = 'login';
                                }
                                // Render the products in the cart list
                                $('.cart .list').html(renderList(response));
                            }
                        });
                        break;
                    case '#login':
                        $('.login').show();
                        break;
                    case '#products':
                        $('.products').show();
                        $.ajax('{{ route('product.products') }}', {
                            method: 'GET',
                            dataType: 'json',
                            success: function (response) {
                                $('.products .products').html(renderList(response, 0, 1));
                            },
                            error: function (response) {
                                if (response.responseJSON.login) {
                                    window.location.hash = 'login';
                                }
                            }
                        })
                        break;
                    case '#product':
                        $('.product').show();
                        break;
                    case '#logout':
                        $.ajax('{{ route('logout') }}', {
                            method: 'GET',
                            dataType: 'json',
                            success: function (response) {
                                window.location.hash = '#';
                            }
                        })
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
                        window.onhashchange();
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
                        window.onhashchange();
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
            $.ajax("{{ route('checkLogin') }}", {
                method: 'POST',
                dataType: 'json',
                data: {
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

        $(document).on('submit', '.order_form', function (event) {
            console.log('Make new order');
            var name = $("#name").val();
            var email = $("#email").val();
            var comments = $("#comments").val();
            $.ajax("{{ route('orders.store') }}", {
                method: 'POST',
                dataType: 'json',
                data: {
                    name: name,
                    email: email,
                    comments: comments
                },
                success: function (response) {
                    if (response.success) {
                        alert('Order created succesfully');
                        window.location.hash = '';
                    } else {
                        alert('Failed');
                    }
                }
            });
            event.preventDefault();
        })

        $(document).on('click', '.edit_product', function (event) {
            console.log('edit product');
            var data = $(this).attr('data-product-id');
            $.ajax("{{ url('edit') }}" + "/" + data, {
                method: "GET",
                dataType: 'json',
                success: function (response) {
                    if (response) {
                        window.location.hash = 'product';
                        $('input[name=title]').val(response.title);
                        $('input[name=description]').val(response.description);
                        $('input[name=price]').val(response.price);
                    }
                }
            })
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

</div>

<div class="page product">
    <form enctype="multipart/form-data">
        Title: <input type="text" name="title" value="" required>
        <br>
        <br>
        Description: <input type="text" name="description" value="" required>
        <br>
        <br>
        Price: <input type="number" step="0.01" name="price" value="" required>
        <br>
        <br>
        <input type="file" name="image" placeholder={{ trans("Image") }}>
        <br>
        <br>
        <input type="submit" name="save" value={{ trans("Save") }}>
    </form>
</div>

</body>
</html>
