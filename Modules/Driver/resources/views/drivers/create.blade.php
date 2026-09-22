@php
    $locale = app()->getLocale();
@endphp
@extends('common::layouts.master')

@section('title', __('driver::general.create'))

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/vendors/css/forms/select/select2.min.css') }}">
@endsection

@section('content')
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('driver::general.create') }}</h4>
            </div>
            <div class="card-body">
                <form class="form form-horizontal" action="{{ route('admin.driver.store') }}" method="POST"
                    enctype="multipart/form-data">
                    <div class="row">
                        {{ csrf_field() }}

                        {{-- Name --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="name">{{ __('driver::attribute.name') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="text" class="form-control" name="name" id="name" required
                                            placeholder="{{ __('driver::attribute.name') }}" value="{{ old('name') }}" />
                                    </div>
                                    @error('name')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Phone --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="phone">{{ __('driver::attribute.phone') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="phone"></i></span>
                                        <input type="text" class="form-control" name="phone" id="phone" required
                                            placeholder="{{ __('driver::attribute.phone') }}"
                                            value="{{ old('phone') }}" />
                                    </div>
                                    @error('phone')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Password --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="password">{{ __('driver::attribute.password') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="lock"></i></span>
                                        <input type="password" class="form-control" name="password" id="password" required
                                            placeholder="{{ __('driver::attribute.password') }}" />
                                    </div>
                                    @error('password')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- License ID --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="license_id">{{ __('driver::attribute.license_id') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="credit-card"></i></span>
                                        <input type="text" class="form-control" name="license_id" id="license_id"
                                            placeholder="{{ __('driver::attribute.license_id') }}"
                                            value="{{ old('license_id') }}" />
                                    </div>
                                    @error('license_id')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Locale --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="locale">{{ __('driver::attribute.locale') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="globe"></i></span>
                                        <select class="form-select" name="locale" id="locale" required>
                                            <option value="en" {{ old('locale') == 'en' ? 'selected' : '' }}>English
                                            </option>
                                            <option value="ar" {{ old('locale') == 'ar' ? 'selected' : '' }}>العربية
                                            </option>
                                        </select>
                                    </div>
                                    @error('locale')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Affiliation Type Radio --}}
                        @php
                            $selectedType = old('entity_type');
                            if (!$selectedType) {
                                if (old('clinic_id')) {
                                    $selectedType = 'clinic';
                                } elseif (old('store_id')) {
                                    $selectedType = 'store';
                                } else {
                                    $selectedType = 'none';
                                }
                            }
                        @endphp
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label
                                        class="col-form-label">{{ __('driver::attribute.affiliation') ?? 'الجهة التابعة' }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="d-flex flex-wrap gap-2 mt-50">
                                        <div class="form-check me-2">
                                            <input class="form-check-input entity-type-radio" type="radio"
                                                name="entity_type" id="entity_type_none" value="none"
                                                {{ $selectedType === 'none' ? 'checked' : '' }}>
                                            <label class="form-check-label"
                                                for="entity_type_none">{{ __('driver::attribute.none') ?? 'لا يوجد' }}</label>
                                        </div>
                                        <div class="form-check me-2">
                                            <input class="form-check-input entity-type-radio" type="radio"
                                                name="entity_type" id="entity_type_store" value="store"
                                                {{ $selectedType === 'store' ? 'checked' : '' }}>
                                            <label class="form-check-label"
                                                for="entity_type_store">{{ __('driver::attribute.store_id') }}</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input entity-type-radio" type="radio"
                                                name="entity_type" id="entity_type_clinic" value="clinic"
                                                {{ $selectedType === 'clinic' ? 'checked' : '' }}>
                                            <label class="form-check-label"
                                                for="entity_type_clinic">{{ __('driver::attribute.clinic_id') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Store --}}
                        <div class="col-12" id="store_wrapper"
                            style="{{ $selectedType === 'store' ? '' : 'display: none;' }}">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="store_id">{{ __('driver::attribute.store_id') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <select class="form-select select2" name="store_id" id="store_id">
                                        <option value="">{{ __('driver::attribute.select_store') }}</option>
                                        @foreach ($viewModel->stores() as $store)
                                            <option value="{{ $store->id }}"
                                                {{ old('store_id') == $store->id ? 'selected' : '' }}>
                                                {{ $store->getTranslation('title', $locale) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('store_id')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Clinic --}}
                        <div class="col-12" id="clinic_wrapper"
                            style="{{ $selectedType === 'clinic' ? '' : 'display: none;' }}">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="clinic_id">{{ __('driver::attribute.clinic_id') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <select class="form-select select2" name="clinic_id" id="clinic_id">
                                        <option value="">{{ __('driver::attribute.select_clinic') }}</option>
                                        @foreach ($viewModel->clinics() as $clinic)
                                            <option value="{{ $clinic->id }}"
                                                {{ old('clinic_id') == $clinic->id ? 'selected' : '' }}>
                                                {{ $clinic->getTranslation('title', $locale) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('clinic_id')
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
                                        for="country_id">{{ __('driver::attribute.country_id') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <select class="form-select select2" name="country_id" id="country_id"
                                        data-fetch-url="{{ route('admin.ajax.cities') }}" data-ajax-col="country_id"
                                        data-ajax-target="#city_id" data-ajax-child="#zone_id">
                                        <option value="">{{ __('driver::attribute.select_country') }}</option>
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
                                        for="city_id">{{ __('driver::attribute.city_id') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <select class="form-select select2" name="city_id" id="city_id" disabled
                                        data-fetch-url="{{ route('admin.ajax.zones') }}" data-ajax-col="city_id"
                                        data-ajax-target="#zone_id">
                                        <option value="">{{ __('driver::attribute.select_city') }}</option>
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
                                        for="zone_id">{{ __('driver::attribute.zone_id') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <select class="form-select select2" name="zone_id" id="zone_id" disabled>
                                        <option value="">{{ __('driver::attribute.select_zone') }}</option>
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
                                    <label
                                        class="col-form-label">{{ __('common::general.location_on_map') ?? 'الموقع على الخريطة' }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group mb-1">
                                        <span class="input-group-text"><i data-feather="search"></i></span>
                                        <input type="text" id="map-search" class="form-control"
                                            placeholder="{{ __('common::general.search_location') ?? 'ابحث عن موقع أو عنوان...' }}" />
                                    </div>
                                    <div id="google-map-picker" class="map-picker-container rounded border"
                                        data-google-map-picker data-lat-input="#latitude" data-lng-input="#longitude"
                                        data-search-input="#map-search"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Latitude --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="latitude">{{ __('driver::attribute.latitude') ?? 'خط العرض' }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="map-pin"></i></span>
                                        <input type="text" id="latitude" class="form-control" name="latitude"
                                            placeholder="{{ __('driver::attribute.latitude') ?? 'خط العرض' }}"
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
                                        for="longitude">{{ __('driver::attribute.longitude') ?? 'خط الطول' }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="map-pin"></i></span>
                                        <input type="text" id="longitude" class="form-control" name="longitude"
                                            placeholder="{{ __('driver::attribute.longitude') ?? 'خط الطول' }}"
                                            value="{{ old('longitude') }}" readonly />
                                    </div>
                                    @error('longitude')
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
                                        for="image">{{ __('driver::attribute.image') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="image"></i></span>
                                        <input type="file" id="image" class="form-control" name="image"
                                            accept="image/*" />
                                    </div>
                                    @error('image')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- is_available --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="is_available">{{ __('driver::attribute.is_available') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" name="is_available"
                                            id="is_available" value="1"
                                            {{ old('is_available') ? 'checked' : '' }} />
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- is_active --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="is_active">{{ __('driver::attribute.is_active') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" name="is_active" id="is_active"
                                            value="1" {{ old('is_active') ? 'checked' : '' }} />
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Buttons --}}
                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit"
                                class="btn btn-primary me-1">{{ __('driver::general.create') }}</button>
                            <a href="{{ route('admin.driver.index') }}"
                                class="btn btn-outline-secondary">{{ __('driver::general.cancel') }}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('admin/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ asset('admin/js/scripts/forms/form-select2.js') }}"></script>
    <script>
        window.googleMapKey = "{{ config('app.google_map_key', '') }}";
    </script>
    <script src="{{ asset('admin/js/scripts/maps/google-map-picker.js') }}"></script>
    <script>
        $(document).ready(function() {
            function toggleAffiliation(type, resetValue = true) {
                if (type === 'store') {
                    $('#clinic_wrapper').hide();
                    if (resetValue) {
                        $('#clinic_id').val('').trigger('change');
                    }
                    $('#clinic_id').prop('disabled', true);

                    $('#store_wrapper').show();
                    $('#store_id').prop('disabled', false);
                } else if (type === 'clinic') {
                    $('#store_wrapper').hide();
                    if (resetValue) {
                        $('#store_id').val('').trigger('change');
                    }
                    $('#store_id').prop('disabled', true);

                    $('#clinic_wrapper').show();
                    $('#clinic_id').prop('disabled', false);
                } else {
                    $('#store_wrapper').hide();
                    if (resetValue) {
                        $('#store_id').val('').trigger('change');
                    }
                    $('#store_id').prop('disabled', true);

                    $('#clinic_wrapper').hide();
                    if (resetValue) {
                        $('#clinic_id').val('').trigger('change');
                    }
                    $('#clinic_id').prop('disabled', true);
                }
            }

            $('.entity-type-radio').on('change', function() {
                toggleAffiliation($(this).val(), true);
            });

            // Initialize state on page load
            var initialType = $('.entity-type-radio:checked').val() || 'none';
            toggleAffiliation(initialType, false);
        });
    </script>
@endsection
