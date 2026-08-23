@extends('common::layouts.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}admin/vendors/css/forms/select/select2.min.css">
@endsection
@section('content')
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">تعديل البيانات الخاصه بالمنتج {{ $product['title'] }}</h4>
                <a class="btn btn-success" href="{{ url('admin/product/' . $product->id . '/attributes') }}">اضافة/تعديل خصائص المنتج</a>
            </div>
            <div class="card-body">
                <form class="form form-horizontal invoice-repeater" action="{{ url('admin/products/' . $product->id) }}"
                    method="POST" enctype="multipart/form-data">
                    {{ method_field('PUT') }}
                    {{ csrf_field() }}
                    <p class="alert alert-danger" style="text-align: center"> {{ session('alert_msg') }}</p>
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="fname-icon"> الاسم باللغة العربية</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="text" id="fname-icon" value="{{ $product['title'] }}"
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
                                            value="{{ $product->getTranslations('title')['en'] }}" class="form-control"
                                            name="title_en" placeholder="الاسم باللغة الانجليزية" />
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
                                        <input type="text" id="fname-icon" value="{{ @$product['description'] }}"
                                            class="form-control" name="description_ar" placeholder="الوصف باللغة العربية" />
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
                                        <input type="text" id="fname-icon"
                                            value="{{ @$product->getTranslations('description')['en'] }}"
                                            class="form-control" name="description_en"
                                            placeholder="الوصف باللغة الانجليزية" />
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
                                        <input type="number" step=".01" value="{{ $product['price'] }}"
                                            id="fname-icon" class="form-control" name="price" placeholder="السعر" />
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
                                        <input type="number" step=".01" value="{{ $product['discounted_price'] }}"
                                            id="fname-icon" class="form-control" name="discounted_price"
                                            placeholder="السعر" />
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
                                                @foreach (\Modules\Branch\Entities\Branch::active()->get() as $branch)
                                                    <option value="{{ $branch->id }}"
                                                        @if ($product['branch_id'] == $branch->id) selected @endif>
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
                                            @foreach ($viewModel->categories() as $category)
                                                <option value="{{ $category->id }}"
                                                    data-branch-id="{{ $category->branch_id ?? '' }}"
                                                    @if ($product['category_id'] == $category->id) selected @endif>
                                                    {{ $category->getTranslations('title')['en'] }} -
                                                    {{ $category->getTranslations('title')['ar'] }} </option>
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
                                        <input type="number" value="{{ $product['sort_order'] }}" id="fname-icon"
                                            class="form-control" name="sort_order" placeholder="الترتيب" />
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
                                        <input type="text" value="{{ $product['calories'] }}" id="fname-icon"
                                            class="form-control" name="calories" placeholder="السعرات الحرارية" />
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
                                            placeholder="مسببات الحساسية باللغه العربية">{{ $product->getTranslations('allergens')['ar'] ?? '' }}</textarea>
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
                                            placeholder="مسببات الحساسية باللغه الانجيليزية">{{ $product->getTranslations('allergens')['en'] ?? '' }}</textarea>
                                        @error('allergens_en')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-12 mb-2">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="pass-icon">الصور</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="image"></i></span>
                                        <input type="file" @if (count($product->images) == 0) required @endif
                                            id="pass-icon" class="form-control sliderImages" name="images[]"
                                            placeholder="الصور" multiple />
                                        @error('images')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="images-container d-flex flex-row flex-wrap"
                                style="display: flex !important;margin-right:25%">
                                @for ($j = 0; $j < count($product->images); $j++)
                                    @php
                                        $mediaPath = $product->images[$j]->image;
                                        $extension = strtolower(pathinfo($mediaPath, PATHINFO_EXTENSION));
                                        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
                                        $videoExtensions = ['mp4', 'mov', 'avi', 'mkv', 'webm', 'ogg'];
                                        $isImage = in_array($extension, $imageExtensions);
                                        $isVideo = in_array($extension, $videoExtensions);
                                    @endphp
                                    <div class="image-container position-relative" image-id="{{ $mediaPath }}"
                                        style="width: 120px; height: 120px;margin: 2px 7px;margin-top:10px"
                                        image-index="{{ $product->images[$j]->id }}" image-name="">
                                        <a href="{{ asset($mediaPath) }}" data-bs-toggle="modal"
                                            data-bs-target="#imageModal{{ $product->images[$j]->id }}">
                                            @if ($isImage)
                                                <img style="width: 100%; height: 100%" class="border p-1"
                                                    src="{{ asset($mediaPath) }}">
                                            @elseif ($isVideo)
                                                <video style="width: 100%; height: 100%" class="border p-1" muted>
                                                    <source src="{{ asset($mediaPath) }}"
                                                        type="video/{{ $extension }}">
                                                    متصفحك لا يدعم تشغيل الفيديو.
                                                </video>
                                            @else
                                                <div class="border p-1 d-flex align-items-center justify-content-center"
                                                    style="width: 100%; height: 100%; background: #f8f8f8;">
                                                    ملف غير مدعوم
                                                </div>
                                            @endif
                                        </a>
                                        <span class="position-absolute text-center"
                                            style="color: white; width: 20px; height: 20px; background: red; top: 0; border-radius: 10px; padding-top: 1px; right: -10px;cursor: pointer"
                                            onclick="removeImage(this,{{ $product->images[$j]->id }},'هل ترغب فى تاكيد عملية الحذف')">X</span>

                                        <!-- Modal -->
                                        <div class="modal fade" id="imageModal{{ $product->images[$j]->id }}"
                                            tabindex="-1"
                                            aria-labelledby="imageModalLabel{{ $product->images[$j]->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="imageModalLabel{{ $product->images[$j]->id }}">وسائط
                                                            المنتج
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        @if ($isImage)
                                                            <img src="{{ asset($mediaPath) }}" alt="image"
                                                                class="img-fluid">
                                                        @elseif ($isVideo)
                                                            <video controls class="img-fluid">
                                                                <source src="{{ asset($mediaPath) }}"
                                                                    type="video/{{ $extension }}">
                                                                متصفحك لا يدعم تشغيل الفيديو.
                                                            </video>
                                                        @else
                                                            <p>لا يمكن عرض هذا النوع من الملفات.</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endfor
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
                                                <option @if (in_array($addon['id'], $addon_ids->toArray())) selected @endif
                                                    value="{{ $addon->id }}">{{ $addon->display ?: $addon->title }}</option>
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
                                                <option @if (in_array($side['id'], $side_ids->toArray())) selected @endif
                                                    value="{{ $side->id }}">{{ $side->display ?: $side->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-sm-9 offset-sm-3 mt-2">
                            <div class="mb-1">
                                <div class="form-check">
                                    <input type="checkbox" value="1"
                                        @if ($product->is_spicy == 1) checked @endif name="is_spicy"
                                        class="form-check-input" id="isSpicyCheck" />
                                    <label class="form-check-label" for="isSpicyCheck">حار</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-9 offset-sm-3">
                            <div class="mb-1">
                                <div class="form-check">
                                    <input type="checkbox" value="1"
                                        @if ($product->is_vegetarian == 1) checked @endif name="is_vegetarian"
                                        class="form-check-input" id="isSpicyCheck" />
                                    <label class="form-check-label" for="isSpicyCheck">نباتي</label>
                                </div>
                            </div>
                        </div>

                         <div class="col-sm-9 offset-sm-3">
                            <div class="mb-1">
                                <div class="form-check">
                                    <input type="checkbox" value="1"
                                        @if ($product->multi_select_sides == 1) checked @endif name="multi_select_sides"
                                        class="form-check-input" id="multiSelectSidesCheck" />
                                    <label class="form-check-label" for="multiSelectSidesCheck">اختيار متعدد للاطباق
                                        الجانبية</label>
                                </div>
                            </div>
                        </div>


                        <div class="col-sm-9 offset-sm-3">
                            <div class="mb-1">
                                <div class="form-check">
                                    <input type="checkbox" value="1"
                                        @if ($product->is_active == 1) checked @endif name="is_active"
                                        class="form-check-input" id="customCheck2" />
                                    <label class="form-check-label" for="customCheck2">تفعيل</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mb-2">
                            <div class="mb-1 row">
                                <div class="col-sm-9 offset-sm-3">
                                    <div class="form-check">
                                        <input type="checkbox" value="1" name="has_size" class="form-check-input"
                                            id="hasSizeCheck" @if ($sizeAttribute) checked @endif />
                                        <label class="form-check-label" for="hasSizeCheck">هل المنتج له حجم؟</label>
                                    </div>
                                </div>
                            </div>
                        </div>




                        <div id="sizeAttributeSection" style="display: {{ $sizeAttribute ? 'block' : 'none' }};">
                            <div class="divider divider-primary">
                                <div class="divider-text">الأحجام (اختياري)</div>
                            </div>

                            <div class="col-12 mb-2">
                                <div class="mb-1 row">
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3 text-center">
                                                <label class="col-form-label" for="fname-icon"> الاسم باللغة
                                                    العربية</label>
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

                                    @if ($sizeAttribute)
                                        <input type="hidden" name="attribute_id" value="{{ $sizeAttribute->id }}">
                                    @endif
                                    <div class="col-sm-9 offset-sm-3">
                                        <div class="form-check mb-1">
                                            <input type="checkbox" value="1" name="required"
                                                class="form-check-input" id="requiredCheck"
                                                @if ($sizeAttribute && $sizeAttribute->required) checked @endif />
                                            <label class="form-check-label" for="requiredCheck">الزامي</label>
                                        </div>
                                        {{-- <div class="form-check mb-1">
                                            <input type="checkbox" value="1" name="multi_select"
                                                class="form-check-input" id="multiSelectCheck"
                                                @if ($sizeAttribute && $sizeAttribute->multi_select) checked @endif />
                                            <label class="form-check-label" for="multiSelectCheck">اختيار متعدد</label>
                                        </div> --}}
                                        <div class="form-check">
                                            <input type="checkbox" value="1" name="override_price"
                                                class="form-check-input" id="overridePriceCheck"
                                                @if ($sizeAttribute && $sizeAttribute->override_price) checked @endif />
                                            <label class="form-check-label" for="overridePriceCheck">التغيير الي سعر
                                                الاحجام
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div data-repeater-list="sizes">
                                @if ($sizeAttribute && $sizeAttribute->values->count() > 0)
                                    @foreach ($sizeAttribute->values as $value)
                                        <div data-repeater-item>
                                            <div class="row d-flex align-items-end">
                                                <div class="col-md-4 col-12">
                                                    <div class="mb-1">
                                                        <label class="form-label">الاسم بالعربية</label>
                                                        <input type="text" name="name_ar" class="form-control"
                                                            placeholder="الاسم بالعربية"
                                                            value="{{ $value['attribute_value'] }}" />
                                                    </div>
                                                </div>

                                                <div class="col-md-4 col-12">
                                                    <div class="mb-1">
                                                        <label class="form-label">الاسم بالانجليزية</label>
                                                        <input type="text" name="name_en" class="form-control"
                                                            placeholder="الاسم بالانجليزية"
                                                            value="{{ $value->getTranslations('attribute_value')['en'] }}" />
                                                    </div>
                                                </div>

                                                <div class="col-md-2 col-12">
                                                    <div class="mb-1">
                                                        <label class="form-label">السعر</label>
                                                        <input type="number" step=".01" name="price"
                                                            class="form-control" placeholder="السعر"
                                                            value="{{ $value['price'] }}" />
                                                    </div>
                                                </div>

                                                <input type="hidden" name="id" value="{{ $value['id'] }}">

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
                                                    <input type="text" name="name_ar" class="form-control"
                                                        placeholder="الاسم بالعربية" />
                                                </div>
                                            </div>

                                            <div class="col-md-4 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label">الاسم بالانجليزية</label>
                                                    <input type="text" name="name_en" class="form-control"
                                                        placeholder="الاسم بالانجليزية" />
                                                </div>
                                            </div>

                                            <div class="col-md-2 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label">السعر</label>
                                                    <input type="number" step=".01" name="price"
                                                        class="form-control" placeholder="السعر" />
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
                        {{--
                        <div class="divider divider-primary">
                            <div class="divider-text">أنواع المنتج (اختياري)</div>
                        </div>

                        <div data-repeater-list="types">
                            @foreach ($product->types as $type)
                                <div data-repeater-item>
                                    <div class="row d-flex align-items-end">
                                        <div class="col-md-3 col-12">
                                            <div class="mb-1">
                                                <label class="form-label">الاسم بالعربية</label>
                                                <input type="text" value="{{ $type['title'] }}" name="title_ar"
                                                    class="form-control" placeholder="الاسم بالعربية" />
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-12">
                                            <div class="mb-1">
                                                <label class="form-label">الاسم بالانجليزية</label>
                                                <input type="text" value="{{ $type->getTranslations('title')['en'] }}"
                                                    name="title_en" class="form-control"
                                                    placeholder="الاسم بالانجليزية" />
                                            </div>
                                        </div>

                                        <div class="col-md-2 col-12">
                                            <div class="mb-1">
                                                <label class="form-label">السعر</label>
                                                <input type="number" step=".01" value="{{ $type['price'] }}"
                                                    name="price" class="form-control" placeholder="السعر" />
                                            </div>
                                        </div>

                                        <div class="col-md-2 col-12">
                                            <div class="mb-1">
                                                <label class="form-label">الصورة</label>
                                                <input type="file" name="image" class="form-control"
                                                    accept="image/*" />
                                                @if ($type->image)
                                                    <img src="{{ asset('uploads/product_types/' . $type->image) }}"
                                                        style="max-width: 50px; margin-top: 5px;" alt="Type Image">
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-1 col-12">
                                            <div class="mb-1">
                                                <label class="form-label">الترتيب</label>
                                                <input type="number" value="{{ $type['sort_order'] }}"
                                                    name="sort_order" class="form-control" />
                                            </div>
                                        </div>

                                        <input type="hidden" name="id" value="{{ $type['id'] }}">

                                        <div class="col-md-1 col-12">
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
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button class="btn btn-icon btn-primary" type="button" data-repeater-create>
                                    <i data-feather="plus" class="me-25"></i>
                                    <span>اضافة نوع جديد</span>
                                </button>
                            </div>
                        </div> --}}


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
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        function removeImage(button, val, confirmText) {
            if (confirm(confirmText) == true) {
                $(button).parent().remove()
                $.ajax({
                    url: '{{ url('admin/deleteProductPhoto') }}',
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        product_photo_id: val,
                    },
                    success: function(result) {},
                    error: function(error) {
                        console.log(error);
                    }
                });
            }
        }
    </script>
    <script src="{{ asset('') }}admin/vendors/js/forms/select/select2.full.min.js"></script>
    <script src="{{ asset('') }}admin/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
    <script src="{{ asset('') }}admin/js/scripts/forms/form-repeater.js"></script>

    <script>
        var initialCategoryId = "{{ $product['category_id'] ?? '' }}";
        var initialBranchId = "{{ $product['branch_id'] ?? '' }}";

        var $categorySelect = $('#category-select');
        var allCategories = $categorySelect.find('option[value!=""]').map(function() {
            return {
                value: this.value,
                text: $(this).text(),
                branchId: String($(this).data('branchId') || '')
            };
        }).get();

        function filterCategoriesByBranch(branchId) {
            var isInitial = branchId != null && branchId !== '';
            var selectedBranch = isInitial ? String(branchId) : ($('#branch-select').val() || '');

            $categorySelect.empty().append('<option value="">اختر القسم</option>');

            $.each(allCategories, function(_, cat) {
                if (selectedBranch && cat.branchId === selectedBranch) {
                    $categorySelect.append('<option value="' + cat.value + '">' + cat.text + '</option>');
                }
            });

            if (isInitial && initialCategoryId) {
                if (!$categorySelect.find('option[value="' + initialCategoryId + '"]').length) {
                    var initialCat = allCategories.find(function(c) {
                        return c.value === initialCategoryId;
                    });
                    if (initialCat) {
                        $categorySelect.append('<option value="' + initialCat.value + '">' + initialCat.text + '</option>');
                    }
                }
                $categorySelect.val(initialCategoryId);
            } else {
                $categorySelect.val('');
            }

            $categorySelect.trigger('change');

            if ($categorySelect.hasClass('select2-hidden-accessible')) {
                $categorySelect.select2('destroy').select2({
                    dropdownAutoWidth: true,
                    width: '100%',
                    dropdownParent: $categorySelect.parent()
                });
            }
        }

        if ($('#branch-select').length) {
            filterCategoriesByBranch(initialBranchId);
        }
    </script>

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

        document.addEventListener('DOMContentLoaded', function() {
            const hasSizeCheck = document.getElementById('hasSizeCheck');
            const sizeAttributeSection = document.getElementById('sizeAttributeSection');

            hasSizeCheck.addEventListener('change', function() {
                if (this.checked) {
                    sizeAttributeSection.style.display = 'block';
                } else {
                    sizeAttributeSection.style.display = 'none';
                }
            });
        });
    </script>
@endsection
