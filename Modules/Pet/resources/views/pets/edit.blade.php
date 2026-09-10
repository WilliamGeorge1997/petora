@php
    $locale = app()->getLocale();
@endphp
@extends('common::layouts.master')

@section('title', __('pet::general.edit'))

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/vendors/css/forms/select/select2.min.css') }}">
@endsection

@section('content')
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('pet::general.edit') }} {{ $pet->name }}</h4>
            </div>
            <div class="card-body">
                <form class="form form-horizontal" action="{{ route('admin.pet.update', $pet->id) }}" method="POST"
                    enctype="multipart/form-data">
                    {{ method_field('PUT') }}
                    {{ csrf_field() }}
                    <div class="row">
                        {{-- Name --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="name">{{ __('pet::attribute.name') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="text" id="name" value="{{ old('name', $pet->name) }}"
                                            class="form-control" name="name"
                                            placeholder="{{ __('pet::attribute.name') }}" required />
                                    </div>
                                    @error('name')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Breed --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="breed">{{ __('pet::attribute.breed') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="tag"></i></span>
                                        <input type="text" id="breed" class="form-control" name="breed"
                                            placeholder="{{ __('pet::attribute.breed') }}"
                                            value="{{ old('breed', $pet->breed) }}" />
                                    </div>
                                    @error('breed')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Client --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="client_id">{{ __('pet::attribute.client_id') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <select class="form-select select2" name="client_id" id="client_id" required>
                                        <option value="" disabled selected>{{ __('pet::attribute.select_client') }}
                                        </option>
                                        @foreach ($viewModel->clients() as $client)
                                            <option value="{{ $client->id }}"
                                                {{ $pet->client_id == $client->id ? 'selected' : '' }}>
                                                {{ $client->name ?? $client->phone }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('client_id')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Pet Type --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="pet_type_id">{{ __('pet::attribute.pet_type_id') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <select class="form-select select2" name="pet_type_id" id="pet_type_id" required>
                                        <option value="" disabled selected>{{ __('pet::attribute.select_type') }}
                                        </option>
                                        @foreach ($viewModel->petTypes() as $type)
                                            <option value="{{ $type->id }}"
                                                {{ $pet->pet_type_id == $type->id ? 'selected' : '' }}>
                                                {{ $type->getTranslation('title', $locale) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('pet_type_id')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Gender --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="gender">{{ __('pet::attribute.gender') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <select class="form-select" name="gender" id="gender" required>
                                        <option value="m" {{ $pet->gender === 'm' ? 'selected' : '' }}>
                                            {{ __('pet::general.male') }}</option>
                                        <option value="f" {{ $pet->gender === 'f' ? 'selected' : '' }}>
                                            {{ __('pet::general.female') }}</option>
                                    </select>
                                    @error('gender')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Date of Birth --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="date_of_birth">{{ __('pet::attribute.date_of_birth') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="date" class="form-control" name="date_of_birth"
                                        value="{{ $pet->date_of_birth ? \Carbon\Carbon::parse($pet->date_of_birth)->format('Y-m-d') : '' }}" />
                                    @error('date_of_birth')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Weight --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="weight">{{ __('pet::attribute.weight') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="number" step="0.01" class="form-control" name="weight"
                                        placeholder="0.00" value="{{ $pet->weight }}" />
                                    @error('weight')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Image --}}
                        <div class="col-12">
                            <div class="mb-1 row align-items-center">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="image">{{ __('pet::attribute.image') }}</label>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="image"></i></span>
                                        <input type="file" id="image" class="form-control" name="image"
                                            accept="image/*" />
                                        @error('image')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                @if ($pet->image != null)
                                    <div class="col-sm-3 text-center">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <img class="rounded border width-100 height-100 cursor-pointer" role="button"
                                                data-bs-toggle="modal" data-bs-target="#petImageModal"
                                                src="{{ asset($pet->image) }}" alt="{{ $pet->name }}">
                                        </div>
                                    </div>
                                    <x-common::modal id="petImageModal" :title="$pet->name" body-class="text-center">
                                        <img src="{{ asset($pet->image) }}" class="img-fluid rounded border"
                                            alt="{{ $pet->name }}">
                                    </x-common::modal>
                                @endif
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit" class="btn btn-primary me-1"><i data-feather="edit"
                                    class="me-50"></i>{{ __('pet::general.edit') }}</button>
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
                        dropdownAutoWidth: true,
                        width: '100%',
                        dropdownParent: $this.parent()
                    });
                });
            }
        });
    </script>
@endsection
