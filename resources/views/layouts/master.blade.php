<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Awesome Website | @yield('title')</title>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
  </head>

  <body>
    
    @if (session()->has('user_token'))
      <nav class="navbar navbar-expand-lg bg-light mb-3">
        <div class="container">
          <a class="navbar-brand justify-content-start" href="home">Navbar</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
            <div class="navbar-nav">
              <span class="text-primary nav-link">Hello {{ session('user')->name }}</span>
              <form action="logout" method="POST">
                @csrf
                <button class="btn btn-link nav-link" type="submit">Logout</button>
              </form>
            </div>
          </div>
        </div>
      </nav>
    @endif

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

    @stack('scripts')

    @stack('action_scripts')

    {{-- Close the alert message automatically --}}
    @if (session()->has('success') || session()->has('error'))
      <script>
          $(document).ready(function() {
              window.setTimeout(function() {
                  $("#message_alert").fadeTo(500, 0).slideUp(500, function() {
                      $(this).remove();
                  });
              }, 3000);
          });
      </script>
    @endif
    
  </body>

</html>
