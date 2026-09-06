@php
    $locale = app()->getLocale();
@endphp
@extends('common::layouts.master')

@section('title', __('service::general.edit'))

@section('content')
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('service::general.edit') }} {{ $service->getTranslation('title', 'ar') }}</h4>
            </div>
            <div class="card-body">
                <form class="form form-horizontal" action="{{ route('admin.service.update', $service->id) }}" method="POST"
                    enctype="multipart/form-data">
                    {{ method_field('PUT') }}
                    {{ csrf_field() }}
                    <div class="row">
                        {{-- Title ar --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="title_ar">{{ __('service::attribute.title_ar') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="text" id="title_ar"
                                            value="{{ $service->getTranslation('title', 'ar') }}" class="form-control"
                                            name="title_ar" placeholder="{{ __('service::attribute.title_ar') }}" required />
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
                                        for="title_en">{{ __('service::attribute.title_en') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="text" id="title_en"
                                            value="{{ $service->getTranslation('title', 'en') }}" class="form-control"
                                            name="title_en" placeholder="{{ __('service::attribute.title_en') }}" required />
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
                                        for="description_ar">{{ __('service::attribute.description_ar') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="file-text"></i></span>
                                        <textarea class="form-control" name="description_ar" placeholder="{{ __('service::attribute.description_ar') }}">{{ $service->getTranslation('description', 'ar', false) }}</textarea>
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
                                        for="description_en">{{ __('service::attribute.description_en') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="file-text"></i></span>
                                        <textarea class="form-control" name="description_en" placeholder="{{ __('service::attribute.description_en') }}">{{ $service->getTranslation('description', 'en', false) }}</textarea>
                                        @error('description_en')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Image --}}
                        <div class="col-12">
                            <div class="mb-1 row align-items-center">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="image">{{ __('service::attribute.image') }}</label>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="image"></i></span>
                                        <input type="file" id="image" class="form-control" name="image"
                                            placeholder="{{ __('service::attribute.image') }}" accept="image/*" />
                                        @error('image')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                @if ($service->image != null)
                                    <div class="col-sm-3 text-center">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <img class="rounded border width-100 height-100 cursor-pointer"
                                                role="button"
                                                data-bs-toggle="modal"
                                                data-bs-target="#serviceImageModal"
                                                src="{{ asset($service->image) }}"
                                                alt="{{ $service->getTranslation('title', $locale) }}">
                                        </div>
                                    </div>
                                    <x-common::modal id="serviceImageModal" :title="$service->getTranslation('title', $locale)" body-class="text-center">
                                        <img src="{{ asset($service->image) }}" class="img-fluid rounded border" alt="{{ $service->getTranslation('title', $locale) }}">
                                    </x-common::modal>
                                @endif
                            </div>
                        </div>

                        {{-- Price --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="price">{{ __('service::attribute.price') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="dollar-sign"></i></span>
                                        <input type="number" step="0.01" min="0" id="price" class="form-control" name="price"
                                            placeholder="{{ __('service::attribute.price') }}" value="{{ old('price', $service->price) }}" required />
                                    </div>
                                    @error('price')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Duration --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="duration">{{ __('service::attribute.duration') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="clock"></i></span>
                                        <input type="number" min="1" id="duration" class="form-control" name="duration"
                                            placeholder="{{ __('service::attribute.duration') }}" value="{{ old('duration', $service->duration) }}" />
                                    </div>
                                    @error('duration')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Is Active --}}
                        <div class="col-sm-9 offset-sm-3">
                            <div class="mb-1">
                                <div class="form-check">
                                    <input type="checkbox" value="1" @if ($service->is_active == 1) checked @endif
                                        name="is_active" class="form-check-input" id="customCheck2" />
                                    <label class="form-check-label"
                                        for="customCheck2">{{ __('service::attribute.is_active') }}</label>
                                </div>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit" class="btn btn-primary me-1"><i data-feather="edit" class="me-50"></i>{{ __('service::general.edit') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
