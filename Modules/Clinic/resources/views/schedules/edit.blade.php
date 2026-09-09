@php
    $locale = app()->getLocale();
    $serviceTitle = $clinicService->service ? $clinicService->service->getTranslation('title', $locale) : '-';
    $days = [
        'saturday'  => __('clinic::general.schedule.days.saturday'),
        'sunday'    => __('clinic::general.schedule.days.sunday'),
        'monday'    => __('clinic::general.schedule.days.monday'),
        'tuesday'   => __('clinic::general.schedule.days.tuesday'),
        'wednesday' => __('clinic::general.schedule.days.wednesday'),
        'thursday'  => __('clinic::general.schedule.days.thursday'),
        'friday'    => __('clinic::general.schedule.days.friday'),
    ];

    $initialTimes = old('times') ?? $schedule->times->map(fn($time) => [
        'id'       => $time->id,
        'from'     => substr($time->from, 0, 5),
        'to'       => substr($time->to, 0, 5),
        'capacity' => $time->capacity,
    ])->toArray();
@endphp
@extends('common::layouts.master')

@section('title', __('clinic::general.schedule.edit'))

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/vendors/css/forms/select/select2.min.css') }}">
@endsection

@section('content')
    <!-- Edit Schedule Form -->
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('clinic::general.schedule.details') }}</h4>
            </div>
            <div class="card-body">
                <form class="form form-horizontal" action="{{ route('admin.clinic.services.schedules.update', [$clinic->id, $clinicService->id, $schedule->id]) }}" method="POST">
                    {{ method_field('PUT') }}
                    {{ csrf_field() }}
                    <div class="row">

                        {{-- Day Selection --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="daySelect">
                                        {{ __('clinic::general.day') }} <span class="text-danger">*</span>
                                    </label>
                                </div>
                                <div class="col-sm-9">
                                    <select class="form-select select2 @error('day') is-invalid @enderror" id="daySelect" name="day" required>
                                        @foreach ($days as $dayKey => $dayLabel)
                                            <option value="{{ $dayKey }}" {{ (old('day', $schedule->day) === $dayKey) ? 'selected' : '' }}>
                                                {{ $dayLabel }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('day')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Working Shifts (Vuexy Form Repeater) --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label">
                                        {{ __('clinic::general.schedule.shifts') }} <span class="text-danger">*</span>
                                    </label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="repeater-default">
                                        <div data-repeater-list="times">
                                            @if(!empty($initialTimes))
                                                @foreach($initialTimes as $time)
                                                    <div data-repeater-item class="row mb-1 align-items-center">
                                                        <input type="hidden" name="id" value="{{ $time['id'] ?? '' }}" />
                                                        <div class="col-md-4 col-4">
                                                            <label class="form-label"><small>{{ __('clinic::general.schedule.from') }}</small></label>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i data-feather="clock"></i></span>
                                                                <input type="time" name="from" class="form-control" value="{{ $time['from'] ?? '' }}" required />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 col-4">
                                                            <label class="form-label"><small>{{ __('clinic::general.schedule.to') }}</small></label>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i data-feather="clock"></i></span>
                                                                <input type="time" name="to" class="form-control" value="{{ $time['to'] ?? '' }}" required />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-3">
                                                            <label class="form-label"><small>{{ __('clinic::general.schedule.capacity') }}</small></label>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i data-feather="users"></i></span>
                                                                <input type="number" name="capacity" class="form-control" value="{{ $time['capacity'] ?? '' }}" />
                                                            </div>
                                                            <small class="text-muted d-block">{{ __('clinic::general.schedule.capacity_tip') }}</small>
                                                        </div>
                                                        <div class="col-md-1 col-1 text-end pt-2">
                                                            <button type="button" class="btn btn-outline-danger btn-icon" data-repeater-delete title="{{ __('common::general.delete') }}">
                                                                <i data-feather="trash-2"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div data-repeater-item class="row mb-1 align-items-center">
                                                    <input type="hidden" name="id" value="" />
                                                    <div class="col-md-4 col-4">
                                                        <label class="form-label"><small>{{ __('clinic::general.schedule.from') }}</small></label>
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i data-feather="clock"></i></span>
                                                            <input type="time" name="from" class="form-control" value="09:00" required />
                                                        </div>
                                                    </div>
                                                        <div class="col-md-4 col-4">
                                                            <label class="form-label"><small>{{ __('clinic::general.schedule.to') }}</small></label>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i data-feather="clock"></i></span>
                                                                <input type="time" name="to" class="form-control" value="17:00" required />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-3">
                                                            <label class="form-label"><small>{{ __('clinic::general.schedule.capacity') }}</small></label>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i data-feather="users"></i></span>
                                                                <input type="number" name="capacity" class="form-control" />
                                                            </div>
                                                            <small class="text-muted d-block">{{ __('clinic::general.schedule.capacity_tip') }}</small>
                                                        </div>
                                                    <div class="col-md-1 col-1 text-end pt-2">
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
                                                    <i data-feather="plus" class="me-50"></i> {{ __('clinic::general.schedule.add_shift') }}
                                                </button>
                                                <small class="text-muted ms-1">{{ __('clinic::general.schedule.help') }}</small>
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
                            <button type="submit" class="btn btn-primary me-1"><i data-feather="edit" class="me-50"></i>{{ __('common::general.save') }}</button>
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
                            text: '{{ __('clinic::general.schedule.min') }}',
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
