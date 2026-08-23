@extends('common::layouts.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}admin/css-rtl/plugins/forms/form-validation.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}admin/vendors/css/forms/select/select2.min.css">
@endsection

@section('content')
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">اضافة صور المنيو</h4>
            </div>
            <div class="card-body">
                <form class="form form-horizontal" action="{{ url('admin/galleries') }}" method="POST"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}

                    @php
                        $isSuperAdmin = auth()->guard('admin')->user()->hasRole('Super Admin');
                    @endphp

                    {{-- Branch dropdown for Super Admin --}}
                    @if ($isSuperAdmin)
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-1 row">
                                    <div class="col-sm-3 text-center">
                                        <label class="col-form-label" for="branch-select">الفرع</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="input-group-merge">
                                            <select class="select2 form-select" id="branch-select" name="branch_id">
                                                <option value="" selected>اختر الفرع</option>
                                                @foreach ($viewModel->findTheme6Branches() as $branch)
                                                    <option value="{{ $branch->id }}"
                                                        {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                                        {{ $branch->getTranslations('title')['ar'] ?? '' }}
                                                        - {{ $branch->getTranslations('title')['en'] ?? '' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('branch_id')
                                                <p class="text-danger mt-25">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Multiple images upload --}}
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="images">الصور</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="image"></i></span>
                                        <input type="file" required id="images" class="form-control" name="images[]"
                                            multiple accept="image/jpeg,image/png,image/jpg,image/webp,image/bmp" />
                                    </div>
                                    @error('images')
                                        <p class="text-danger mt-25">{{ $message }}</p>
                                    @enderror
                                    @error('images.*')
                                        <p class="text-danger mt-25">{{ $message }}</p>
                                    @enderror
                                    <small class="text-muted">يمكنك اختيار أكثر من صورة في نفس الوقت</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="row">
                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit" class="btn btn-primary me-1">اضافة</button>
                            <a href="{{ url('admin/galleries') }}" class="btn btn-outline-secondary">الغاء</a>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('') }}admin/vendors/js/forms/select/select2.full.min.js"></script>
    <script>
        var select = $('.select2');
        select.each(function() {
            var $this = $(this);
            $this.wrap('<div class="position-relative"></div>');
            $this.select2({
                dropdownAutoWidth: true,
                width: '100%',
                dropdownParent: $this.parent()
            });
        });
    </script>
@endsection
