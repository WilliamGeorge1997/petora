@php
    $locale = app()->getLocale();
    use Modules\Coupon\Enums\CouponType;
    use Modules\Coupon\Enums\CouponDiscountOn;
@endphp
@extends('common::layouts.master')

@section('title', __('coupon::general.edit') ?? 'Edit Coupon')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/vendors/css/forms/select/select2.min.css') }}">
@endsection

@section('content')
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('coupon::general.edit') ?? 'Edit Coupon' }} {{ $coupon->code }}</h4>
            </div>
            <div class="card-body">
                <form class="form form-horizontal" action="{{ route('admin.coupon.update', $coupon->id) }}" method="POST">
                    {{ method_field('PUT') }}
                    {{ csrf_field() }}
                    <div class="row">
                        
                        {{-- Code --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="code">{{ __('coupon::attribute.code') ?? 'Code' }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="hash"></i></span>
                                        <input type="text" class="form-control" name="code" required
                                            placeholder="{{ __('coupon::attribute.code') ?? 'Code' }}"
                                            value="{{ old('code') ?? $coupon->code }}" />
                                    </div>
                                    @error('code')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Type --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="type">{{ __('coupon::attribute.type') ?? 'Type' }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <select class="form-select select2" name="type" id="type" required>
                                        @foreach (CouponType::options() as $value => $label)
                                            <option value="{{ $value }}" {{ (old('type') ?? $coupon->type->value) == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Value --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="value">{{ __('coupon::attribute.value') ?? 'Value' }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="dollar-sign"></i></span>
                                        <input type="number" step="0.01" class="form-control" name="value" required
                                            placeholder="{{ __('coupon::attribute.value') ?? 'Value' }}"
                                            value="{{ old('value') ?? $coupon->value }}" />
                                    </div>
                                    @error('value')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Limit --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="limit">{{ __('coupon::attribute.limit') ?? 'Max Discount Limit' }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="shield"></i></span>
                                        <input type="number" step="0.01" class="form-control" name="limit"
                                            placeholder="{{ __('coupon::attribute.limit') ?? 'Max Discount Limit' }}"
                                            value="{{ old('limit') ?? $coupon->limit }}" />
                                    </div>
                                    @error('limit')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Discount On --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="discount_on">{{ __('coupon::attribute.discount_on') ?? 'Discount On' }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <select class="form-select select2" name="discount_on" id="discount_on" required>
                                        @foreach (CouponDiscountOn::options() as $value => $label)
                                            <option value="{{ $value }}" {{ (old('discount_on') ?? $coupon->discount_on->value) == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('discount_on')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Num of uses --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="num_of_uses">{{ __('coupon::attribute.num_of_uses') ?? 'Usage Limit' }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="users"></i></span>
                                        <input type="number" class="form-control" name="num_of_uses"
                                            placeholder="{{ __('coupon::attribute.num_of_uses') ?? 'Usage Limit' }}"
                                            value="{{ old('num_of_uses') ?? $coupon->num_of_uses }}" />
                                    </div>
                                    @error('num_of_uses')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Client uses --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="client_uses">{{ __('coupon::attribute.client_uses') ?? 'Uses Per Client' }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user-check"></i></span>
                                        <input type="number" class="form-control" name="client_uses"
                                            placeholder="{{ __('coupon::attribute.client_uses') ?? 'Uses Per Client' }}"
                                            value="{{ old('client_uses') ?? $coupon->client_uses }}" />
                                    </div>
                                    @error('client_uses')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Date From --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="date_from">{{ __('coupon::attribute.date_from') ?? 'Date From' }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="calendar"></i></span>
                                        <input type="date" class="form-control" name="date_from"
                                            value="{{ old('date_from') ?? ($coupon->date_from ? $coupon->date_from->format('Y-m-d') : '') }}" />
                                    </div>
                                    @error('date_from')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Date To --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="date_to">{{ __('coupon::attribute.date_to') ?? 'Date To' }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="calendar"></i></span>
                                        <input type="date" class="form-control" name="date_to"
                                            value="{{ old('date_to') ?? ($coupon->date_to ? $coupon->date_to->format('Y-m-d') : '') }}" />
                                    </div>
                                    @error('date_to')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Time From --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="time_from">{{ __('coupon::attribute.time_from') ?? 'Time From' }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="clock"></i></span>
                                        <input type="time" class="form-control" name="time_from"
                                            value="{{ old('time_from') ?? ($coupon->time_from ? substr($coupon->time_from, 0, 5) : '') }}" />
                                    </div>
                                    @error('time_from')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Time To --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="time_to">{{ __('coupon::attribute.time_to') ?? 'Time To' }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="clock"></i></span>
                                        <input type="time" class="form-control" name="time_to"
                                            value="{{ old('time_to') ?? ($coupon->time_to ? substr($coupon->time_to, 0, 5) : '') }}" />
                                    </div>
                                    @error('time_to')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Is Active --}}
                        <div class="col-sm-9 offset-sm-3">
                            <div class="mb-1">
                                <div class="form-check">
                                    <input type="checkbox" value="1" name="is_active" class="form-check-input"
                                        id="customCheck2" {{ $coupon->is_active ? 'checked' : '' }} />
                                    <label class="form-check-label"
                                        for="customCheck2">{{ __('coupon::attribute.is_active') ?? 'Is Active' }}</label>
                                </div>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit" class="btn btn-primary me-1"><i data-feather="edit"
                                    class="me-50"></i>{{ __('coupon::general.update') ?? 'Update' }}</button>
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
                        dropdownParent: $this.parent()
                    });
                });
            }
        });
    </script>
@endsection
