@extends('common::layouts.master')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}admin/vendors/css/forms/select/select2.min.css">
@endsection

@section('content')
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">تعديل البيانات الخاصه بالقسم {{ $category['title'] }}</h4>
            </div>
            <div class="card-body">
                <form class="form form-horizontal" action="{{ url('admin/categories/' . $category->id) }}" method="POST"
                    enctype="multipart/form-data">
                    {{ method_field('PUT') }}
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
                                        <input type="text" id="fname-icon" value="{{ $category['title'] }}"
                                            class="form-control" name="title_ar" placeholder="الاسم باللغة العربية" />
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
                                        <input type="text" id="fname-icon"
                                            value="{{ $category->getTranslations('title')['en'] }}" class="form-control"
                                            name="title_en" placeholder="الاسم باللغة الانجليزية" />
                                        @error('title_en')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if (auth()->guard('admin')->user()->hasRole('Super Admin'))
                            <div class="col-12">
                                <div class="mb-1 row">
                                    <div class="col-sm-3 text-center">
                                        <label class="col-form-label" for="branch-select">الفرع</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="input-group-merge">
                                            <select class="select2 form-select" id="branch-select" name="branch_id"
                                                onchange="filterParentCategoriesByBranch()">
                                                @foreach (\Modules\Branch\Entities\Branch::active()->get() as $branch)
                                                    <option value="{{ $branch->id }}"
                                                        @if ($category['branch_id'] == $branch->id) selected @endif>
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

                        @if (!empty($theme) && $theme !== 1)
                            <div class="col-12">
                                <div class="mb-1 row">
                                    <div class="col-sm-3 text-center">
                                        <label class="col-form-label" for="pass-icon">القسم الاعلي</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="input-group-merge">
                                            <select class="select2 form-select dt-role" id="select2-basic"
                                                name="category_id">
                                                <option value="">اختر القسم الاعلي</option>
                                                @foreach ($viewModel->active() as $categoryModel)
                                                    <option value="{{ $categoryModel->id }}"
                                                        data-branch-id="{{ $categoryModel->branch_id ?? '' }}"
                                                        {{ $categoryModel->id == $category->category_id ? 'selected' : '' }}>
                                                        {{ $categoryModel->getTranslations('title')['ar'] }} -
                                                        {{ $categoryModel->getTranslations('title')['en'] }}
                                                    </option>
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
                                        <input type="number" value="{{ $category['sort_order'] }}" id="fname-icon"
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
                            <div class="mb-1 row align-items-center">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="pass-icon">الصورة</label>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="image"></i></span>
                                        <input type="file" id="pass-icon" class="form-control" name="image"
                                            placeholder="الصورة" />
                                        @error('image')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    @if ($category->image)
                                        <a href="{{ $category->image }}" data-bs-toggle="modal"
                                            data-bs-target="#imageModal">
                                            <img src="{{ $category->image }}" alt="image"
                                                class="img-fluid border p-1" width="150" height="150">
                                        </a>
                                        <!-- Modal -->
                                        <div class="modal fade" id="imageModal" tabindex="-1"
                                            aria-labelledby="imageModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="imageModalLabel">صورة القسم</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <img src="{{ $category->image }}" alt="image"
                                                            class="img-fluid">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>



                        <div class="col-sm-9 offset-sm-3">
                            <div class="mb-1">
                                <div class="form-check">
                                    <input type="checkbox" value="1" @if ($category->is_active == 1) checked @endif
                                        name="is_active" class="form-check-input" id="customCheck2" />
                                    <label class="form-check-label" for="customCheck2">تفعيل</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit" class="btn btn-primary me-1">تعديل</button>
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

        var initialParentCategoryId = "{{ $category->category_id ?? '' }}";
        var initialBranchId = "{{ $category->branch_id ?? '' }}";

        var $parentSelect = $('#select2-basic');
        var allParentCategories = $parentSelect.find('option[value!=""]').map(function() {
            return {
                value: this.value,
                text: $(this).text(),
                branchId: String($(this).data('branchId') || '')
            };
        }).get();

        function filterParentCategoriesByBranch(branchId) {
            var isInitial = branchId != null && branchId !== '';
            var selectedBranch = isInitial ? String(branchId) : ($('#branch-select').val() || '');

            $parentSelect.empty().append('<option value="">اختر القسم الاعلي</option>');

            $.each(allParentCategories, function(_, cat) {
                if (selectedBranch && cat.branchId === selectedBranch) {
                    $parentSelect.append('<option value="' + cat.value + '">' + cat.text + '</option>');
                }
            });

            if (isInitial && initialParentCategoryId) {
                if (!$parentSelect.find('option[value="' + initialParentCategoryId + '"]').length) {
                    var initialCat = allParentCategories.find(function(c) {
                        return c.value === initialParentCategoryId;
                    });
                    if (initialCat) {
                        $parentSelect.append('<option value="' + initialCat.value + '">' + initialCat.text + '</option>');
                    }
                }
                $parentSelect.val(initialParentCategoryId);
            } else {
                $parentSelect.val('');
            }

            $parentSelect.trigger('change');

            if ($parentSelect.hasClass('select2-hidden-accessible')) {
                $parentSelect.select2('destroy').select2({
                    dropdownAutoWidth: true,
                    width: '100%',
                    dropdownParent: $parentSelect.parent()
                });
            }
        }

        if ($('#branch-select').length) {
            filterParentCategoriesByBranch(initialBranchId);
        }
    </script>
@endsection
