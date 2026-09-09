@php
    $locale = app()->getLocale();
    $days = [
        'saturday'  => __('clinic::general.schedule.days.saturday'),
        'sunday'    => __('clinic::general.schedule.days.sunday'),
        'monday'    => __('clinic::general.schedule.days.monday'),
        'tuesday'   => __('clinic::general.schedule.days.tuesday'),
        'wednesday' => __('clinic::general.schedule.days.wednesday'),
        'thursday'  => __('clinic::general.schedule.days.thursday'),
        'friday'    => __('clinic::general.schedule.days.friday'),
    ];
    $existingDays = $schedules->pluck('day')->toArray();
@endphp
@extends('common::layouts.master')

@section('title', __('clinic::general.delivery_schedule.add'))

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/vendors/css/forms/select/select2.min.css') }}">
@endsection

@section('content')
    <!-- Create Delivery Schedule Form -->
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('clinic::general.delivery_schedule.details') }} - {{ $clinic->getTranslation('title', $locale) }}</h4>
            </div>
            <div class="card-body">
                <form class="form form-horizontal" action="{{ route('admin.clinic.delivery-schedules.store', $clinic->id) }}" method="POST">
                    {{ csrf_field() }}
                    <div class="row">

                        {{-- Day Selection --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="daySelect">
                                        {{ __('clinic::general.delivery_schedule.select') }} <span class="text-danger">*</span>
                                    </label>
                                </div>
                                <div class="col-sm-9">
                                    <select class="form-select select2 @error('day') is-invalid @enderror" id="daySelect" name="day" required>
                                        <option value="" disabled selected>{{ __('clinic::general.delivery_schedule.select') }}</option>
                                        @foreach ($days as $dayKey => $dayLabel)
                                            @php
                                                $isAlreadyAdded = in_array($dayKey, $existingDays);
                                            @endphp
                                            <option value="{{ $dayKey }}" {{ $isAlreadyAdded ? 'disabled' : '' }} {{ old('day') === $dayKey ? 'selected' : '' }}>
                                                {{ $dayLabel }} {{ $isAlreadyAdded ? '(' . __('clinic::general.delivery_schedule.exists') . ')' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('day')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Delivery Shifts (Vuexy Form Repeater) --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label">
                                        {{ __('clinic::general.delivery_schedule.shifts') }} <span class="text-danger">*</span>
                                    </label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="repeater-default">
                                        <div data-repeater-list="times">
                                            @if(old('times'))
                                                @foreach(old('times') as $time)
                                                    <div data-repeater-item class="row mb-1 align-items-center">
                                                        <div class="col-md-5 col-5">
                                                            <label class="form-label"><small>{{ __('clinic::general.from') }}</small></label>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i data-feather="clock"></i></span>
                                                                <input type="time" name="from" class="form-control" value="{{ $time['from'] ?? '' }}" required />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-5 col-5">
                                                            <label class="form-label"><small>{{ __('clinic::general.to') }}</small></label>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i data-feather="clock"></i></span>
                                                                <input type="time" name="to" class="form-control" value="{{ $time['to'] ?? '' }}" required />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2 col-2 text-end pt-2">
                                                            <button type="button" class="btn btn-outline-danger btn-icon" data-repeater-delete title="{{ __('common::general.delete') }}">
                                                                <i data-feather="trash-2"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div data-repeater-item class="row mb-1 align-items-center">
                                                    <div class="col-md-5 col-5">
                                                        <label class="form-label"><small>{{ __('clinic::general.from') }}</small></label>
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i data-feather="clock"></i></span>
                                                            <input type="time" name="from" class="form-control" value="09:00" required />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5 col-5">
                                                        <label class="form-label"><small>{{ __('clinic::general.to') }}</small></label>
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i data-feather="clock"></i></span>
                                                            <input type="time" name="to" class="form-control" value="17:00" required />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 col-2 text-end pt-2">
                                                        <button type="button" class="btn btn-outline-danger btn-icon" data-repeater-delete title="{{ __('common::general.delete') }}">
                                                            <i data-feather="trash-2"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <button type="button" class="btn btn-sm btn-outline-primary" data-repeater-create>
                                                    <i data-feather="plus" class="me-50"></i> {{ __('clinic::general.delivery_schedule.add_shift') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @error('times')
                                        <p class="text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit" class="btn btn-primary me-1"><i data-feather="plus" class="me-50"></i>{{ __('clinic::general.delivery_schedule.add') }}</button>
                            <a href="{{ route('admin.clinic.delivery-schedules.index', $clinic->id) }}" class="btn btn-outline-secondary">{{ __('clinic::general.cancel') }}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('admin/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/js/forms/repeater/jquery.repeater.min.js') }}"></script>
    <script>
        $(function () {
            'use strict';

            // Select2
            var select = $('.select2');
            if (select.length) {
                select.each(function () {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>');
                    $this.select2({ dropdownParent: $this.parent() });
                });
            }

            // Vuexy Repeater
            $('.repeater-default').repeater({
                show: function () {
                    $(this).slideDown();
                    if (feather) {
                        feather.replace({ width: 14, height: 14 });
                    }
                },
                hide: function (deleteElement) {
                    if ($('[data-repeater-item]').length > 1) {
                        $(this).slideUp(deleteElement);
                    } else {
                        Swal.fire({
                            text: '{{ __('clinic::general.delivery_schedule.min') }}',
                            icon: 'info',
                            customClass: { confirmButton: 'btn btn-primary' },
                            buttonsStyling: false
                        });
                    }
                },
                isFirstItemUndeletable: false
            });
        });
    </script>
@endsection
