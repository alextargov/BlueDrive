<!-- Stored in resources/views/layouts/master.blade.php -->

<html>
    <head>
        <title>BlueDrive - @yield('title')</title>
        @yield('css')
        <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/basic.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.css') }}">
    </head>
    <body>


        <div class="container">
            @yield('content')
        </div>
    </body>
</html>