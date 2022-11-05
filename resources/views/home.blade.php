@extends('layouts.master')
@section('title', 'Login Page')
@section('content')

    {{-- Alert --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" id="message_alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <div class="alert-message">
                <h4 class="alert-heading">{{ session('response')->message }}</h4>
                <p>{{ session('success') }}</p>
            </div>
        </div>
    @endif

    {{-- Show Form, Add Button & Search Form --}}
    <div class="row mx-auto g-0">
        {{-- Add Button --}}
        <div class="col mb-2">
            <button class=" btn btn-primary" type="button" id="create">New data</button>
        </div>
        {{-- Show Select --}}
        <div class="col-lg-1 col-md-2 mb-2 me-lg-2 me-md-2 me-sm-0">
            <form method="GET">
                <select class="form-select form-control" name="page_size" onchange='this.form.submit()'>
                    @php
                        $page_size_options = ['10', '25', '50', '100'];
                    @endphp
                    @foreach ($page_size_options as $option)
                        <option value="{{ $option }}" @if (request('page_size') == $option) selected @endif>
                            {{ $option }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        {{-- Search --}}
        <div class="col mb-2">
            <form method="GET">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Search..." name="s"
                        value="{{ request('s') }}">
                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <table class="table table-sm table-striped align-middle">
        <thead class="table-light">
            <tr>
                <th scope="col">#</th>
                @if (isset($response->data[0]))
                    @foreach ($response->data[0] as $key => $value)
                        @if ($key == 'id')
                            @continue
                        @endif
                        <th scope="col">
                            {{-- Header --}}
                            {{ ucwords(str_replace('_', ' ', strtolower(preg_replace('/(?<!\ )[A-Z]/', " $0", $key)))) }}
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
                    <td>
                        <button type="button" class="btn btn-warning" id="edit"
                            @foreach ($data as $key => $value) data-{{ $key }}="{{ $value }}" @endforeach>Edit
                        </button>
                        <button type="button" class="btn btn-danger" id="delete"
                            @foreach ($data as $key => $value) data-{{ $key }}="{{ $value }}" @endforeach>Delete
                        </button>
                    </td>
                </tr>
            @empty
                <tr class="text-center">
                    <td colspan="100">
                        <p>No Data.</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- paginating --}}
    <div class="row mx-auto g-0">
        <div class="col-lg-4 mb-2">
            Showing {{ $response->meta->from ?? 0 }} to {{ $response->meta->to }} of {{ $response->meta->total }} entries
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
                            <a class="page-link" disabled href="{!! str_replace($response->meta->path, request()->url(), $link->url) !!}" aria-label="{{ $link->label }}">
                                <span aria-hidden="true">{!! $link->label !!}</span>
                            </a>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- Modal --}}
    {{-- Modal Create --}}
    <div class="modal fade" id="modal_create" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">&nbsp;Add New Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="form_create">
                        @csrf

                        <div class="form-floating mb-3">
                            <input type="text" name="name"
                                class="form-control @if (isset(session('response')->errors->name[0])) is-invalid @endif" placeholder="Name"
                                required value="{{ old('name') }}">
                            <label>Name</label>
                            @if (isset(session('response')->errors->name[0]))
                                <div class="invalid-feedback">
                                    {{ session('response')->errors->name[0] }}
                                </div>
                            @endif
                        </div>

                        <div class="form-floating mb-3">
                            <input type="number" name="price" min="1"
                                class="form-control @if (isset(session('response')->errors->price[0])) is-invalid @endif"
                                placeholder="Price" required value="{{ old('price') }}">
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
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-bg-warning bg-opacity-75">
                    <h5 class="modal-title">Edit Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form method="POST" id="form_edit">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" readonly value="{{ old('id') }}">

                        <div class="form-floating mb-3">
                            <input type="text" name="name"
                                class="form-control @if (isset(session('response')->errors->name[0])) is-invalid @endif"
                                placeholder="Name" required value="{{ old('name') }}">
                            <label>Name</label>
                            @if (isset(session('response')->errors->name[0]))
                                <div class="invalid-feedback">
                                    {{ session('response')->errors->name[0] }}
                                </div>
                            @endif
                        </div>

                        <div class="form-floating mb-3">
                            <input type="number" name="price" min="1"
                                class="form-control @if (isset(session('response')->errors->price[0])) is-invalid @endif"
                                placeholder="Price" required value="{{ old('price') }}">
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
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-bg-danger bg-opacity-75">
                    <h5 class="modal-title">Delete Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form action="home/delete" method="POST" id="form_delete">
                        @csrf
                        @method('DELETE')
                        <div class="row">
                            <input type="hidden" name="id" readonly>
                            <span>
                                <h4>Are you sure?</h4>
                                Do you want to delete:
                                <div id="delete_name" class="text-bold"></div>
                            </span>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('assets/js/jquery-3.6.1.min.js') }}"></script>
    @endpush
    @push('action_scripts')
        {{-- Modal Trigger --}}
        <script>
            $(document).on('click', '#create', function(e) {
                e.preventDefault()
                $('#form_create').attr('action', 'home');
                $('#modal_create').modal('show')
                $('#modal_create').on('shown.bs.modal', function() {
                    $('#form_create input[name="name"]').focus()
                })
            })

            $(document).on('click', '#edit', function(e) {
                e.preventDefault()
                let id = $(this).data('id')
                $('#form_edit').find('input[name="id"]').val($(this).data('id'))
                $('#form_edit').find('input[name="name"]').val($(this).data('name'))
                $('#form_edit').find('input[name="price"]').val($(this).data('price'))
                $('#form_edit').attr('action', 'home/' + id)
                $('#modal_edit').modal('show')
                $('#modal_edit').on('shown.bs.modal', function() {
                    $('#form_edit input[name="name"]').focus()
                })
            })

            $(document).on('click', '#delete', function(e) {
                e.preventDefault()
                $('#form_delete').find('input[name="id"]').val($(this).data('id'))
                $('#delete_name').text($(this).data('name'))
                $('#modal_delete').modal('show')
            })
        </script>

        {{-- Close Action --}}
        <script>
            $(document).ready(function() {
                $('#modal_create').on('hidden.bs.modal', function() {
                    $('#form_create').find('.is-invalid').removeClass('is-invalid')
                    $('#form_create').find('.invalid-feedback').html('')
                    $('#form_create').trigger('reset')
                })
                $('#modal_edit').on('hidden.bs.modal', function() {
                    $('#form_edit').find('.is-invalid').removeClass('is-invalid')
                    $('#form_edit').find('.invalid-feedback').html('')
                    $('#form_edit').trigger('reset')
                })
                $('#modal_delete').on('hidden.bs.modal', function() {
                    $('#form_delete').trigger('reset')
                })
            });
        </script>

        {{-- open the form automatically if any input is error --}}
        @if (session()->has('model'))
            <script>
                $(window).on('load', function() {
                    $('#modal_{{ session('model') }}').modal('show');

                    var attr = $('#form_{{ session('model') }}').find('input[name="id"]').val()

                    if (typeof attr !== 'undefined' && attr !== false) {
                        $('#form_{{ session('model') }}').attr('action', 'home/' + attr)
                    } else {
                        $('#form_{{ session('model') }}').attr('action', 'home')
                    }
                });
            </script>
        @endif

    @endpush

@endsection
