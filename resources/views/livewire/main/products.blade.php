<div>
    {{-- default loading states --}}
    <div wire:loading>
        <div class="d-flex bg-dark justify-content-center align-items-center position-fixed top-0 start-0 w-100 h-100 opacity-50"
            style="z-index: 9999;">
            <div class="spinner-grow spinner-grow-lg text-danger" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div class="spinner-grow spinner-grow-lg text-success" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div class="spinner-grow spinner-grow-lg text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
    {{-- Show Form, Add Button & Search Form --}}
    <div class="row mx-auto g-0">
        {{-- Add Button --}}
        <div class="col mb-2">
            <button class=" btn btn-primary" type="button" wire:click="display_modal('create')">New data</button>
        </div>
        {{-- Show Select --}}
        <div class="col-lg-1 col-md-2 mb-2 me-lg-2 me-md-2 me-sm-0">
            {{-- <form wire:submit.prevent="destroy"> --}}
            <select class="form-select form-control" wire:change.defer="change_page_size($event.target.value)">
                @php
                    $page_size_options = ['10', '25', '50', '100'];
                @endphp
                @foreach ($page_size_options as $option)
                    <option value="{{ $option }}" @if ($this->page_size == $option) selected @endif>
                        {{ $option }}</option>
                @endforeach
            </select>
        </div>
        {{-- Search --}}
        <div class="col mb-2">
            {{-- <form wire:submit.prevent="search">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Search..." wire:model.defer="search"
                        value="{{ $this->search }}">
                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                </div>
            </form> --}}
            <input type="search" class="form-control" placeholder="Search..." wire:model.debounce.750ms="search"
                value="{{ $this->search }}">
            {{-- <input type="text" class="form-control" placeholder="Search..." wire:model.lazy="search"
                        value="{{ $this->search }}"> --}}
        </div>
    </div>
    @push('css')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    @endpush
    {{ session('user_token') }}
    {{-- Table --}}
    <table class="table table-sm table-striped align-middle">
        <thead class="table-light">
            <tr class="align-middle">
                <th scope="col">#</th>
                {{-- <th scope="col">Name <button class="btn"><i class="bi bi-filter"></button></i></th>
                <th scope="col">Price <button class="btn"><i class="bi bi-filter"></button></i></th> --}}
                @if (isset($response->data[0]))
                    @foreach ($response->data[0] as $key => $value)
                        @if ($key == 'id')
                            @continue
                        @endif
                        <th scope="col">
                            {{-- Header --}}
                            {{ ucwords(str_replace('_', ' ', strtolower(preg_replace('/(?<!\ )[A-Z]/', " $0", $key)))) }}
                            {{-- Sort --}}
                            <button class="btn" wire:click="sort('{{ $key }}', '{{ $this->sort_order }}')">
                                @if ($key == $this->sort_column && $this->sort_order == 'asc')
                                    <span class="bi bi-sort-up"></span>
                                @elseif ($key == $this->sort_column && $this->sort_order == 'desc')
                                    <span class="bi bi-sort-down"></span>
                                @else
                                    <span class="bi bi-filter"></span>
                                @endif
                            </button>
                        </th>
                    @endforeach
                @endif
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($response->data as $data)
                <tr>
                    <td>{{ $response->meta->from + $loop->iteration - 1 }}</td>
                    @foreach ($data as $key => $value)
                        @if ($key == 'id')
                            @continue
                        @endif
                        <td>{{ $value }}</td>
                    @endforeach
                    <div wire:ignore.self>
                        <td>
                            {{-- <button type="button" class="btn btn-warning" id="edit"
                                @foreach ($data as $key => $value) data-{{ $key }}="{{ $value }}" @endforeach>Edit
                            </button>
                            <button type="button" class="btn btn-danger" id="delete"
                                @foreach ($data as $key => $value) data-{{ $key }}="{{ $value }}" @endforeach>Delete
                            </button> --}}
                            @foreach ($data as $key => $value)
                                @php
                                    $array_data[$key] = $value;
                                @endphp
                            @endforeach
                            <button type="button" class="btn btn-warning"
                                wire:click="display_modal('edit', {{ json_encode($array_data) }})">Edit
                            </button>
                            <button type="button" class="btn btn-danger"
                                wire:click="display_modal('delete', {{ json_encode($array_data) }})">Delete
                            </button>
                        </td>
                    </div>
                </tr>
            @empty
                <tr class="text-center">
                    <td colspan="999">
                        <p>No Data.</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- paginating --}}
    <div class="row mx-auto g-0">
        <div class="col-lg-4 mb-2">
            Showing {{ $response->meta->from ?? 0 }} to {{ $response->meta->to }} of {{ $response->meta->total }}
            entries
        </div>
        <div class="col">
            <ul class="pagination flex-wrap justify-content-lg-end">
                {{-- links --}}
                @foreach ($response->meta->links as $link)
                    <li class="page-item @if ($link->active) active @endif">
                        @if ($link->url == null)
                            <span class="page-link text-muted">{!! $link->label !!}</span>
                        @elseif ($link->active)
                            <span class="page-link">{!! $link->label !!}</span>
                        @else
                            @php
                                parse_str(parse_url($link->url)['query'], $params);
                            @endphp
                            <button class="btn btn-link page-link"
                                wire:click.defer="change_page({{ $params['page'] }})">
                                <span>{!! $link->label !!}</span>
                            </button>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- Modal --}}
    {{-- Modal Create --}}
    <div class="modal fade" id="modal_create" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">&nbsp;Add New Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="store('create')">

                        <div class="form-floating mb-3">
                            <input type="text"
                                class="form-control @if (isset(session('response')->errors->name[0])) is-invalid @endif"
                                placeholder="Name" required wire:model.defer="name">
                            <label>Name</label>
                            @if (isset(session('response')->errors->name[0]))
                                <div class="invalid-feedback">
                                    {{ session('response')->errors->name[0] }}
                                </div>
                            @endif
                        </div>

                        <div class="form-floating mb-3">
                            <input type="number" min="1"
                                class="form-control @if (isset(session('response')->errors->price[0])) is-invalid @endif"
                                placeholder="Price" required wire:model.defer="price">
                            <label>Price</label>
                            @if (isset(session('response')->errors->price[0]))
                                <div class="invalid-feedback">
                                    {{ session('response')->errors->price[0] }}
                                </div>
                            @endif
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>
    {{-- Modal Edit --}}
    <div class="modal fade" id="modal_edit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-bg-warning bg-opacity-75">
                    <h5 class="modal-title">Edit Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form wire:submit.prevent="update('edit')">

                        <div class="form-floating mb-3">
                            <input type="text"
                                class="form-control @if (isset(session('response')->errors->name[0])) is-invalid @endif"
                                placeholder="Name" required wire:model.defer="name">
                            <label>Name</label>
                            @if (isset(session('response')->errors->name[0]))
                                <div class="invalid-feedback">
                                    {{ session('response')->errors->name[0] }}
                                </div>
                            @endif
                        </div>

                        <div class="form-floating mb-3">
                            <input type="number" min="1"
                                class="form-control @if (isset(session('response')->errors->price[0])) is-invalid @endif"
                                placeholder="Price" required wire:model.defer="price">
                            <label>Price</label>
                            @if (isset(session('response')->errors->price[0]))
                                <div class="invalid-feedback">
                                    {{ session('response')->errors->price[0] }}
                                </div>
                            @endif
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-warning">Update</button>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>
    {{-- Modal Delete --}}
    <div class="modal fade" id="modal_delete" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-bg-danger bg-opacity-75">
                    <h5 class="modal-title">Delete Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form wire:submit.prevent="destroy('delete')">

                        <span>
                            <h4>Are you sure?</h4>
                            Do you want to delete: <b>{{ $this->name }}</b>
                        </span>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

