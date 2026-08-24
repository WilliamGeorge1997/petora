@extends('common::layouts.master')
@section('content')
    <section id="dashboard-ecommerce">
        <div class="row match-height">
            <div class="col-xl-4 col-md-6 col-12">
                <div class="card card-congratulation-medal">
                    <div class="card-body">
                        <h5>{{ __('admin::dashboard.congratulations') }}</h5>
                        <p class="card-text font-small-3">{{ __('admin::dashboard.welcome_message') }}</p>
                        <a href="{{ url('admin/orders') }}" type="button" class="btn btn-primary">{{ __('admin::dashboard.view_orders') }}</a>
                        <img src="{{ asset('admin/images/illustration/badge.svg') }}" class="congratulation-medal"
                            alt="Medal Pic" />
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
