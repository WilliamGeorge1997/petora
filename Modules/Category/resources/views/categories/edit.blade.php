@php
    $locale = app()->getLocale();
@endphp
@extends('common::layouts.master')

@section('title', __('category::general.edit'))

@section('content')
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('category::general.edit') }}
                    {{ $category->getTranslation('title', 'ar') }}</h4>
            </div>
            <div class="card-body">
                <form class="form form-horizontal" action="{{ route('admin.category.update', $category->id) }}" method="POST"
                    enctype="multipart/form-data">
                    {{ method_field('PUT') }}
                    {{ csrf_field() }}
                    <div class="row">
                        {{-- Title ar --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="title_ar">{{ __('category::attribute.title_ar') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="text" id="title_ar"
                                            value="{{ $category->getTranslation('title', 'ar') }}" class="form-control"
                                            name="title_ar" placeholder="{{ __('category::attribute.title_ar') }}"
                                            required />
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
                                        for="title_en">{{ __('category::attribute.title_en') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="text" id="title_en"
                                            value="{{ $category->getTranslation('title', 'en') }}" class="form-control"
                                            name="title_en" placeholder="{{ __('category::attribute.title_en') }}"
                                            required />
                                        @error('title_en')
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
                                        for="image">{{ __('category::attribute.image') }}</label>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="image"></i></span>
                                        <input type="file" id="image" class="form-control" name="image"
                                            placeholder="{{ __('category::attribute.image') }}" accept="image/*" />
                                        @error('image')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                @if ($category->image != null)
                                    <div class="col-sm-3 text-center">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <img class="rounded border width-100 height-100 cursor-pointer"
                                                role="button"
                                                data-bs-toggle="modal"
                                                data-bs-target="#categoryImageModal"
                                                src="{{ asset($category->image) }}"
                                                alt="{{ $category->getTranslation('title', $locale) }}">
                                        </div>
                                    </div>
                                    <x-common::modal id="categoryImageModal" :title="$category->getTranslation('title', $locale)" body-class="text-center">
                                        <img src="{{ asset($category->image) }}" class="img-fluid rounded border" alt="{{ $category->getTranslation('title', $locale) }}">
                                    </x-common::modal>
                                @endif
                            </div>
                        </div>


                        {{-- Is Active --}}
                        <div class="col-sm-9 offset-sm-3">
                            <div class="mb-1">
                                <div class="form-check">
                                    <input type="checkbox" value="1" @if ($category->is_active == 1) checked @endif
                                        name="is_active" class="form-check-input" id="customCheck2" />
                                    <label class="form-check-label"
                                        for="customCheck2">{{ __('category::attribute.is_active') }}</label>
                                </div>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit"
                                class="btn btn-primary me-1"><i data-feather="edit" class="me-50"></i>{{ __('category::general.update') }}</button>
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