@section('title', 'Home')
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/toastify/toastify.css') }}">
@endpush
@push('scripts')
    <script src="{{ asset('assets/vendor/toastify/toastify.js') }}"></script>
    <script src="{{ asset('assets/vendor/toastify/toastify-message.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-3.6.1.min.js') }}"></script>
@endpush
@push('action_scripts')
    {{-- modal event --}}
    <script>
        window.addEventListener('show_modal', event => {
            $('#modal_' + event.detail.modal).modal("show")
        })

        window.addEventListener('close_modal', event => {
            setTimeout(function() {
                $('#modal_' + event.detail.modal).modal("hide")
            }, 50)
            $('#modal_' + event.detail.modal).on('hidden.bs.modal', function() {
                livewire.emit('clear')
                $(this).find('form').trigger('reset')
            })
        })
    </script>

    {{-- When modal is closed --}}
    <script>
        $('#modal_create').on('hidden.bs.modal', function() {
            livewire.emit('clear')
            $(this).find('form').trigger('reset')
        })
        $('#modal_edit').on('hidden.bs.modal', function() {
            livewire.emit('clear')
            $(this).find('form').trigger('reset')
        })
        $('#modal_delete').on('hidden.bs.modal', function() {
            livewire.emit('clear')
            $(this).find('form').trigger('reset')
        })
    </script>

    {{-- toast message from session --}}
    @if (session()->has('toastify_error'))
        <script>
            Toastify({
                text: "{{ session('toastify_error') }}",
                duration: 3000,
                close: true,
                // avatar: 'bi bi-check-lg',
                gravity: "bottom",
                position: "right",
                style: {
                    background: 'linear-gradient(to right, #f85032, #e73827)'
                }
            }).showToast()
        </script>
    @endif
@endpush
