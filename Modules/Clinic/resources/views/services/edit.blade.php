@php
    $locale = app()->getLocale();
@endphp
@extends('common::layouts.master')

@section('title', __('clinic::general.service.edit'))

@section('content')
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    {{ __('clinic::general.service.edit') }} - {{ $clinic_service->service?->getTranslation('title', $locale) }}
                </h4>
            </div>
            <div class="card-body">
                <form class="form form-horizontal"
                    action="{{ route('admin.clinic.services.update', [$clinic->id, $clinic_service->id]) }}"
                    method="POST">
                    {{ method_field('PUT') }}
                    {{ csrf_field() }}
                    <div class="row">

                        {{-- Price --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="price">{{ __('service::attribute.price') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="dollar-sign"></i></span>
                                        <input type="number" step="0.01" min="0" id="price"
                                            value="{{ $clinic_service->price }}" class="form-control"
                                            name="price" placeholder="{{ __('service::attribute.price') }}" required />
                                        @error('price')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Duration --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="duration">{{ __('service::attribute.duration') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="clock"></i></span>
                                        <input type="number" min="1" id="duration"
                                            value="{{ $clinic_service->duration }}" class="form-control"
                                            name="duration" placeholder="{{ __('service::attribute.duration') }}" />
                                        @error('duration')
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
                                    <input type="checkbox" value="1"
                                        @if ($clinic_service->is_active == 1) checked @endif
                                        name="is_active" class="form-check-input" id="is_active" />
                                    <label class="form-check-label" for="is_active">{{ __('service::attribute.is_active') }}</label>
                                </div>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit" class="btn btn-primary me-1">
                                <i data-feather="edit" class="me-50"></i>{{ __('clinic::general.update') }}
                            </button>
                            <a href="{{ route('admin.clinic.services.index', $clinic->id) }}" class="btn btn-outline-secondary">
                                <i data-feather="x" class="me-50"></i>{{ __('clinic::general.cancel') }}
                            </a>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
