@extends('layouts.master')
@section('title', 'Login Page')
@section('content')
    <div class="row align-items-center justify-content-center vh-100">
        <div class="col-lg-6 col-md-10 col-sm-10">
            <div class="card">
                <div class="card-body">

                    {{-- Alert --}}
                    @if (isset(session('response')->message))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert" id="message_alert">
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            <div class="alert-message">
                                <strong>{{ session('response')->message }}</strong>
                            </div>
                        </div>
                    @endif

                    <div class="text-center mt-4">
                        <p class="h2">Welcome back</p>
                        <p class="lead">
                            Sign in to your account to continue
                        </p>
                    </div>

                    <div class="m-sm-4">
                        <form method="POST" action="/">
                            @csrf
                            <div class="form-floating mb-3">
                                <input type="email" name="email" class="form-control" id="email"
                                    placeholder="name@example.com" autofocus required value="{{ old('email') }}">
                                <label for="email">Email address</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="password" name="password" class="form-control" id="password"
                                    placeholder="Password" required>
                                <label for="password">Password</label>
                            </div>

                            <button type="submit" class="w-100 btn btn-lg btn-primary">Login</button>

                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
