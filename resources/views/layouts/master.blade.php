<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Awesome Website | @yield('title')</title>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    @livewireStyles

    @stack('css')

</head>

<body style="background-color: rgb(52, 52, 52)">

    @livewire('layouts.topbar')

    <div class="container h-100">
        @yield('content')
    </div>

    <noscript>
        <div
            style="position: fixed; top: 0px; left: 0px; z-index: 30000000;
              height: 100%; width: 100%; background-color: #FFFFFF">
            <div class="alert alert-danger alert-dismissible" role="alert">
                <div class="alert-message">
                    <h4 class="alert-heading">Warning!</h4>
                    <p>
                        Javascript is not enabled.
                        <br>
                        Please enable Javascript to access our website.
                    </p>
                </div>
            </div>
        </div>
    </noscript>

    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

    @livewireScripts

    @stack('scripts')

    @stack('action_scripts')

</body>

</html>
