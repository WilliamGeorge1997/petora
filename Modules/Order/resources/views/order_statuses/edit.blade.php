@php
    $locale = app()->getLocale();
@endphp
@extends('common::layouts.master')

@section('title', __('order::general.edit'))

@section('content')
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('order::general.edit_order_status') }} {{ $orderStatus->getTranslation('title', 'ar') }}</h4>
            </div>
            <div class="card-body">
                <form class="form form-horizontal" action="{{ route('admin.order_status.update', $orderStatus->id) }}" method="POST">
                    {{ method_field('PUT') }}
                    {{ csrf_field() }}
                    <div class="row">
                        {{-- Title ar --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="title_ar">{{ __('order::attribute.title_ar') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="check-circle"></i></span>
                                        <input type="text" id="title_ar"
                                            value="{{ $orderStatus->getTranslation('title', 'ar') }}" class="form-control"
                                            name="title_ar" placeholder="{{ __('order::attribute.title_ar') }}" required />
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
                                        for="title_en">{{ __('order::attribute.title_en') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="check-circle"></i></span>
                                        <input type="text" id="title_en"
                                            value="{{ $orderStatus->getTranslation('title', 'en') }}" class="form-control"
                                            name="title_en" placeholder="{{ __('order::attribute.title_en') }}" required />
                                        @error('title_en')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Is Active --}}
                        <div class="col-sm-9 offset-sm-3">
                            <div class="mb-1">
                                <div class="form-check">
                                    <input type="checkbox" value="1" {{ old('is_active', $orderStatus->is_active) ? 'checked' : '' }}
                                        name="is_active" class="form-check-input" id="customCheck2" />
                                    <label class="form-check-label"
                                        for="customCheck2">{{ __('order::attribute.is_active') }}</label>
                                </div>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit" class="btn btn-primary me-1"><i data-feather="edit" class="me-50"></i>{{ __('order::general.edit_order_status') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
