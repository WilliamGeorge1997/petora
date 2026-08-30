@php
    $locale = app()->getLocale();
@endphp
@extends('common::layouts.master')
@section('content')
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('client::general.edit_client') }}</h4>
            </div>
            <div class="card-body">
                <form class="form form-horizontal" action="{{ route('admin.client.update', $client->id) }}" method="POST"
                    enctype="multipart/form-data">
                    {{ method_field('PUT') }}
                    {{ csrf_field() }}
                    <div class="row">
                        
                        {{-- Name --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="name">{{ __('client::attribute.name') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="text" class="form-control" name="name" required
                                            placeholder="{{ __('client::attribute.name') }}"
                                            value="{{ old('name', $client->name) }}" />
                                    </div>
                                    @error('name')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="email">{{ __('client::attribute.email') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="mail"></i></span>
                                        <input type="email" class="form-control" name="email"
                                            placeholder="{{ __('client::attribute.email') }}"
                                            value="{{ old('email', $client->email) }}" />
                                    </div>
                                    @error('email')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Phone --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="phone">{{ __('client::attribute.phone') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="phone"></i></span>
                                        <input type="text" class="form-control" name="phone" required
                                            placeholder="{{ __('client::attribute.phone') }}"
                                            value="{{ old('phone', $client->phone) }}" />
                                    </div>
                                    @error('phone')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Password --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="password">{{ __('client::attribute.password') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="lock"></i></span>
                                        <input type="password" class="form-control" name="password"
                                            placeholder="{{ __('client::attribute.password') }}" />
                                    </div>
                                    <small class="text-muted">{{ __('client::general.leave_blank_to_keep_current') }}</small>
                                    @error('password')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Image --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="image">{{ __('client::attribute.image') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="file" class="form-control dropify" name="image"
                                        data-default-file="{{ $client->image }}" />
                                    @error('image')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Is Active --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="is_active">{{ __('client::attribute.is_active') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="form-check form-switch form-check-success">
                                        <input type="checkbox" class="form-check-input switch-active" name="is_active" value="1"
                                            id="is_active" {{ old('is_active', $client->is_active) ? 'checked' : '' }} />
                                        <label class="form-check-label" for="is_active">
                                            <span class="switch-icon-left"><i data-feather="check"></i></span>
                                            <span class="switch-icon-right"><i data-feather="x"></i></span>
                                        </label>
                                    </div>
                                    @error('is_active')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>


                        <div class="col-sm-9 offset-sm-3 text-center">
                            <button type="submit"
                                class="btn btn-primary me-1">{{ __('client::general.save') }}</button>
                            <a href="{{ route('admin.client.index') }}"
                                class="btn btn-outline-secondary">{{ __('client::general.cancel') }}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
