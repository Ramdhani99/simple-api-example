<div>
    <div class="row align-items-center justify-content-center vh-100">
        <div class="col-lg-6 col-md-10 col-sm-10">
            <div class="card">
                <div class="card-body">

                    <div class="text-center mt-4">
                        <p class="h2">Welcome back</p>
                        <p class="lead">
                            Sign in to your account to continue
                        </p>
                    </div>

                    <div class="m-sm-4">
                        <form wire:submit.prevent="login">
                            @csrf
                            <div class="form-floating mb-3">
                                <input type="email" wire:model.defer="email" class="form-control" id="email"
                                    placeholder="name@example.com" autofocus>
                                <label for="email">Email address</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="password" wire:model.defer="password" class="form-control" id="password"
                                    placeholder="Password">
                                <label for="password">Password</label>
                            </div>

                            <x-forms.submit-button title="Login" target="login" buttonClass="w-100" />

                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@section('title', 'Login Page')
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/toastify/toastify.css') }}">
@endpush
@push('scripts')
    <script src="{{ asset('assets/vendor/toastify/toastify.js') }}"></script>
    <script src="{{ asset('assets/vendor/toastify/toastify-message.js') }}"></script>
@endpush
