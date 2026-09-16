@extends('common::layouts.master')

@section('title', __('order::general.edit'))

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/vendors/css/forms/select/select2.min.css') }}">
@endsection

@section('content')
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Update data of order no {{ $order->order_no ?? $order->id }}</h4>
            </div>
            <div class="card-body">
                <form class="form form-horizontal" action="{{ route('admin.order.update', $order->id) }}" method="POST" enctype="multipart/form-data">
                    {{ method_field('PUT') }}
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="status">Order status</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group-merge">
                                        <select name="order_status_id" id="status" class="select2 form-select select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                            @foreach($viewModel->orderStatuses() as $status)
                                                <option @if ($order->order_status_id == $status->id) selected @endif value="{{ $status->id }}">
                                                    {{ $status->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('order_status_id')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12" id="driver">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="select2">Driver</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group-merge">
                                        <select name="driver_id" class="select2 form-select select2-hidden-accessible" id="select2" data-select2-id="select2" tabindex="-1" aria-hidden="true">
                                            <option value="">{{ __('order::general.select_driver') }}</option>
                                            @foreach($viewModel->drivers() as $driver)
                                                <option @if ($order->driver_id == $driver->id) selected @endif value="{{ $driver->id }}">
                                                    {{ $driver->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('driver_id')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="fname-icon">Note</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="text" class="form-control" name="notes" value="{{ old('notes', $order->notes) }}" placeholder="Note" />
                                        @error('notes')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit" class="btn btn-primary me-1">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('admin/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script>
        var select = $('.select2');

        select.each(function () {
            var $this = $(this);
            $this.wrap('<div class="position-relative"></div>');
            $this.select2({
                dropdownAutoWidth: true,
                width: '100%',
                dropdownParent: $this.parent()
            });
        });

        if (document.getElementById('status').value == 3) {
            document.getElementById('driver').style.display = 'block';
        } else {
            document.getElementById('driver').style.display = 'none';
        }

        document.getElementById('status').onchange = function() {
            if (document.getElementById('status').value == 3) {
                document.getElementById('driver').style.display = 'block';
            } else {
                document.getElementById('driver').style.display = 'none';
            }
        };
    </script>
@endsection
