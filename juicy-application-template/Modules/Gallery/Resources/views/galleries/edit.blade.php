@extends('common::layouts.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}admin/css-rtl/plugins/forms/form-validation.css">
@endsection

@section('content')
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">تعديل صورة صور المنيو</h4>
            </div>
            <div class="card-body">
                <form class="form form-horizontal" action="{{ url('admin/galleries/' . $gallery->id) }}" method="POST"
                    enctype="multipart/form-data">
                    {{ method_field('PUT') }}
                    {{ csrf_field() }}

                    {{-- Sort order --}}
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="sort_order">الترتيب</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="list"></i></span>
                                        <input type="number" id="sort_order" class="form-control" name="sort_order"
                                            placeholder="الترتيب" value="{{ old('sort_order', $gallery->sort_order) }}"
                                            min="0" />
                                    </div>
                                    @error('sort_order')
                                        <p class="alert alert-danger mt-25">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Image --}}
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-1 row align-items-center">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="image">الصورة</label>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="image"></i></span>
                                        <input type="file" id="image" class="form-control" name="image"
                                            accept="image/jpeg,image/png,image/jpg,image/webp,image/bmp" />
                                    </div>
                                    @error('image')
                                        <p class="alert alert-danger mt-25">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    @if ($gallery->image)
                                        <a href="{{ $gallery->image }}" data-bs-toggle="modal" data-bs-target="#imageModal">
                                            <img src="{{ $gallery->image }}" alt="gallery image"
                                                class="img-fluid border p-1" width="150" height="150"
                                                style="object-fit: cover;">
                                        </a>
                                        <!-- Modal -->
                                        <div class="modal fade" id="imageModal" tabindex="-1"
                                            aria-labelledby="imageModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="imageModalLabel">صورة الجاليري</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <img src="{{ $gallery->image }}" alt="gallery image"
                                                            class="img-fluid">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Is active --}}
                    <div class="col-sm-9 offset-sm-3">
                        <div class="mb-1">
                            <div class="form-check">
                                <input type="checkbox" value="1" name="is_active" class="form-check-input"
                                    id="is_active" @if ($gallery->is_active == 1) checked @endif />
                                <label class="form-check-label" for="is_active">تفعيل</label>
                            </div>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="col-sm-9 offset-sm-3">
                        <button type="submit" class="btn btn-primary me-1">تعديل</button>
                        <a href="{{ url('admin/galleries') }}" class="btn btn-outline-secondary">الغاء</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
