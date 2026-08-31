@php
    $locale = app()->getLocale();
@endphp
@extends('common::layouts.master')
@section('content')
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('store::general.edit_store') }} {{ $store->getTranslation('title', 'ar') }}</h4>
            </div>
            <div class="card-body">
                <form class="form form-horizontal" action="{{ route('admin.store.update', $store->id) }}" method="POST"
                    enctype="multipart/form-data">
                    {{ method_field('PUT') }}
                    {{ csrf_field() }}
                    <div class="row">
                        {{-- Title ar --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="title_ar">{{ __('store::attribute.title_ar') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="text" id="title_ar"
                                            value="{{ $store->getTranslation('title', 'ar') }}" class="form-control"
                                            name="title_ar" placeholder="{{ __('store::attribute.title_ar') }}" required />
                                        @error('title_ar')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Title en --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="title_en">{{ __('store::attribute.title_en') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="text" id="title_en"
                                            value="{{ $store->getTranslation('title', 'en') }}" class="form-control"
                                            name="title_en" placeholder="{{ __('store::attribute.title_en') }}" required />
                                        @error('title_en')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Description ar --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="description_ar">{{ __('store::attribute.description_ar') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="file-text"></i></span>
                                        <textarea class="form-control" name="description_ar" placeholder="{{ __('store::attribute.description_ar') }}">{{ $store->getTranslation('description', 'ar', false) }}</textarea>
                                        @error('description_ar')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Description en --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="description_en">{{ __('store::attribute.description_en') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="file-text"></i></span>
                                        <textarea class="form-control" name="description_en" placeholder="{{ __('store::attribute.description_en') }}">{{ $store->getTranslation('description', 'en', false) }}</textarea>
                                        @error('description_en')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Address ar --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="address_ar">{{ __('store::attribute.address_ar') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="map-pin"></i></span>
                                        <textarea class="form-control" name="address_ar" required placeholder="{{ __('store::attribute.address_ar') }}">{{ $store->getTranslation('address', 'ar') }}</textarea>
                                        @error('address_ar')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Address en --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="address_en">{{ __('store::attribute.address_en') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="map-pin"></i></span>
                                        <textarea class="form-control" name="address_en" required placeholder="{{ __('store::attribute.address_en') }}">{{ $store->getTranslation('address', 'en') }}</textarea>
                                        @error('address_en')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Phone --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="phone">{{ __('store::attribute.phone') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="phone"></i></span>
                                        <input type="text" id="phone" value="{{ $store->phone }}"
                                            class="form-control" name="phone"
                                            placeholder="{{ __('store::attribute.phone') }}" required />
                                        @error('phone')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Company --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="company_id">{{ __('store::attribute.company_id') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <select class="form-select" name="company_id" id="company_id" required>
                                        <option value="">{{ __('store::attribute.select_company') }}</option>
                                        @foreach ($viewModel->companies() as $company)
                                            <option value="{{ $company->id }}"
                                                {{ $store->company_id == $company->id ? 'selected' : '' }}>
                                                {{ $company->getTranslation('title', $locale) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('company_id')
                                        <p class="alert alert-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Image --}}
                        <div class="col-12">
                            <div class="mb-1 row align-items-center">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="image">{{ __('store::attribute.image') }}</label>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="image"></i></span>
                                        <input type="file" id="image" class="form-control" name="image"
                                            placeholder="{{ __('store::attribute.image') }}" accept="image/*" />
                                        @error('image')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                @if ($store->image != null)
                                    <div class="col-sm-3 text-center">
                                        <div class="d-flex justify-content-center align-items-center mt-1">
                                            <img class="img-fluid rounded border" width="100" height="100" src="{{ asset($store->image) }}" alt="{{ $store->getTranslation('title', $locale) }}">
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Country --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="country_id">{{ __('store::attribute.country_id') ?? 'الدولة' }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <select class="form-select" name="country_id" id="country_id" required
                                        data-fetch-url="{{ route('admin.ajax.cities') }}"
                                        data-ajax-col="country_id"
                                        data-ajax-target="#city_id"
                                        data-ajax-child="#zone_id">
                                        <option value="">
                                            {{ __('store::attribute.select_country') ?? 'اختر الدولة' }}</option>
                                        @foreach ($viewModel->countries() as $country)
                                            <option value="{{ $country->id }}"
                                                {{ $store->country_id == $country->id ? 'selected' : '' }}>
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
                                        for="city_id">{{ __('store::attribute.city_id') ?? 'المدينة' }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <select class="form-select" name="city_id" id="city_id" required
                                        data-fetch-url="{{ route('admin.ajax.zones') }}"
                                        data-ajax-col="city_id"
                                        data-ajax-target="#zone_id">
                                        <option value="">{{ __('store::attribute.select_city') ?? 'اختر المدينة' }}</option>
                                        @if($store->city_id)
                                            <option value="{{ $store->city_id }}" selected>
                                                {{ $store->city?->getTranslation('title', $locale) }}
                                            </option>
                                        @endif
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
                                        for="zone_id">{{ __('store::attribute.zone_id') ?? 'المنطقة' }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <select class="form-select" name="zone_id" id="zone_id" required>
                                        <option value="">{{ __('store::attribute.select_zone') ?? 'اختر المنطقة' }}</option>
                                        @if($store->zone_id)
                                            <option value="{{ $store->zone_id }}" selected>
                                                {{ $store->zone?->getTranslation('title', $locale) }}
                                            </option>
                                        @endif
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
                                    <label class="col-form-label">{{ __('common::general.location_on_map') ?? 'الموقع على الخريطة' }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group mb-1">
                                        <span class="input-group-text"><i data-feather="search"></i></span>
                                        <input type="text" id="map-search" class="form-control"
                                            placeholder="{{ __('common::general.search_location') ?? 'ابحث عن موقع أو عنوان...' }}" />
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
                                        for="latitude">{{ __('store::attribute.latitude') ?? 'خط العرض' }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="map-pin"></i></span>
                                        <input type="text" id="latitude" class="form-control" name="latitude"
                                            placeholder="{{ __('store::attribute.latitude') ?? 'خط العرض' }}"
                                            value="{{ $store->latitude }}" />
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
                                        for="longitude">{{ __('store::attribute.longitude') ?? 'خط الطول' }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="map-pin"></i></span>
                                        <input type="text" id="longitude" class="form-control" name="longitude"
                                            placeholder="{{ __('store::attribute.longitude') ?? 'خط الطول' }}"
                                            value="{{ $store->longitude }}" />
                                    </div>
                                    @error('longitude')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Is Active --}}
                        <div class="col-sm-9 offset-sm-3">
                            <div class="mb-1">
                                <div class="form-check">
                                    <input type="checkbox" value="1" @if ($store->is_active == 1) checked @endif
                                        name="is_active" class="form-check-input" id="customCheck2" />
                                    <label class="form-check-label"
                                        for="customCheck2">{{ __('store::attribute.is_active') }}</label>
                                </div>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit"
                                class="btn btn-primary me-1">{{ __('store::general.update') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    {{-- <script src="//cdn.ckeditor.com/4.16.0/full/ckeditor.js"></script> --}}
@endsection
