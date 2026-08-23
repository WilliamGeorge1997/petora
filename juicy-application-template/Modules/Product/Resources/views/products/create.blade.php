@extends('common::layouts.master')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}admin/vendors/css/forms/select/select2.min.css">
@endsection
@section('content')
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">انشاء منتج جديد</h4>
            </div>
            <div class="card-body">
                <form class="form form-horizontal invoice-repeater" action="{{ url('admin/products/') }}" method="POST"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}
                    @if (!empty(session('alert_msg')))
                        <div class="alert alert-danger fail text-center" role="alert">
                            {{ session('alert_msg') }}
                        </div>
                    @endif
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
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="fname-icon"> الوصف باللغة العربية</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="text" class="form-control" name="description_ar"
                                            placeholder="الوصف باللغة العربية" value="{{ old('description_ar') }}" />
                                        @error('description_ar')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="fname-icon"> الوصف باللغة الانجليزية</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="text" id="fname-icon" class="form-control" name="description_en"
                                            placeholder="الوصف باللغة الانجليزية" value="{{ old('description_en') }}" />
                                        @error('description_en')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="fname-icon">السعر</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="number" step=".01" id="fname-icon" class="form-control"
                                            name="price" placeholder="السعر" value="{{ old('price') }}" />
                                        @error('price')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="fname-icon">السعر بعد الخصم</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="number" step=".01" id="fname-icon" class="form-control"
                                            name="discounted_price" placeholder="السعر بعد الخصم"
                                            value="{{ old('discounted_price') }}" />
                                        @error('discounted_price')
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
                                                onchange="filterCategoriesByBranch()">
                                                <option value="" selected>اختر الفرع</option>
                                                @foreach (\Modules\Branch\Entities\Branch::active()->get() as $branch)
                                                    <option value="{{ $branch->id }}">
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

                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="pass-icon">القسم</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group-merge">
                                        <select class="form-select select2" name="category_id" id="category-select">
                                            <option value="">اختر القسم</option>
                                            @foreach ($viewModel->categories() as $category)
                                                <option value="{{ $category->id }}"
                                                    data-branch-id="{{ $category->branch_id ?? '' }}">
                                                    {{ $category->getTranslations('title')['en'] ?? '' }} -
                                                    {{ $category->getTranslations('title')['ar'] ?? '' }}</option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="fname-icon">الترتيب</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="number" id="fname-icon" class="form-control" name="sort_order"
                                            placeholder="الترتيب" value="{{ old('sort_order') }}" />
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
                                    <label class="col-form-label" for="fname-icon">السعرات الحرارية</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="text" id="fname-icon" class="form-control" name="calories"
                                            placeholder="السعرات الحرارية" value="{{ old('calories') }}" />
                                        @error('calories')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="fname-icon">مسببات الحساسية باللغه العربية</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <textarea type="text" id="fname-icon" class="form-control" name="allergens_ar"
                                            placeholder="مسببات الحساسية باللغه العربية">{{ old('allergens_ar') }}</textarea>
                                        @error('allergens_ar')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="fname-icon">مسببات الحساسية باللغه
                                        الانجيليزية</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <textarea type="text" id="fname-icon" class="form-control" name="allergens_en"
                                            placeholder="مسببات الحساسية باللغه الانجيليزية">{{ old('allergens_en') }}</textarea>
                                        @error('allergens_en')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="pass-icon">الصور</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="image"></i></span>
                                        <input type="file" id="pass-icon" required class="form-control"
                                            name="images[]" placeholder="الصور" multiple />
                                        @error('images')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                        @if ($errors->has('images.*'))
                                            <span class="text-danger">{{ $errors->first('images.*') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="pass-icon">الاضافات</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group-merge">
                                        <select name="addons[]" class="select2 form-select select2-hidden-accessible"
                                            id="select2-multiple" multiple="" data-select2-id="select2-multiple"
                                            tabindex="-1" aria-hidden="true">
                                            @foreach ($viewModel->addons() as $addon)
                                                <option value="{{ $addon->id }}">{{ $addon->display ?: $addon->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="pass-icon">الاطباق الجانبية</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group-merge">
                                        <select name="sides[]" class="select2 form-select select2-hidden-accessible"
                                            id="select2-multiple2" multiple="" data-select2-id="select2-multiple2"
                                            tabindex="-1" aria-hidden="true">
                                            @foreach ($viewModel->sides() as $side)
                                                <option value="{{ $side->id }}">{{ $side->display ?: $side->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="divider divider-primary">
                            <div class="divider-text">أنواع المنتج (اختياري)</div>
                        </div>

                        <div data-repeater-list="types">
                            <div data-repeater-item>
                                <div class="row d-flex align-items-end">
                                    <div class="col-md-3 col-12">
                                        <div class="mb-1">
                                            <label class="form-label">الاسم بالعربية</label>
                                            <input type="text" name="title_ar" class="form-control"
                                                placeholder="الاسم بالعربية" />
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-12">
                                        <div class="mb-1">
                                            <label class="form-label">الاسم بالانجليزية</label>
                                            <input type="text" name="title_en" class="form-control"
                                                placeholder="الاسم بالانجليزية" />
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-12">
                                        <div class="mb-1">
                                            <label class="form-label">السعر</label>
                                            <input type="number" step=".01" name="price" class="form-control"
                                                placeholder="السعر" />
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-12">
                                        <div class="mb-1">
                                            <label class="form-label">الصورة</label>
                                            <input type="file" name="image" class="form-control"
                                                accept="image/*" />
                                        </div>
                                    </div>

                                    <div class="col-md-1 col-12">
                                        <div class="mb-1">
                                            <label class="form-label">الترتيب</label>
                                            <input type="number" name="sort_order" class="form-control"
                                                value="1" />
                                        </div>
                                    </div>

                                    <div class="col-md-1 col-12">
                                        <div class="mb-1">
                                            <button class="btn btn-outline-danger text-nowrap px-1" data-repeater-delete
                                                type="button">
                                                <i data-feather="x" class="me-25"></i>
                                                <span>حذف</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <hr />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button class="btn btn-icon btn-primary" type="button" data-repeater-create>
                                    <i data-feather="plus" class="me-25"></i>
                                    <span>اضافة نوع جديد</span>
                                </button>
                            </div>
                        </div> --}}

                        <div class="col-sm-9 offset-sm-3 mt-2">
                            <div class="mb-1">
                                <div class="form-check">
                                    <input type="checkbox" value="1" name="is_spicy" class="form-check-input"
                                        id="isSpicyCheck" {{ old('is_spicy') ? 'checked' : '' }} />
                                    <label class="form-check-label" for="isSpicyCheck">حار</label>
                                </div>
                            </div>
                        </div>


                        <div class="col-sm-9 offset-sm-3">
                            <div class="mb-1">
                                <div class="form-check">
                                    <input type="checkbox" value="1" name="is_vegetarian" class="form-check-input"
                                        id="isSpicyCheck" {{ old('is_vegetarian') ? 'checked' : '' }} />
                                    <label class="form-check-label" for="isSpicyCheck">نباتي</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-9 offset-sm-3">
                            <div class="mb-1">
                                <div class="form-check">
                                    <input type="checkbox" value="1" name="multi_select_sides"
                                        class="form-check-input" id="multiSelectSidesCheck"
                                        {{ old('multi_select_sides') ? 'checked' : '' }} />
                                    <label class="form-check-label" for="multiSelectSidesCheck">اختيار متعدد للاطباق
                                        الجانبية</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-9 offset-sm-3">
                            <div class="mb-1">
                                <div class="form-check">
                                    <input type="checkbox" value="1" name="is_active" class="form-check-input"
                                        id="customCheck2" {{ old('is_active') ? 'checked' : '' }} />
                                    <label class="form-check-label" for="customCheck2">تفعيل</label>
                                </div>
                            </div>
                        </div>


                        <div class="col-9 offset-sm-3 mb-2">
                            <div class="form-check">
                                <input type="checkbox" value="1" name="has_size" class="form-check-input"
                                    id="hasSizeCheck" {{ old('has_size') ? 'checked' : '' }} />
                                <label class="form-check-label" for="hasSizeCheck">هل المنتج له حجم؟</label>
                            </div>
                        </div>

                        <div id="sizeAttributeSection" style="display: {{ old('has_size') ? 'block' : 'none' }};">
                            <div class="divider divider-primary">
                                <div class="divider-text">الأحجام (اختياري)</div>
                            </div>
                            <div class="col-12">
                                <h4 class="card-title mb-1">الأحجام</h4>
                            </div>

                            <div class="row">

                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3 text-center">
                                            <label class="col-form-label" for="fname-icon"> الاسم باللغة العربية</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group-merge">
                                                <span class="input-group-text"><i data-feather="user"></i></span>
                                                <input type="text" class="form-control" disabled
                                                    placeholder="الاسم باللغة العربية" value="الحجم" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3 text-center">
                                            <label class="col-form-label" for="fname-icon"> الاسم باللغة الانجليزية
                                                الانجليزية</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group-merge">
                                                <span class="input-group-text"><i data-feather="user"></i></span>
                                                <input type="text" class="form-control" disabled
                                                    placeholder="الاسم باللغة الانجليزية" value="Size" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 mb-2">
                                    <div class="mb-1 row">
                                        <div class="col-sm-9 offset-sm-3">
                                            <div class="form-check mb-1">
                                                <input type="checkbox" value="1" name="required"
                                                    class="form-check-input" id="requiredCheck"
                                                    {{ old('required') ? 'checked' : '' }} />
                                                <label class="form-check-label" for="requiredCheck">الزامي</label>
                                            </div>
                                            {{-- <div class="form-check mb-1">
                                                <input type="checkbox" value="1" name="multi_select"
                                                    class="form-check-input" id="multiSelectCheck"
                                                    {{ old('multi_select') ? 'checked' : '' }} />
                                                <label class="form-check-label" for="multiSelectCheck">اختيار
                                                    متعدد</label>
                                            </div> --}}
                                            <div class="form-check">
                                                <input type="checkbox" value="1" name="override_price"
                                                    class="form-check-input" id="overridePriceCheck"
                                                    {{ old('override_price') ? 'checked' : '' }} />
                                                <label class="form-check-label" for="overridePriceCheck">التغيير الي سعر
                                                    الاحجام
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div data-repeater-list="sizes">
                                    @if (old('sizes'))
                                        @foreach (old('sizes') as $index => $size)
                                            <div data-repeater-item>
                                                <div class="row d-flex align-items-end">
                                                    <div class="col-md-4 col-12">
                                                        <div class="mb-1">
                                                            <label class="form-label">الاسم بالعربية</label>
                                                            <input type="text" name="name_ar"
                                                                class="form-control to-be-disabled"
                                                                placeholder="الاسم بالعربية"
                                                                value="{{ $size['name_ar'] ?? '' }}" />
                                                            @error("sizes.{$index}.name_ar")
                                                                <p class="alert alert-danger mt-1">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4 col-12">
                                                        <div class="mb-1">
                                                            <label class="form-label">الاسم بالانجليزية</label>
                                                            <input type="text" name="name_en"
                                                                class="form-control to-be-disabled"
                                                                placeholder="الاسم بالانجليزية"
                                                                value="{{ $size['name_en'] ?? '' }}" />
                                                            @error("sizes.{$index}.name_en")
                                                                <p class="alert alert-danger mt-1">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2 col-12">
                                                        <div class="mb-1">
                                                            <label class="form-label">السعر</label>
                                                            <input type="number" name="price" step=".01"
                                                                class="form-control to-be-disabled" placeholder="السعر"
                                                                value="{{ $size['price'] ?? '' }}" />
                                                            @error("sizes.{$index}.price")
                                                                <p class="alert alert-danger mt-1">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2 col-12">
                                                        <div class="mb-1">
                                                            <button class="btn btn-outline-danger text-nowrap px-1"
                                                                data-repeater-delete type="button">
                                                                <i data-feather="x" class="me-25"></i>
                                                                <span>حذف</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr />
                                            </div>
                                        @endforeach
                                    @else
                                        <div data-repeater-item>
                                            <div class="row d-flex align-items-end">
                                                <div class="col-md-4 col-12">
                                                    <div class="mb-1">
                                                        <label class="form-label">الاسم بالعربية</label>
                                                        <input type="text" name="name_ar"
                                                            class="form-control to-be-disabled"
                                                            placeholder="الاسم بالعربية" />

                                                    </div>
                                                </div>

                                                <div class="col-md-4 col-12">
                                                    <div class="mb-1">
                                                        <label class="form-label">الاسم بالانجليزية</label>
                                                        <input type="text" name="name_en"
                                                            class="form-control to-be-disabled"
                                                            placeholder="الاسم بالانجليزية" />
                                                    </div>
                                                </div>

                                                <div class="col-md-2 col-12">
                                                    <div class="mb-1">
                                                        <label class="form-label">السعر</label>
                                                        <input type="number" name="price" step=".01"
                                                            class="form-control to-be-disabled" placeholder="السعر" />
                                                    </div>
                                                </div>

                                                <div class="col-md-2 col-12">
                                                    <div class="mb-1">
                                                        <button class="btn btn-outline-danger text-nowrap px-1"
                                                            data-repeater-delete type="button">
                                                            <i data-feather="x" class="me-25"></i>
                                                            <span>حذف</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr />
                                        </div>
                                    @endif
                                </div>
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <button class="btn btn-icon btn-primary" type="button" data-repeater-create>
                                            <i data-feather="plus" class="me-25"></i>
                                            <span>اضافة حجم جديد</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit" class="btn btn-primary me-1">اضافة</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
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
    <script src="{{ asset('') }}admin/vendors/js/forms/select/select2.full.min.js"></script>
    <script src="{{ asset('') }}admin/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
    <script src="{{ asset('') }}admin/js/scripts/forms/form-repeater.js"></script>

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
    <script>
        const hasSizeCheck = document.getElementById('hasSizeCheck');
        const sizeAttributeSection = document.getElementById('sizeAttributeSection');

        // Check sizes repeater if selected
        function checkSizesReapeater() {
            if (hasSizeCheck.checked) {
                sizeAttributeSection.style.display = 'block';
                sizeAttributeSection.querySelectorAll('.to-be-disabled').forEach(input => {
                    input.disabled = false;
                });
            } else {
                sizeAttributeSection.style.display = 'none';
                sizeAttributeSection.querySelectorAll('.to-be-disabled').forEach(input => {
                    input.disabled = true;
                });
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            checkSizesReapeater();

            $(document).on('click', '[data-repeater-create]', function() {
                setTimeout(function() {
                    checkSizesReapeater();
                }, 0);
            });
        });

        hasSizeCheck.addEventListener('change', function() {
            checkSizesReapeater();
        });
    </script>
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
    <script>
        var allCategories = [];
        $('#category-select option').each(function() {
            var val = $(this).val();
            if (val) {
                allCategories.push({
                    value: val,
                    text: $(this).text(),
                    branchId: $(this).attr('data-branch-id') || ''
                });
            }
        });

        function filterCategoriesByBranch() {
            var selectedBranch = $('#branch-select').val() || '';
            var categorySelect = $('#category-select');

            categorySelect.empty();
            categorySelect.append('<option value="">اختر القسم</option>');

            for (var i = 0; i < allCategories.length; i++) {
                var cat = allCategories[i];
                if (selectedBranch && cat.branchId === selectedBranch) {
                    categorySelect.append('<option value="' + cat.value + '">' + cat.text + '</option>');
                }
            }

            categorySelect.val('');
            categorySelect.trigger('change');

            if (categorySelect.hasClass('select2-hidden-accessible')) {
                categorySelect.select2('destroy');
                categorySelect.select2({
                    dropdownAutoWidth: true,
                    width: '100%',
                    dropdownParent: categorySelect.parent()
                });
            }
        }

        if ($('#branch-select').length) {
            filterCategoriesByBranch();
        }
    </script>
@endsection
