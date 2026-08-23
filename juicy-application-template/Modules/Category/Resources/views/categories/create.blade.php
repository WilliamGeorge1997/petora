@extends('common::layouts.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}admin/css-rtl/plugins/forms/form-validation.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}admin/vendors/css/forms/select/select2.min.css">
@endsection
@section('content')
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">انشاء فئة جديدة</h4>
            </div>
            <div class="card-body">
                <form class="form form-horizontal" action="{{ url('admin/categories/') }}" method="POST"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="fname-icon"> الاسم باللغة العربية</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="text" class="form-control" name="title_ar"
                                            placeholder="الاسم باللغة العربية" value="{{ old('title_ar') }}" />
                                        @error('title_ar')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="fname-icon"> الاسم باللغة الانجليزية</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="text" id="fname-icon" class="form-control" name="title_en"
                                            placeholder="الاسم باللغة الانجليزية" value="{{ old('title_en') }}" />
                                        @error('title_en')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        @php
                            $isSuperAdmin = auth()->guard('admin')->user()->hasRole('Super Admin');
                        @endphp

                        @if ($isSuperAdmin)
                            <div class="col-12">
                                <div class="mb-1 row">
                                    <div class="col-sm-3 text-center">
                                        <label class="col-form-label" for="branch-select">الفرع</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="input-group-merge">
                                            <select class="select2 form-select" id="branch-select" name="branch_id"
                                                onchange="filterParentCategoriesByBranch()">
                                                <option value="" selected>اختر الفرع</option>
                                                @foreach (\Modules\Branch\Entities\Branch::active()->with('settings:branch_id,theme')->get() as $branch)
                                                    <option value="{{ $branch->id }}"
                                                        data-theme="{{ $branch->settings->theme ?? '' }}">
                                                        {{ $branch->title ?? $branch->getTranslations('title')['ar'] . ' - ' . $branch->getTranslations('title')['en'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('branch_id')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @php
                            $showCategoryRow = $isSuperAdmin || (!empty($theme) && $theme != 1);
                        @endphp

                        @if ($showCategoryRow)
                            <div class="col-12" id="category-row"
                                @if ($isSuperAdmin) style="display:none;" @endif>
                                <div class="mb-1 row">
                                    <div class="col-sm-3 text-center">
                                        <label class="col-form-label" for="pass-icon">القسم الاعلي</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="input-group-merge">
                                            <select class="select2 form-select dt-role" id="select2-basic"
                                                name="category_id">
                                                <option value="" selected>اختر القسم الاعلي</option>
                                                @foreach ($viewModel->active() as $category)
                                                    <option value="{{ $category->id }}"
                                                        data-branch-id="{{ $category->branch_id ?? '' }}">
                                                        {{ $category->getTranslations('title')['en'] }} -
                                                        {{ $category->getTranslations('title')['ar'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif



                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="fname-icon">الترتيب</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="number" value="{{ old('sort_order') }}" id="fname-icon"
                                            class="form-control" name="sort_order" placeholder="الترتيب"
                                            value="{{ old('sort_order') }}" />
                                        @error('sort_order')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="pass-icon">الصورة</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="image"></i></span>
                                        <input type="file" id="pass-icon" class="form-control" name="image"
                                            placeholder="الصورة" />
                                        @error('image')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-9 offset-sm-3">
                            <div class="mb-1">
                                <div class="form-check">
                                    <input type="checkbox" value="1" name="is_active" class="form-check-input"
                                        id="customCheck2" />
                                    <label class="form-check-label" for="customCheck2">تفعيل</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit" class="btn btn-primary me-1">اضافة</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('') }}admin/vendors/js/forms/validation/jquery.validate.min.js"></script>
    <script src="{{ asset('') }}admin/js/scripts/forms/form-validation.js"></script>
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

        var allParentCategories = [];
        $('#select2-basic option').each(function() {
            var val = $(this).val();
            if (val) {
                allParentCategories.push({
                    value: val,
                    text: $(this).text(),
                    branchId: $(this).attr('data-branch-id') || ''
                });
            }
        });

        function filterParentCategoriesByBranch() {
            var selectedBranch = $('#branch-select').val() || '';
            var parentSelect = $('#select2-basic');
            var categoryRow = $('#category-row');

            // Toggle category row based on selected branch theme
            var selectedOption = $('#branch-select').find('option:selected');
            var theme = selectedOption.data('theme');

            if (!selectedBranch) {
                categoryRow.hide();
            } else if (theme == 1) {
                categoryRow.hide();
            } else {
                categoryRow.show();
            }

            parentSelect.empty();
            parentSelect.append('<option value="">اختر القسم الاعلي</option>');

            for (var i = 0; i < allParentCategories.length; i++) {
                var cat = allParentCategories[i];
                if (selectedBranch && cat.branchId === selectedBranch) {
                    parentSelect.append('<option value="' + cat.value + '">' + cat.text + '</option>');
                }
            }

            parentSelect.val('');
            parentSelect.trigger('change');

            if (parentSelect.hasClass('select2-hidden-accessible')) {
                parentSelect.select2('destroy');
                parentSelect.select2({
                    dropdownAutoWidth: true,
                    width: '100%',
                    dropdownParent: parentSelect.parent()
                });
            }
        }

        if ($('#branch-select').length) {
            filterParentCategoriesByBranch();
        }
    </script>
@endsection
