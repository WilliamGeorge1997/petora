@php
    $locale = app()->getLocale();
@endphp
@extends('common::layouts.master')

@section('title', __('notification::general.create'))

@section('content')
    <section id="basic-horizontal-layouts">
        <div class="row">
            <div class="col-md-12 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('notification::general.broadcast') }}</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.notifications.store') }}" method="POST" enctype="multipart/form-data" class="form form-horizontal">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="title_en">{{ __('notification::attribute.title_en') }}</label>
                                    <input type="text" id="title_en" class="form-control @error('title_en') is-invalid @enderror" name="title_en" value="{{ old('title_en') }}" required>
                                    @error('title_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="title_ar">{{ __('notification::attribute.title_ar') }}</label>
                                    <input type="text" id="title_ar" class="form-control @error('title_ar') is-invalid @enderror" name="title_ar" value="{{ old('title_ar') }}" required>
                                    @error('title_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="description_en">{{ __('notification::attribute.description_en') }}</label>
                                    <textarea id="description_en" class="form-control @error('description_en') is-invalid @enderror" name="description_en" rows="3" required>{{ old('description_en') }}</textarea>
                                    @error('description_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="description_ar">{{ __('notification::attribute.description_ar') }}</label>
                                    <textarea id="description_ar" class="form-control @error('description_ar') is-invalid @enderror" name="description_ar" rows="3" required>{{ old('description_ar') }}</textarea>
                                    @error('description_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="image">{{ __('notification::attribute.image') }}</label>
                                    <input type="file" id="image" class="form-control @error('image') is-invalid @enderror" name="image">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="group_by">{{ __('notification::attribute.group_by') }}</label>
                                    <input type="text" id="group_by" class="form-control @error('group_by') is-invalid @enderror" name="group_by" value="{{ old('group_by') }}">
                                    @error('group_by')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 mt-3">
                                    <button type="submit" class="btn btn-primary me-1">{{ __('notification::general.create') }}</button>
                                    <a href="{{ route('admin.notifications.index') }}" class="btn btn-outline-secondary">{{ __('common::general.cancel') }}</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
