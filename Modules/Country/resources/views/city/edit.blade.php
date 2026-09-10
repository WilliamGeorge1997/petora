@php
    $locale = app()->getLocale();
@endphp
@extends('common::layouts.master')

@section('title', __('country::general.edit_city'))

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/vendors/css/forms/select/select2.min.css') }}">
@endsection

@section('content')
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('country::general.edit_city') }} {{ $city->getTranslation('title', 'ar') }}</h4>
            </div>
            <div class="card-body">
                <form class="form form-horizontal" action="{{ route('admin.cities.update', $city->id) }}" method="POST"
                    enctype="multipart/form-data">
                    {{ method_field('PUT') }}
                    {{ csrf_field() }}
                    <div class="row">
                        {{-- Title ar --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="title_ar">{{ __('country::attribute.title_ar') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="text" id="title_ar"
                                            value="{{ $city->getTranslation('title', 'ar') }}" class="form-control"
                                            name="title_ar" placeholder="{{ __('country::attribute.title_ar') }}"
                                            required />
                                        @error('title_ar')
                                            <p class="text-danger">{{ $message }}</p>
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
                                        for="title_en">{{ __('country::attribute.title_en') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="text" id="title_en"
                                            value="{{ $city->getTranslation('title', 'en') }}" class="form-control"
                                            name="title_en" placeholder="{{ __('country::attribute.title_en') }}"
                                            required />
                                        @error('title_en')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Country --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="country_id">{{ __('country::attribute.country_id') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <select class="select2 form-select" name="country_id" id="country_id" required>
                                        <option value="" disabled selected>
                                            {{ __('country::general.select_country') }}</option>
                                        @foreach ($viewModel->countries() as $country)
                                            <option value="{{ $country->id }}"
                                                {{ $city->country_id == $country->id ? 'selected' : '' }}>
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

                        {{-- Is Active --}}
                        <div class="col-sm-9 offset-sm-3">
                            <div class="mb-1">
                                <div class="form-check">
                                    <input type="checkbox" value="1" @if ($city->is_active == 1) checked @endif
                                        name="is_active" class="form-check-input" id="customCheck2" />
                                    <label class="form-check-label"
                                        for="customCheck2">{{ __('country::attribute.is_active') }}</label>
                                </div>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit" class="btn btn-primary me-1"><i data-feather="edit"
                                    class="me-50"></i>{{ __('country::general.edit_city') }}</button>
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
        $(document).ready(function() {
            var select = $('.select2');
            if (select.length) {
                select.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>');
                    $this.select2({
                        dropdownParent: $this.parent()
                    });
                });
            }
        });
    </script>
@endsection
