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
                                            placeholder="{{ __('driver::attribute.phone') }}" value="{{ old('phone') }}" />
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
                                    <label class="col-form-label" for="password">{{ __('driver::attribute.password') }}</label>
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
                                    <label class="col-form-label" for="license_id">{{ __('driver::attribute.license_id') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="credit-card"></i></span>
                                        <input type="text" class="form-control" name="license_id" id="license_id"
                                            placeholder="{{ __('driver::attribute.license_id') }}" value="{{ old('license_id') }}" />
                                    </div>
                                    @error('license_id')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Store --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="store_id">{{ __('driver::attribute.store_id') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <select class="form-select select2" name="store_id" id="store_id">
                                        <option value="">{{ __('driver::attribute.select_store') }}</option>
                                        @foreach ($viewModel->stores() as $store)
                                            <option value="{{ $store->id }}" {{ old('store_id') == $store->id ? 'selected' : '' }}>
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
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="clinic_id">{{ __('driver::attribute.clinic_id') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <select class="form-select select2" name="clinic_id" id="clinic_id">
                                        <option value="">{{ __('driver::attribute.select_clinic') }}</option>
                                        @foreach ($viewModel->clinics() as $clinic)
                                            <option value="{{ $clinic->id }}" {{ old('clinic_id') == $clinic->id ? 'selected' : '' }}>
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
                                    <label class="col-form-label" for="country_id">{{ __('driver::attribute.country_id') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <select class="form-select select2" name="country_id" id="country_id"
                                        data-fetch-url="{{ route('admin.ajax.cities') }}" data-ajax-col="country_id"
                                        data-ajax-target="#city_id" data-ajax-child="#zone_id">
                                        <option value="">{{ __('driver::attribute.select_country') }}</option>
                                        @foreach ($viewModel->countries() as $country)
                                            <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
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
                                    <label class="col-form-label" for="city_id">{{ __('driver::attribute.city_id') }}</label>
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
                                    <label class="col-form-label" for="zone_id">{{ __('driver::attribute.zone_id') }}</label>
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

                        {{-- Latitude & Longitude Map Picker --}}
                        <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}" />
                        <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}" />
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label">{{ __('common::general.location_on_map') ?? 'الموقع على الخريطة' }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group mb-1">
                                        <span class="input-group-text"><i data-feather="search"></i></span>
                                        <input type="text" id="map-search" class="form-control"
                                            placeholder="{{ __('common::general.search_location') ?? 'ابحث عن موقع...' }}" />
                                    </div>
                                    <div id="google-map-picker" class="map-picker-container rounded border"
                                        data-google-map-picker data-lat-input="#latitude" data-lng-input="#longitude"
                                        data-search-input="#map-search" style="height: 300px;"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Image --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="image">{{ __('driver::attribute.image') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="image"></i></span>
                                        <input type="file" id="image" class="form-control" name="image" accept="image/*" />
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
                                    <label class="col-form-label" for="is_available">{{ __('driver::attribute.is_available') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" name="is_available" id="is_available"
                                            value="1" {{ old('is_available', '1') == '1' ? 'checked' : '' }} />
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- is_active --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="is_active">{{ __('driver::attribute.is_active') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" name="is_active" id="is_active"
                                            value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }} />
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Buttons --}}
                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit" class="btn btn-primary me-1">{{ __('driver::general.create') }}</button>
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
    @include('common::includes.cascading_dropdowns')
    @include('common::includes.map_picker')
@endsection
