<div>
    @if (session()->has('user_token'))
        <nav class="navbar navbar-expand-lg bg-light mb-3">
            <div class="container">
                <a class="navbar-brand justify-content-start" href="home">My Website</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                    aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
                    <div class="navbar-nav">
                        <span class="text-primary nav-link">Hello {{ session('user')->name }}</span>
                        {{-- Logout --}}
                        <div>
                            <div wire:loading.remove wire:target="logout">
                                <button class="btn btn-link nav-link" wire:click="logout"
                                    wire:offline.attr="disabled">Logout</button>
                            </div>
                            <div wire:loading wire:target="logout">
                                <button class="btn btn-link nav-link" type="button" disabled>
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>
                                    Loading...
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </nav>
    @endif
</div>
