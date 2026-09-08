@php
    $locale = app()->getLocale();
    $days = [
        'saturday'  => __('clinic::general.days.saturday'),
        'sunday'    => __('clinic::general.days.sunday'),
        'monday'    => __('clinic::general.days.monday'),
        'tuesday'   => __('clinic::general.days.tuesday'),
        'wednesday' => __('clinic::general.days.wednesday'),
        'thursday'  => __('clinic::general.days.thursday'),
        'friday'    => __('clinic::general.days.friday'),
    ];
    $initialWorkingHours = old('working_hours') ?? [];
@endphp
@extends('common::layouts.master')

@section('title', __('clinic::general.create'))

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('admin/vendors/css/forms/select/select2.min.css')}}">
@endsection

@section('content')
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('clinic::general.create') }}</h4>
            </div>
            <div class="card-body">
                <form class="form form-horizontal" action="{{ route('admin.clinic.store') }}" method="POST"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        {{-- Title ar --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="title_ar">{{ __('clinic::attribute.title_ar') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="text" class="form-control" name="title_ar" required
                                            placeholder="{{ __('clinic::attribute.title_ar') }}"
                                            value="{{ old('title_ar') }}" />
                                    </div>
                                    @error('title_ar')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Title en --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="title_en">{{ __('clinic::attribute.title_en') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="text" class="form-control" name="title_en" required
                                            placeholder="{{ __('clinic::attribute.title_en') }}"
                                            value="{{ old('title_en') }}" />
                                    </div>
                                    @error('title_en')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Description ar --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="description_ar">{{ __('clinic::attribute.description_ar') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="file-text"></i></span>
                                        <textarea class="form-control" name="description_ar" placeholder="{{ __('clinic::attribute.description_ar') }}">{{ old('description_ar') }}</textarea>
                                    </div>
                                    @error('description_ar')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Description en --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="description_en">{{ __('clinic::attribute.description_en') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="file-text"></i></span>
                                        <textarea class="form-control" name="description_en" placeholder="{{ __('clinic::attribute.description_en') }}">{{ old('description_en') }}</textarea>
                                    </div>
                                    @error('description_en')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Address ar --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="address_ar">{{ __('clinic::attribute.address_ar') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="map-pin"></i></span>
                                        <textarea class="form-control" name="address_ar" placeholder="{{ __('clinic::attribute.address_ar') }}">{{ old('address_ar') }}</textarea>
                                    </div>
                                    @error('address_ar')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Address en --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="address_en">{{ __('clinic::attribute.address_en') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="map-pin"></i></span>
                                        <textarea class="form-control" name="address_en" placeholder="{{ __('clinic::attribute.address_en') }}">{{ old('address_en') }}</textarea>
                                    </div>
                                    @error('address_en')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Phone --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="phone">{{ __('clinic::attribute.phone') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="phone"></i></span>
                                        <input type="text" class="form-control" name="phone"
                                            placeholder="{{ __('clinic::attribute.phone') }}"
                                            value="{{ old('phone') }}" />
                                    </div>
                                    @error('phone')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Image --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="image">{{ __('clinic::attribute.image') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="image"></i></span>
                                        <input type="file" id="image" class="form-control" name="image"
                                            placeholder="{{ __('clinic::attribute.image') }}" accept="image/*" />
                                    </div>
                                    @error('image')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Country --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="country_id">{{ __('clinic::attribute.country_id') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <select class="form-select select2" name="country_id" id="country_id" required
                                        data-fetch-url="{{ route('admin.ajax.cities') }}"
                                        data-ajax-col="country_id"
                                        data-ajax-target="#city_id"
                                        data-ajax-child="#zone_id">
                                        <option value="" disabled selected>{{ __('clinic::attribute.select_country') }}</option>
                                        @foreach ($viewModel->countries() as $country)
                                            <option value="{{ $country->id }}"
                                                {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                                {{ $country->getTranslation('title', $locale) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('country_id')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- City --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="city_id">{{ __('clinic::attribute.city_id') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <select class="form-select select2" name="city_id" id="city_id" required disabled
                                        data-fetch-url="{{ route('admin.ajax.zones') }}"
                                        data-ajax-col="city_id"
                                        data-ajax-target="#zone_id">
                                        <option value="" disabled selected>{{ __('clinic::attribute.select_city') }}</option>
                                    </select>
                                    @error('city_id')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Zone --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="zone_id">{{ __('clinic::attribute.zone_id') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <select class="form-select select2" name="zone_id" id="zone_id" required disabled>
                                        <option value="" disabled selected>{{ __('clinic::attribute.select_zone') }}</option>
                                    </select>
                                    @error('zone_id')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Location on Map --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label">{{ __('common::general.location_on_map') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group mb-1">
                                        <span class="input-group-text"><i data-feather="search"></i></span>
                                        <input type="text" id="map-search" class="form-control"
                                            placeholder="{{ __('common::general.search_location') }}" />
                                    </div>
                                    <div id="google-map-picker" class="map-picker-container rounded border"
                                        data-google-map-picker
                                        data-lat-input="#latitude"
                                        data-lng-input="#longitude"
                                        data-search-input="#map-search"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Latitude --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="latitude">{{ __('clinic::attribute.latitude') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="map-pin"></i></span>
                                        <input type="text" id="latitude" class="form-control" name="latitude"
                                            placeholder="{{ __('clinic::attribute.latitude') }}"
                                            value="{{ old('latitude') }}" readonly />
                                    </div>
                                    @error('latitude')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Longitude --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="longitude">{{ __('clinic::attribute.longitude') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="map-pin"></i></span>
                                        <input type="text" id="longitude" class="form-control" name="longitude"
                                            placeholder="{{ __('clinic::attribute.longitude') }}"
                                            value="{{ old('longitude') }}" readonly />
                                    </div>
                                    @error('longitude')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Working Hours (Vuexy Form Repeater) --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label">
                                        {{ __('clinic::general.working_hours') }}
                                    </label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="working-hours-repeater">
                                        <div data-repeater-list="working_hours">
                                            @if(!empty($initialWorkingHours))
                                                @foreach($initialWorkingHours as $item)
                                                    <div data-repeater-item class="row mb-1 align-items-center">
                                                        <div class="col-md-3 col-12 mb-50">
                                                            <label class="form-label"><small>{{ __('clinic::general.day') }}</small></label>
                                                            <select class="form-select" name="day" required>
                                                                <option value="" disabled {{ empty($item['day']) ? 'selected' : '' }}>{{ __('clinic::general.select_day') }}</option>
                                                                @foreach ($days as $dayKey => $dayLabel)
                                                                    <option value="{{ $dayKey }}" {{ ($item['day'] ?? '') === $dayKey ? 'selected' : '' }}>
                                                                        {{ $dayLabel }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3 col-6 mb-50">
                                                            <label class="form-label"><small>{{ __('clinic::general.from') }}</small></label>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i data-feather="clock"></i></span>
                                                                <input type="time" name="from" class="form-control time-input" value="{{ $item['from'] ?? '' }}" {{ !empty($item['is_open_24_hours']) ? 'disabled' : '' }} />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-6 mb-50">
                                                            <label class="form-label"><small>{{ __('clinic::general.to') }}</small></label>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i data-feather="clock"></i></span>
                                                                <input type="time" name="to" class="form-control time-input" value="{{ $item['to'] ?? '' }}" {{ !empty($item['is_open_24_hours']) ? 'disabled' : '' }} />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2 col-8 mb-50">
                                                            <div class="form-check mt-2">
                                                                <input type="checkbox" name="is_open_24_hours" value="1" class="form-check-input open-24-hours-check" {{ !empty($item['is_open_24_hours']) ? 'checked' : '' }} />
                                                                <label class="form-check-label"><small>{{ __('clinic::general.open_24_hours') }}</small></label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1 col-4 mb-50 text-end pt-2">
                                                            <button type="button" class="btn btn-outline-danger btn-icon" data-repeater-delete title="{{ __('common::general.delete') }}">
                                                                <i data-feather="trash-2"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div data-repeater-item class="row mb-1 align-items-center">
                                                    <div class="col-md-3 col-12 mb-50">
                                                        <label class="form-label"><small>{{ __('clinic::general.day') }}</small></label>
                                                        <select class="form-select" name="day">
                                                            <option value="" disabled selected>{{ __('clinic::general.select_day') }}</option>
                                                            @foreach ($days as $dayKey => $dayLabel)
                                                                <option value="{{ $dayKey }}">{{ $dayLabel }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3 col-6 mb-50">
                                                        <label class="form-label"><small>{{ __('clinic::general.from') }}</small></label>
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i data-feather="clock"></i></span>
                                                            <input type="time" name="from" class="form-control time-input" value="09:00" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 col-6 mb-50">
                                                        <label class="form-label"><small>{{ __('clinic::general.to') }}</small></label>
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i data-feather="clock"></i></span>
                                                            <input type="time" name="to" class="form-control time-input" value="17:00" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 col-8 mb-50">
                                                        <div class="form-check mt-2">
                                                            <input type="checkbox" name="is_open_24_hours" value="1" class="form-check-input open-24-hours-check" />
                                                            <label class="form-check-label"><small>{{ __('clinic::general.open_24_hours') }}</small></label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1 col-4 mb-50 text-end pt-2">
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
                                                    <i data-feather="plus" class="me-50"></i> {{ __('clinic::general.add_working_hours') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @error('working_hours')
                                        <p class="text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Is Active --}}
                        <div class="col-sm-9 offset-sm-3">
                            <div class="mb-1">
                                <div class="form-check">
                                    <input type="checkbox" value="1" name="is_active" class="form-check-input"
                                        id="customCheck2" />
                                    <label class="form-check-label"
                                        for="customCheck2">{{ __('clinic::attribute.is_active') }}</label>
                                </div>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit"
                                class="btn btn-primary me-1"><i data-feather="plus" class="me-50"></i>{{ __('clinic::general.create') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{asset('admin/vendors/js/forms/select/select2.full.min.js')}}"></script>
    <script src="{{asset('admin/vendors/js/forms/repeater/jquery.repeater.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            var select = $('.select2');
            if (select.length) {
                select.each(function () {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>');
                    $this.select2({
                        dropdownParent: $this.parent()
                    });
                });
            }

            // Working Hours Repeater
            $('.working-hours-repeater').repeater({
                show: function () {
                    $(this).slideDown();
                    $(this).find('.time-input').prop('disabled', false);
                    $(this).find('.open-24-hours-check').prop('checked', false);
                    if (feather) {
                        feather.replace({ width: 14, height: 14 });
                    }
                },
                hide: function (deleteElement) {
                    $(this).slideUp(deleteElement);
                },
                isFirstItemUndeletable: false
            });

            $(document).on('change', '.open-24-hours-check', function () {
                var isChecked = $(this).is(':checked');
                var $row = $(this).closest('[data-repeater-item]');
                var $timeInputs = $row.find('.time-input');
                if (isChecked) {
                    $timeInputs.val('').prop('disabled', true);
                } else {
                    $timeInputs.prop('disabled', false);
                }
            });
        });
    </script>
@endsection
