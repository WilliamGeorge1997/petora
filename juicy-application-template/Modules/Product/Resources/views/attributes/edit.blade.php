@extends('common::layouts.master')

@section('content')
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">تعديل خاصيه {{ $product_attribute['attribute_ar'] }}</h4>
            </div>
            <div class="card-body">
                <form class="form form-horizontal invoice-repeater"
                    action="{{ url('admin/attribute/' . $product_attribute['id'] . '/update') }}" method="POST"
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
                                        <input type="text" value="{{ $product_attribute['title'] }}" class="form-control"
                                            name="attribute_ar" placeholder="الاسم باللغة العربية" />
                                        @error('attribute_ar')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" value="{{ $product_attribute['product_id'] }}" name="product_id">

                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="fname-icon"> الاسم باللغة الانجليزية</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="text"
                                            value="{{ $product_attribute->getTranslations('title')['en'] }}" id="fname-icon"
                                            class="form-control" name="attribute_en"
                                            placeholder="الاسم باللغة الانجليزية" />
                                        @error('attribute_en')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-9 offset-sm-3">
                            <div class="mb-1">
                                <div class="form-check">
                                    <input type="checkbox" @if ($product_attribute['required'] == 1) checked @endif value="1"
                                        name="required" class="form-check-input" />
                                    <label class="form-check-label">الزامي</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-9 offset-sm-3">
                            <div class="mb-1">
                                <div class="form-check">
                                    <input type="checkbox" @if ($product_attribute['multi_select'] == 1) checked @endif value="1"
                                        name="multi_select" class="form-check-input" />
                                    <label class="form-check-label">اختيار متعدد</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-9 offset-sm-3">
                            <div class="mb-1">
                                <div class="form-check">
                                    <input type="checkbox" @if ($product_attribute['override_price'] == 1) checked @endif value="1"
                                        name="override_price" class="form-check-input" />
                                    <label class="form-check-label">تعديل علي السعر الاساسي</label>
                                </div>
                            </div>
                        </div>


                        <div class="divider divider-primary">
                            <div class="divider-text">قيم الخاصية</div>
                        </div>

                        <div data-repeater-list="attribute_values">
                            @foreach ($product_attribute['values'] as $value)
                                <div data-repeater-item>
                                    <div class="row d-flex align-items-end">
                                        <div class="col-md-2 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="itemname">قيمة الخاصية باللغة العربية</label>
                                                <input type="text" value="{{ $value['attribute_value'] }}"
                                                    name="value_ar" class="form-control" id="itemname"
                                                    aria-describedby="itemname" placeholder="قيمة الخاصية باللغة العربية" />
                                            </div>
                                        </div>

                                        <div class="col-md-2 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="itemname">قيمة الخاصية باللغة
                                                    الانجليزية</label>
                                                <input type="text"
                                                    value="{{ $value->getTranslations('attribute_value')['en'] }}"
                                                    name="value_en" class="form-control" id="itemname"
                                                    aria-describedby="itemname"
                                                    placeholder="قيمة الخاصية باللغة الانجليزية" />
                                            </div>
                                        </div>

                                        <div class="col-md-2 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="itemquantity">السعر</label>
                                                <input type="number" step=".01" value="{{ $value['price'] }}"
                                                    name="price" class="form-control" id="itemquantity"
                                                    aria-describedby="itemquantity" placeholder="السعر" />
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-12">
                                            <div class="d-flex gap-1">
                                                <div class="mb-1">
                                                    <label class="form-label" for="image">الصورة</label>
                                                    <input type="file" name="image" class="form-control"
                                                        id="itemImage" aria-describedby="image" placeholder="image" />
                                                </div>
                                                @if ($value['image'])
                                                    <picture
                                                        class="border d-flex justify-content-center align-items-center"
                                                        id="imageContainer">
                                                        <img width="75" height="75" id="image"
                                                            src="{{ $value['image'] }}"
                                                            alt="{{ $value['attribute_value'] }}">
                                                    </picture>
                                                @endif

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
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button class="btn btn-icon btn-primary" type="button" data-repeater-create>
                                    <i data-feather="plus" class="me-25"></i>
                                    <span>اضافة جديد</span>
                                </button>
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
    <script src="{{ asset('') }}admin/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
    <script src="{{ asset('') }}admin/js/scripts/forms/form-repeater.js"></script>

    @if (session('error'))
        <script>
            Swal.fire({
                title: 'خطأ!',
                text: @if (session('error') === 'error')
                    'لا يمكن ادخال اكثر من خاصية تقوم بالتعديل علي قيمة سعر المنتج'
                @else
                    '{{ session('error') }}'
                @endif ,
                icon: 'error',
                customClass: {
                    confirmButton: 'btn btn-danger'
                },
                buttonsStyling: false
            });
        </script>
    @endif
    <script>
        $(document).on('click', '[data-repeater-create]', function() {
            $('[data-repeater-item]').last().find('picture').remove();
        });
    </script>
@endsection
