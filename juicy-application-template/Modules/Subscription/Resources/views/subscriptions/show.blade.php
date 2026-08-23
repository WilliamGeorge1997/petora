@extends('common::layouts.master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('') }}admin/css-rtl/plugins/extensions/ext-component-sweet-alerts.css">
    <style>
        .subscription-card {
            border: 2px solid #e4e6f0;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .subscription-card.active {
            border-color: #28a745;
            background: linear-gradient(135deg, #f8fff9 0%, #e8f5e8 100%);
        }

        .package-selection-card {
            border: 2px dashed #007bff;
            border-radius: 10px;
            background: linear-gradient(135deg, #f8fbff 0%, #e3f2fd 100%);
        }

        .badge-active {
            background: linear-gradient(45deg, #28a745, #20c997);
        }

        .form-control:disabled {
            background-color: #f8f9fa;
            opacity: 0.8;
        }

        /* History Table Styles */
        .history-table {
            margin-bottom: 0;
        }

        .history-table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            font-size: 0.875rem;
            color: #495057;
            padding: 12px 8px;
        }

        .history-table tbody tr {
            transition: background-color 0.2s ease;
        }

        .history-table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .history-table td {
            padding: 12px 8px;
            vertical-align: middle;
            border-bottom: 1px solid #dee2e6;
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">اشتراكات الفرع</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('admin/branches') }}">الشركات</a></li>
                                <li class="breadcrumb-item active">اشتراكات {{ $branch->getTranslations('title')['ar'] }}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <!-- branch Info Card -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">معلومات الفرع</h4>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-2 text-center">
                                    @if ($branch->image)
                                        <img src="{{ asset($branch->image) }}"
                                            alt="{{ $branch->getTranslations('title')['ar'] }}"
                                            class="img-fluid rounded-circle"
                                            style="width: 80px; height: 80px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 80px; height: 80px;">
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-10">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <strong>اسم الشركة:</strong> {{ $branch->getTranslations('title')['ar'] }}
                                        </div>
                                        <div class="col-md-3">
                                            <strong>رقم الهاتف:</strong> {{ $branch->phone ?? '' }}
                                        </div>
                                        <div class="col-md-3">
                                            <strong>العنوان :</strong> {{ $branch->getTranslations('address')['ar'] ?? '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <!-- Current Subscriptions Section -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title">الاشتراكات الحالية</h4>
                            <span class="badge bg-primary">{{ $activeSubscription ? 1 : 0 }} اشتراك</span>
                        </div>
                        <div class="card-body py-2">
                            @if ($activeSubscription)
                                <div class="subscription-card active p-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="flex-grow-1">
                                            <div class="row align-items-center">
                                                <div class="col-md-5">
                                                    <h6 class="mb-1">
                                                        {{ $activeSubscription->package->getTranslations('title')['ar'] }}
                                                        <span class="badge badge-active ms-2">فعال</span>
                                                    </h6>
                                                    <small class="text-muted">
                                                        {{ Str::limit($activeSubscription->package->getTranslations('description')['ar'], 50) }}
                                                    </small>
                                                </div>
                                                <div class="col-md-2 text-center">
                                                    <small class="text-muted d-block">السعر</small>
                                                    <strong>{{ number_format($activeSubscription->price, 2) }} ج.م</strong>
                                                </div>
                                                <div class="col-md-2 text-center">
                                                    <small class="text-muted d-block">المدة</small>
                                                    <strong>{{ $activeSubscription->duration_months }}
                                                        {{ $activeSubscription->duration_months == 1 ? 'شهر' : 'أشهر' }}</strong>
                                                </div>
                                                <div class="col-md-2 text-center">
                                                    <small class="text-muted d-block">عدد المنتجات</small>
                                                    <strong>{{ $activeSubscription->product_count }}
                                                        @if (is_null($activeSubscription->product_count))
                                                            غير متاح
                                                        @else
                                                            {{ $activeSubscription->product_count == 1 ? 'منتج' : 'منتجات' }}
                                                        @endif
                                                    </strong>

                                                </div>
                                            </div>
                                            <div class="row mt-1">
                                                <div class="col-md-6">
                                                    <small class="text-muted">
                                                        من
                                                        {{ $activeSubscription->start_date ? $activeSubscription->start_date : 'غير محدد' }}
                                                        إلى
                                                        {{ $activeSubscription->end_date ? $activeSubscription->end_date : 'غير محدد' }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ms-2">
                                            <button type="button" class="btn btn-outline-danger btn-sm"
                                                onclick="confirmDeactivation({{ $activeSubscription->id }})">
                                                إلغاء التفعيل
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-3">
                                    <h6 class="mt-2 mb-1">لا توجد اشتراكات فعالة</h6>
                                    <small class="text-muted">لا توجد اشتراكات فعالة حالياً لهذه الشركة.</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Add New Package Section -->
                <div class="col-lg-4">
                    <div class="card package-selection-card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <i data-feather="plus-circle"></i> إضافة اشتراك جديد
                            </h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.subscriptions.store', $branch->id) }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label">اختر الباقة <span class="text-danger">*</span></label>
                                    <select class="form-select" name="package_id" id="package_id" required>
                                        <option value="">اختر الباقة...</option>
                                        @foreach ($viewModel->activePackages() as $package)
                                            <option value="{{ $package->id }}" data-price="{{ $package->price }}"
                                                data-discounted_price={{ $package->discounted_price ?? $package->price }}
                                                data-duration_months="{{ $package->duration_months }}"
                                                data-product_count="{{ $package->product_count }}">
                                                {{ $package->getTranslations('title')['ar'] }} -
                                                {{ number_format($package->price, 2) }} ج.م
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('package_id')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">مدة الاشتراك (بالشهور)</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="duration_months"
                                            id="duration_months" min="1" disabled
                                            placeholder="سيتم تحديده تلقائياً من الباقة">
                                    </div>
                                    <small class="text-muted">يتم تحديد المدة تلقائياً حسب الباقة المختارة</small>
                                    @error('duration_months')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">عدد المنتجات</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="product_count"
                                            id="product_count" min="1" disabled
                                            placeholder="سيتم تحديده تلقائياً من الباقة">
                                    </div>
                                    <small class="text-muted">يتم تحديد عدد المنتجات تلقائياً حسب الباقة المختارة</small>
                                    @error('product_count')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="row">
                                        <div class="col-6">
                                            <label class="form-label">السعر</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" name="price" id="price"
                                                    step="0.01" min="0" disabled
                                                    placeholder="سيتم تحديده تلقائياً من الباقة">
                                                <span class="input-group-text">ج.م</span>
                                            </div>

                                            @error('price')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label">السعر بعد الخصم</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" name="discounted_price"
                                                    id="discounted_price" step="0.01" min="0" disabled
                                                    placeholder="سيتم حسابه تلقائياً">
                                                <span class="input-group-text">ج.م</span>
                                            </div>
                                            @error('discounted_price')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <small class="text-muted">يتم تحديد السعر تلقائياً حسب الباقة المختارة</small>
                                    </div>
                                </div>

                                <div class="alert alert-info">
                                    <strong>ملاحظة:</strong> عند إضافة اشتراك جديد، سيتم إلغاء تفعيل جميع الاشتراكات السابقة
                                    تلقائياً.
                                </div>

                                <button type="submit" class="btn btn-primary w-100" id="submitBtn" disabled>
                                    <i data-feather="plus"></i> إضافة الاشتراك
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Package Details Preview -->
                    <div class="card mt-3" id="package-preview" style="display: none;">
                        <div class="card-header">
                            <h6 class="card-title">تفاصيل الباقة المختارة</h6>
                        </div>
                        <div class="card-body">
                            <div id="package-details"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subscription History Section -->
            @if ($inactiveSubscriptions->count() > 0)
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title">
                                    سجل الاشتراكات السابقة
                                </h4>
                                <span class="badge bg-danger">{{ $inactiveSubscriptions->count() }} اشتراك غير
                                    فعال</span>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover history-table text-center">
                                        <thead>
                                            <tr>
                                                <th>الباقة</th>
                                                <th>السعر</th>
                                                <th>تاريخ البداية</th>
                                                <th>تاريخ الانتهاء</th>
                                                <th>المدة</th>
                                                <th>عدد المنتجات</th>
                                                <th>الحالة</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($inactiveSubscriptions as $subscription)
                                                <tr>
                                                    <td>
                                                        <div>
                                                            <h6 class="mb-1">
                                                                {{ $subscription->package->getTranslations('title')['ar'] }}
                                                            </h6>
                                                            <small
                                                                class="text-muted">{{ Str::limit($subscription->package->getTranslations('description')['ar'], 40) }}</small>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <strong>{{ number_format($subscription->price, 2) }} ج.م</strong>
                                                    </td>
                                                    <td>
                                                        <div class="text-nowrap">
                                                            {{ $subscription->start_date }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if ($subscription->end_date)
                                                            <div class="text-nowrap">
                                                                {{ $subscription->end_date }}
                                                            </div>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-light text-dark">
                                                            {{ $subscription->duration_months }}
                                                            {{ $subscription->duration_months == 1 ? 'شهر' : 'أشهر' }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        @if (is_null($subscription->product_count))
                                                            <span class="badge bg-light text-dark">غير متاح</span>
                                                        @else
                                                            <span class="badge bg-light text-dark">
                                                                {{ $subscription->product_count }}
                                                                {{ $subscription->product_count == 1 ? 'منتج' : 'منتجات' }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-danger text-white">
                                                            غير فعال
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Simple Summary -->
                                <div class="row mt-3 pt-3 border-top">
                                    <div class="col-md-6 text-center">
                                        <h5 class="mb-0">{{ $inactiveSubscriptions->count() }}</h5>
                                        <small class="text-muted">إجمالي الاشتراكات السابقة</small>
                                    </div>
                                    <div class="col-md-6 text-center">
                                        <h5 class="mb-0">{{ number_format($inactiveSubscriptions->sum('price'), 2) }}
                                            ج.م</h5>
                                        <small class="text-muted">إجمالي القيمة</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Deactivation Confirmation Modal -->
    <div class="modal fade" id="deactivationModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تأكيد إلغاء التفعيل</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    هل أنت متأكد من رغبتك في إلغاء تفعيل هذا الاشتراك؟
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <form id="deactivationForm" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger">تأكيد الإلغاء</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('') }}admin/vendors/js/extensions/sweetalert2.all.min.js"></script>

    <script>
        $(document).ready(function() {
            // Show success alert if session exists
            @if (session('created'))
                Swal.fire({
                    title: 'تم بنجاح!',
                    text: 'تم إلغاء التفعيل بنجاح',
                    icon: 'success',
                    confirmButtonText: 'موافق',
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
                });
            @endif
            @if (session('deactivated'))
                Swal.fire({
                    title: 'تم بنجاح!',
                    text: 'تم إلغاء الاشتراك بنجاح',
                    icon: 'success',
                    confirmButtonText: 'موافق',
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
                });
            @endif
            // Auto-fill form when package is selected
            $('#package_id').change(function() {
                const selectedOption = $(this).find('option:selected');
                const price = selectedOption.data('price');
                const discounted_price = selectedOption.data('discounted_price');
                const duration_months = selectedOption.data('duration_months');
                const product_count = selectedOption.data('product_count');
                if (price) {
                    // Fill the values
                    $('#price').val(price);
                    $('#discounted_price').val(discounted_price);
                    $('#duration_months').val(duration_months);
                    $('#product_count').val(product_count);
                    // Enable submit button
                    $('#submitBtn').prop('disabled', false);

                    // Show package preview
                    const packageName = selectedOption.text();
                    const packageHtml = `
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">${packageName.split(' - ')[0]}</h6>
                    <span class="badge bg-primary">مختارة</span>
                </div>
                <hr>
                <div class="row">
                    <div class="col-4">
                        <small class="text-muted">السعر</small>
                        <div class="h6 text-success">${price} ج.م</div>
                    </div>
                    <div class="col-4">
                        <small class="text-muted">السعر بعد الخصم</small>
                        <div class="h6 text-success">${discounted_price} ج.م</div>
                    </div>
                    <div class="col-4">
                        <small class="text-muted">مدة الاشتراك</small>
                        <div class="h6 text-success">${duration_months} ${duration_months == 1 ? 'شهر' : 'أشهر'}</div>
                    </div>
                    <div class="col-4">
                        <small class="text-muted">عدد المنتجات</small>
                        <div class="h6 text-success">${product_count} ${product_count == 1 ? 'منتج' : 'منتجات'}</div>
                    </div>
                </div>
            `;
                    $('#package-details').html(packageHtml);
                    $('#package-preview').show();
                } else {
                    // Clear values and disable submit
                    $('#price').val('');
                    $('#duration_months').val('');
                    $('#product_count').val('');
                    $('#submitBtn').prop('disabled', true);
                    $('#package-preview').hide();
                }
            });
        });

        function confirmDeactivation(subscriptionId) {
            $('#deactivationForm').attr('action',
                "{{ url('admin/branches/' . $branch->id . '/subscriptions/deactivate') }}/" + subscriptionId);
            $('#deactivationModal').modal('show');
        }
    </script>
@endsection
