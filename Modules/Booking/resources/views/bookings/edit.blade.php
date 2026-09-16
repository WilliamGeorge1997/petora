@php
    $locale = app()->getLocale();
@endphp
@extends('common::layouts.master')

@section('title', __('booking::general.booking.edit'))

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/vendors/css/forms/select/select2.min.css') }}">
@endsection

@section('content')
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('booking::general.booking.edit') }} {{ $booking->booking_no ?? $booking->id }}</h4>
            </div>
            <div class="card-body">
                <form class="form form-horizontal" action="{{ route('admin.booking.update', $booking->id) }}" method="POST" enctype="multipart/form-data">
                    {{ method_field('PUT') }}
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="status">{{ __('booking::attribute.status') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group-merge">
                                        <select name="booking_status_id" id="status" class="select2 form-select select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                            @foreach($viewModel->bookingStatuses() as $status)
                                                <option @if ($booking->booking_status_id == $status->id) selected @endif value="{{ $status->id }}">
                                                    {{ $status->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('booking_status_id')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="fname-icon">{{ __('booking::attribute.notes') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="text" class="form-control" name="notes" value="{{ old('notes', $booking->notes) }}" placeholder="Note" />
                                        @error('notes')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit" class="btn btn-primary me-1">{{ __('common::general.submit') }}</button>
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
    </script>
@endsection
