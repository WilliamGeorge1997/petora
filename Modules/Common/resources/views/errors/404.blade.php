@extends('common::layouts.master')

@section('title', __('common::message.page_not_found'))

@section('content')
    <div class="misc-inner p-2 p-sm-3 text-center">
        <div class="w-100">
            <h2 class="mb-1">{{ __('common::message.page_not_found_title') }} 🕵🏻‍♀️</h2>
            <p class="mb-2">{{ __('common::message.page_not_found_desc') }}</p>
            <a class="btn btn-primary mb-2 btn-sm-block" href="{{ url('/admin/dashboard') }}">
                {{ __('common::message.back_to_home') }}
            </a>
            <div class="mt-1">
                <img class="img-fluid width-75" src="{{ asset('admin/images/pages/error.svg') }}" alt="404" />
            </div>
        </div>
    </div>
@endsection
