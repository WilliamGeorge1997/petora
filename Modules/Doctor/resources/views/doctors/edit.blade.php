@php
    $locale = app()->getLocale();
@endphp
@extends('common::layouts.master')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('admin/vendors/css/forms/select/select2.min.css')}}">
@endsection
@section('content')
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('doctor::general.edit_doctor') }}</h4>
            </div>
            <div class="card-body">
                <form class="form form-horizontal" action="{{ route('admin.doctor.update', $doctor->id) }}" method="POST"
                    enctype="multipart/form-data">
                    <div class="row">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}

                        {{-- Name ar --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="name_ar">{{ __('doctor::attribute.name_ar') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="text" class="form-control" name="name_ar" required
                                            placeholder="{{ __('doctor::attribute.name_ar') }}"
                                            value="{{ old('name_ar', $doctor->getTranslation('name', 'ar')) }}" />
                                    </div>
                                    @error('name_ar')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Name en --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="name_en">{{ __('doctor::attribute.name_en') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="text" class="form-control" name="name_en" required
                                            placeholder="{{ __('doctor::attribute.name_en') }}"
                                            value="{{ old('name_en', $doctor->getTranslation('name', 'en')) }}" />
                                    </div>
                                    @error('name_en')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Clinic --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="clinic_id">{{ __('doctor::attribute.clinic_id') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <select name="clinic_id" class="form-select select2" required>
                                        <option value="" disabled selected>{{ __('clinic::general.select_clinic') }}</option>
                                        @foreach($viewModel->clinics() as $clinic)
                                            <option value="{{ $clinic->id }}" {{ old('clinic_id', $doctor->clinic_id) == $clinic->id ? 'selected' : '' }}>
                                                {{ $clinic->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('clinic_id')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Specialty ar --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="specialty_ar">{{ __('doctor::attribute.specialty_ar') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="briefcase"></i></span>
                                        <input type="text" class="form-control" name="specialty_ar" required
                                            placeholder="{{ __('doctor::attribute.specialty_ar') }}"
                                            value="{{ old('specialty_ar', $doctor->getTranslation('specialty', 'ar')) }}" />
                                    </div>
                                    @error('specialty_ar')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Specialty en --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="specialty_en">{{ __('doctor::attribute.specialty_en') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="briefcase"></i></span>
                                        <input type="text" class="form-control" name="specialty_en" required
                                            placeholder="{{ __('doctor::attribute.specialty_en') }}"
                                            value="{{ old('specialty_en', $doctor->getTranslation('specialty', 'en')) }}" />
                                    </div>
                                    @error('specialty_en')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Image --}}
                        <div class="col-12">
                            <div class="mb-1 row align-items-center">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="image">{{ __('doctor::attribute.image') }}</label>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="image"></i></span>
                                        <input type="file" id="image" class="form-control" name="image"
                                            placeholder="{{ __('doctor::attribute.image') }}" accept="image/*" />
                                        @error('image')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                @if ($doctor->image != null)
                                    <div class="col-sm-3 text-center">
                                        <div class="d-flex justify-content-center align-items-center mt-1">
                                            <img class="img-fluid rounded border" width="100" height="100" src="{{ asset($doctor->image) }}" alt="{{ $doctor->getTranslation('name', $locale) }}">
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Is Active --}}
                        <div class="col-sm-9 offset-sm-3">
                            <div class="mb-1">
                                <div class="form-check">
                                    <input type="checkbox" value="1" name="is_active" class="form-check-input"
                                        id="customCheck2" {{ $doctor->is_active ? 'checked' : '' }} />
                                    <label class="form-check-label"
                                        for="customCheck2">{{ __('doctor::attribute.is_active') }}</label>
                                </div>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit"
                                class="btn btn-primary me-1">{{ __('doctor::general.update') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{asset('admin/vendors/js/forms/select/select2.full.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            var select = $('.select2');
            if (select.length) {
                select.each(function () {
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
