<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ThemeLooks</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    @livewireStyles
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="{{ route('home') }}">ThemeLooks</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item {{ Route::currentRouteName() == 'home' ? 'active' : '' }}">
                    <a class="nav-link " href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item {{ Route::currentRouteName() == 'product.*' ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('product.index') }}">Product</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                        aria-expanded="false">
                        Attributes
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item {{ Route::currentRouteName() == 'category.*' ? 'active' : '' }}" href="{{ route('category.index') }}">Category</a>
                        <a class="dropdown-item {{ Route::currentRouteName() == 'size.*' ? 'active' : '' }}" href="{{ route('size.index') }}">Size</a>
                        <a class="dropdown-item {{ Route::currentRouteName() == 'color.*' ? 'active' : '' }}" href="{{ route('color.index') }}">Color</a>
                    </div>
                </li>
                <li class="nav-item {{ Route::currentRouteName() == 'pos' ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('pos') }}">POS</a>
                </li>
                <li class="nav-item {{ Route::currentRouteName() == 'order.list' || Route::currentRouteName() == 'order.list.items' ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('order.list') }}">Orders</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container-xxl px-5">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous">
    </script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js" integrity="sha512-L0Shl7nXXzIlBSUUPpxrokqq4ojqgZFQczTYlGjzONGTDAcLremjwaWv5A+EDLnxhQzY5xUZPWLOLqYRkY0Cbw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @yield('custom_js')
    @livewireScripts
</body>

</html>
