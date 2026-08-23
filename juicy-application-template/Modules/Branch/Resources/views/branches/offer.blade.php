@extends('common::layouts.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}admin/vendors/css/forms/select/select2.min.css">
@endsection

@section('content')

    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">انشاء او تعديل العرض</h4>
            </div>
            <div class="card-body">
                @if (auth('admin')->user()->hasRole('Super Admin'))
                    <form method="GET" class="mb-2">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-1 row">
                                    <div class="col-sm-3 text-center">
                                        <label class="col-form-label" for="pass-icon">الفرع</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <select name="branch_id" class="form-select select2" onchange="this.form.submit()">
                                            <option value="">اختر الفرع</option>
                                            @foreach (\Modules\Branch\Entities\Branch::available()->active()->get() as $branch)
                                                <option value="{{ $branch->id }}"
                                                    @if ($branch_id == $branch->id) selected @endif>
                                                    {{ $branch->title ?? $branch->getTranslations('title')['ar'] . ' - ' . $branch->getTranslations('title')['en'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('branch_id')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                @endif

                <form class="form form-horizontal" action="{{ url('admin/branches/offer') }}" method="POST"
                    enctype="multipart/form-data">

                    @csrf
                    <input type="hidden" name="branch_id" value="{{ $branch_id }}">

                    <div class="row">
                        {{-- Offer image --}}
                        <div class="col-12">
                            <div class="mb-1 row align-items-center">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="pass-icon">صورة العرض</label>
                                </div>
                                <div class="col-sm-7">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="image"></i></span>
                                        <input type="file" id="pass-icon" class="form-control" name="app_offer_image"
                                            placeholder="app_offer_image" />
                                    </div>
                                </div>
                                @error('app_offer_image')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                                @if ($offerData?->app_offer_image)
                                    <div class="col-sm-2 ">
                                        <div class="border p-1 text-center">
                                            <a href="{{ $offerData?->app_offer_image }}" data-bs-toggle="modal"
                                                data-bs-target="#appOfferImageModal">
                                                <img src="{{ $offerData?->app_offer_image }}" alt="image"
                                                    class="img-fluid " width="150" height="150">
                                            </a>
                                        </div>

                                        <!-- Modal -->
                                        <div class="modal fade" id="appOfferImageModal" tabindex="-1"
                                            aria-labelledby="appOfferImageModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="appOfferImageModalLabel">صورة العرض
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <img src="{{ $offerData?->app_offer_image }}" alt="image"
                                                            class="img-fluid">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Product --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label">المنتج</label>
                                </div>
                                <div class="col-sm-9">
                                    <select class="select2 form-select" name="app_offer_product_id">
                                        <option value="">اختر المنتج</option>
                                        @foreach ($viewModel->offerProducts($branch_id) as $product)
                                            <option value="{{ $product->id }}"
                                                @if ($offerData?->app_offer_product_id == $product->id) selected @endif>
                                                {{ $product->getTranslations('title')['ar'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Ends at --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label">موعد الانتهاء</label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="datetime-local" class="form-control" name="app_offer_ends_at"
                                        value="{{ $offerData?->app_offer_ends_at }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-9 offset-sm-3">
                            <div class="mb-1">
                                <div class="form-check">
                                    <input type="checkbox" value="1" @if ($offerData?->app_offer_is_active == 1) checked @endif
                                        name="app_offer_is_active" class="form-check-input" id="customCheck2" />
                                    <label class="form-check-label" for="customCheck2">تفعيل</label>
                                </div>
                            </div>
                        </div>


                        <div class="col-sm-9 offset-sm-3 mt-2">
                            <button class="btn btn-primary me-50">حفظ</button>
                        </div>


                    </div>
                </form>
                @if ($branch_id && $offerData->app_offer_image)
                    <form class="col-sm-9 offset-sm-3 mt-2" method="POST"
                        action="{{ route('branches.offer.destroy') }}"
                        onsubmit="return confirm('هل أنت متأكد من حذف العرض؟');">
                        @csrf
                        @method('delete')
                        <input type="hidden" name="branch_id" value="{{ $branch_id }}">
                        <button type="submit" class="btn btn-danger">حذف العرض</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('') }}admin/vendors/js/forms/select/select2.full.min.js"></script>
    <script>
        $('.select2').select2({
            width: '100%'
        });
    </script>
    @if (session('success'))
        <script src="{{ asset('') }}admin/vendors/js/extensions/sweetalert2.all.min.js"></script>
        <script>
            Swal.fire({
                title: 'أحسنت!',
                text: '{{ session('success') }}',
                icon: 'success',
                customClass: {
                    confirmButton: 'btn btn-primary'
                },
                buttonsStyling: false
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                title: 'خطأ!',
                text: '{{ session('error') }}',
                icon: 'error',
                customClass: {
                    confirmButton: 'btn btn-primary'
                },
                buttonsStyling: false
            });
        </script>
    @endif
@endsection
