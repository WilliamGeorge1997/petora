@extends('common::layouts.master')

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-start mb-0">الخصومات</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">الرئيسية</a></li>
                            @if (auth()->guard('admin')->user()->hasRole('Super Admin'))
                                <li class="breadcrumb-item"><a href="{{ route('branches.index') }}">الفروع</a></li>
                            @endif
                            <li class="breadcrumb-item">{{ $branch->getTranslation('title', 'ar') }}</li>
                            <li class="breadcrumb-item active">الخصومات</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <form method="POST" action="{{ route('branch.discounts.update', ['id' => $branch->id]) }}">
                @csrf

                <div class="card">
                    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">خصم حسب قيمة الطلب</h4>
                        <div class="form-check form-switch form-check-primary mb-0">
                            <label class="form-check-label fw-bold me-50" for="is_order_discount_enabled">تفعيل خصم قيمة
                                الطلب</label>
                            <input class="form-check-input" type="checkbox" id="is_order_discount_enabled"
                                name="is_order_discount_enabled" value="1"
                                @if (old('is_order_discount_enabled', $branch->settings?->is_order_discount_enabled)) checked @endif>
                        </div>
                    </div>

                    <div class="card-body pt-2">
                        @error('order_discounts')
                            <div class="alert alert-danger p-1 mb-2">
                                {{ $message }}
                            </div>
                        @enderror

                        {{-- Repeater for Discounts --}}
                        <div id="discounts-container"
                            style="display: {{ old('is_order_discount_enabled', $branch->settings?->is_order_discount_enabled) ? 'block' : 'none' }};"
                            class="mb-2">
                            <div class="repeater-default">
                                <div data-repeater-list="order_discounts">
                                    @php
                                        $discounts = $branch->orderDiscounts ?? collect();
                                        $oldDiscounts = old('order_discounts');
                                        if ($oldDiscounts) {
                                            $discounts = collect($oldDiscounts);
                                        }
                                    @endphp

                                    @if ($discounts->count() > 0)
                                        @foreach ($discounts as $index => $discount)
                                            <div data-repeater-item class="row align-items-end mb-1">
                                                <input type="hidden" name="id"
                                                    value="{{ is_array($discount) ? $discount['id'] ?? '' : $discount->id }}">
                                                <div class="col-md-4 col-12 mb-1 mb-md-0">
                                                    <label class="form-label fw-semibold">الحد الأدنى للطلب</label>
                                                    <input type="number" step="0.01" min="0"
                                                        class="form-control @error("order_discounts.{$index}.min_total") is-invalid @enderror"
                                                        name="min_total" required
                                                        value="{{ is_array($discount) ? $discount['min_total'] ?? '' : $discount->min_total }}"
                                                        placeholder="مثال: 100">
                                                    @error("order_discounts.{$index}.min_total")
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-3 col-12 mb-1 mb-md-0">
                                                    <label class="form-label fw-semibold">نوع الخصم</label>
                                                    <select name="type"
                                                        class="form-select @error("order_discounts.{$index}.type") is-invalid @enderror"
                                                        required>
                                                        <option value="percent"
                                                            @if ((is_array($discount) ? $discount['type'] ?? '' : $discount->type) == 'percent') selected @endif>نسبة مئوية (%)
                                                        </option>
                                                        <option value="fixed"
                                                            @if ((is_array($discount) ? $discount['type'] ?? '' : $discount->type) == 'fixed') selected @endif>مبلغ ثابت
                                                        </option>
                                                    </select>
                                                    @error("order_discounts.{$index}.type")
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-3 col-12 mb-1 mb-md-0">
                                                    <label class="form-label fw-semibold">قيمة الخصم</label>
                                                    <input type="number" step="0.01" min="0"
                                                        class="form-control @error("order_discounts.{$index}.value") is-invalid @enderror"
                                                        name="value" required
                                                        value="{{ is_array($discount) ? $discount['value'] ?? '' : $discount->value }}"
                                                        placeholder="مثال: 10">
                                                    @error("order_discounts.{$index}.value")
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                {{-- <div class="col-md-2 col-12">
                                                <button class="btn btn-outline-danger w-100" data-repeater-delete type="button">
                                                    <i data-feather="trash-2" class="me-25"></i>
                                                    <span>حذف</span>
                                                </button>
                                            </div> --}}
                                            </div>
                                        @endforeach
                                    @else
                                        <div data-repeater-item class="row align-items-end mb-1">
                                            <input type="hidden" name="id" value="">
                                            <div class="col-md-4 col-12 mb-1 mb-md-0">
                                                <label class="form-label fw-semibold">الحد الأدنى للطلب</label>
                                                <input type="number" step="0.01" min="0" class="form-control"
                                                    name="min_total" required placeholder="مثال: 100">
                                            </div>
                                            <div class="col-md-3 col-12 mb-1 mb-md-0">
                                                <label class="form-label fw-semibold">نوع الخصم</label>
                                                <select name="type" class="form-select" required>
                                                    <option value="percent">نسبة مئوية (%)</option>
                                                    <option value="fixed">مبلغ ثابت</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 col-12 mb-1 mb-md-0">
                                                <label class="form-label fw-semibold">قيمة الخصم</label>
                                                <input type="number" step="0.01" min="0" class="form-control"
                                                    name="value" required placeholder="مثال: 10">
                                            </div>
                                            {{-- <div class="col-md-2 col-12">
                                            <button class="btn btn-outline-danger w-100" data-repeater-delete type="button">
                                                <i data-feather="trash-2" class="me-25"></i>
                                                <span>حذف</span>
                                            </button>
                                        </div> --}}
                                        </div>
                                    @endif
                                </div>
                                {{-- <div class="row mt-1">
                                <div class="col-12">
                                    <button class="btn btn-outline-primary" type="button" data-repeater-create>
                                        <i data-feather="plus" class="me-25"></i>
                                        <span>إضافة خصم جديد</span>
                                    </button>
                                </div>
                            </div> --}}
                            </div>
                        </div>

                        <div class="mt-2 pt-1 border-top">
                            <button class="btn btn-primary" type="submit">
                                <i data-feather="save" class="me-25"></i>
                                <span>حفظ التغييرات</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    @if (session('success'))
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: 'success',
                    title: 'احسنت',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'حسناً'
                });
            });
        </script>
    @endif
    <script src="{{ asset('') }}admin/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize repeater with custom Arabic confirmation using jQuery
            $('.repeater-default').repeater({
                show: function() {
                    $(this).slideDown();
                    if (typeof feather !== 'undefined') {
                        feather.replace({
                            width: 14,
                            height: 14
                        });
                    }
                },
                hide: function(deleteElement) {
                    if (confirm('هل أنت متأكد من حذف هذا الخصم؟')) {
                        $(this).slideUp(deleteElement);
                    }
                }
            });

            // Toggle discounts section on switch change using jQuery
            $('#is_order_discount_enabled').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#discounts-container').slideDown();
                    $('#discounts-container').find('input, select').prop('disabled', false);
                } else {
                    $('#discounts-container').slideUp();
                    $('#discounts-container').find('input, select').prop('disabled', true);
                }
            });

            // Initial check on load
            if ($('#is_order_discount_enabled').is(':checked')) {
                $('#discounts-container').show();
                $('#discounts-container').find('input, select').prop('disabled', false);
            } else {
                $('#discounts-container').hide();
                $('#discounts-container').find('input, select').prop('disabled', true);
            }
        });
    </script>
@endsection
