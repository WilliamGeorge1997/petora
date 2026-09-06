@php
    $locale = app()->getLocale();
@endphp
@extends('common::layouts.master')

@section('title', __('pet::general.create_type'))

@section('content')
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('pet::general.create_type') }}</h4>
            </div>
            <div class="card-body">
                <form class="form form-horizontal" action="{{ route('admin.pet_type.store') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="row">
                        {{-- Title ar --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="title_ar">{{ __('pet::attribute.title_ar') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="tag"></i></span>
                                        <input type="text" class="form-control" name="title_ar" required
                                            placeholder="{{ __('pet::attribute.title_ar') }}"
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
                                        for="title_en">{{ __('pet::attribute.title_en') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="tag"></i></span>
                                        <input type="text" class="form-control" name="title_en" required
                                            placeholder="{{ __('pet::attribute.title_en') }}"
                                            value="{{ old('title_en') }}" />
                                    </div>
                                    @error('title_en')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Is Active --}}
                        <div class="col-sm-9 offset-sm-3">
                            <div class="mb-1">
                                <div class="form-check">
                                    <input type="checkbox" value="1" name="is_active" class="form-check-input"
                                        id="customCheck2" {{ old('is_active') ? 'checked' : '' }} />
                                    <label class="form-check-label"
                                        for="customCheck2">{{ __('pet::attribute.is_active') }}</label>
                                </div>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit"
                                class="btn btn-primary me-1"><i data-feather="plus" class="me-50"></i>{{ __('pet::general.create_type') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
