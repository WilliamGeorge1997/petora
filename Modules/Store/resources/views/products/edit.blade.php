@php
    $locale = app()->getLocale();
@endphp
@extends('common::layouts.master')

@section('title', __('store::general.edit'))

@section('content')
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('store::general.edit') }} {{ $product->getTranslation('title', $locale) }} - {{ $store->getTranslation('title', $locale) }}</h4>
            </div>
            <div class="card-body">
                <form class="form form-horizontal" action="{{ route('admin.store.products.update', [$store->id, $product->id]) }}" method="POST"
                    enctype="multipart/form-data">
                    {{ method_field('PUT') }}
                    {{ csrf_field() }}
                    <div class="row">
                        {{-- Master Product Reference --}}
                        <div class="col-12 mb-2">
                            <div class="alert alert-info mb-0" role="alert">
                                <div class="alert-body d-flex align-items-center">
                                    <i data-feather="info" class="me-50 font-medium-3"></i>
                                    <div>
                                        <strong>{{ __('store::general.product_override.master_details') }}:</strong>
                                        <span>{{ __('store::general.product_override.notice') }}</span>
                                    </div>
                                </div>
                                <div class="mt-1 d-flex flex-wrap gap-2 text-dark font-small-3">
                                    <div><strong>{{ __('product::attribute.title_ar') }}:</strong> {{ $product->getTranslation('title', 'ar', false) ?: '-' }}</div>
                                    <div><strong>{{ __('product::attribute.title_en') }}:</strong> {{ $product->getTranslation('title', 'en', false) ?: '-' }}</div>
                                    <div><strong>{{ __('product::attribute.price') }}:</strong> {{ $product->price }}</div>
                                    @if($product->category)
                                        <div><strong>{{ __('product::attribute.category_id') }}:</strong> {{ $product->category->getTranslation('title', $locale) }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Title ar --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="title_ar">{{ __('product::attribute.title_ar') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="type"></i></span>
                                        <input type="text" id="title_ar"
                                            value="{{ old('title_ar', $sellerProduct->getTranslation('title', 'ar', false)) }}" class="form-control"
                                            name="title_ar" placeholder="{{ $product->getTranslation('title', 'ar', false) }}" />
                                        @error('title_ar')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Title en --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="title_en">{{ __('product::attribute.title_en') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="type"></i></span>
                                        <input type="text" id="title_en"
                                            value="{{ old('title_en', $sellerProduct->getTranslation('title', 'en', false)) }}" class="form-control"
                                            name="title_en" placeholder="{{ $product->getTranslation('title', 'en', false) }}" />
                                        @error('title_en')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Description ar --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="description_ar">{{ __('product::attribute.description_ar') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="file-text"></i></span>
                                        <textarea class="form-control" name="description_ar" id="description_ar"
                                            placeholder="{{ $product->getTranslation('description', 'ar', false) }}">{{ old('description_ar', $sellerProduct->getTranslation('description', 'ar', false)) }}</textarea>
                                        @error('description_ar')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Description en --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="description_en">{{ __('product::attribute.description_en') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="file-text"></i></span>
                                        <textarea class="form-control" name="description_en" id="description_en"
                                            placeholder="{{ $product->getTranslation('description', 'en', false) }}">{{ old('description_en', $sellerProduct->getTranslation('description', 'en', false)) }}</textarea>
                                        @error('description_en')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Price --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="price">{{ __('product::attribute.price') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="dollar-sign"></i></span>
                                        <input type="number" step="0.01" min="0" id="price" class="form-control" name="price"
                                            value="{{ old('price', $sellerProduct->price) }}"
                                            placeholder="{{ __('product::attribute.price') }}" required />
                                        @error('price')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Image --}}
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label"
                                        for="images">{{ __('product::attribute.images') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    @if ($sellerProduct->images->isNotEmpty())
                                        <div class="mb-1">
                                            <span class="badge badge-light-primary mb-50">{{ __('store::general.product_override.custom_images') }}</span>
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach ($sellerProduct->images as $img)
                                                    <div class="position-relative d-inline-block" id="image-wrapper-{{ $img->id }}">
                                                        <img class="rounded border width-100 height-100 cursor-pointer" role="button"
                                                            style="width: 100px; height: 100px; object-fit: cover;"
                                                            data-bs-toggle="modal" data-bs-target="#sellerImageModal{{ $img->id }}"
                                                            src="{{ asset($img->image) }}"
                                                            alt="Seller Product Image">
                                                        <button type="button" class="btn btn-icon btn-sm btn-danger position-absolute top-0 end-0 m-25 delete-seller-image"
                                                            data-id="{{ $img->id }}"
                                                            title="{{ __('store::general.delete') }}">
                                                            <i data-feather="trash-2" style="width: 12px; height: 12px;"></i>
                                                        </button>
                                                    </div>
                                                    <x-common::modal id="sellerImageModal{{ $img->id }}" title="{{ __('product::attribute.images') }}" body-class="text-center">
                                                        <img src="{{ asset($img->image) }}" class="img-fluid rounded border" style="max-height: 500px; object-fit: contain;"
                                                            alt="Seller Product Image">
                                                    </x-common::modal>
                                                @endforeach
                                            </div>
                                        </div>
                                    @elseif ($product->images->isNotEmpty())
                                        <div class="mb-1">
                                            <span class="badge badge-light-secondary mb-50">{{ __('store::general.product_override.master_images') }}</span>
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach ($product->images as $masterImg)
                                                    <div class="d-inline-block">
                                                        <img class="rounded border width-100 height-100 cursor-pointer opacity-75" role="button"
                                                            style="width: 100px; height: 100px; object-fit: cover;"
                                                            data-bs-toggle="modal" data-bs-target="#masterImageModal{{ $masterImg->id }}"
                                                            src="{{ asset($masterImg->image) }}"
                                                            alt="Master Product Image"
                                                            title="{{ __('store::general.product_override.master_images') }}">
                                                    </div>
                                                    <x-common::modal id="masterImageModal{{ $masterImg->id }}" title="{{ __('store::general.product_override.master_images') }}" body-class="text-center">
                                                        <img src="{{ asset($masterImg->image) }}" class="img-fluid rounded border" style="max-height: 500px; object-fit: contain;"
                                                            alt="Master Product Image">
                                                    </x-common::modal>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="image"></i></span>
                                        <input type="file" id="images" class="form-control" name="images[]"
                                            placeholder="{{ __('product::attribute.images') }}" accept="image/*" multiple />
                                    </div>
                                    @error('images')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                    @error('images.*')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Is Active --}}
                        <div class="col-sm-9 offset-sm-3">
                            <div class="mb-1">
                                <div class="form-check">
                                    <input type="checkbox" value="1" @if (old('is_active', $sellerProduct->is_active)) checked @endif
                                        name="is_active" class="form-check-input" id="customCheck2" />
                                    <label class="form-check-label"
                                        for="customCheck2">{{ __('product::attribute.is_active') }}</label>
                                </div>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit" class="btn btn-primary me-1"><i data-feather="edit"
                                    class="me-50"></i>{{ __('store::general.update') }}</button>
                            <a href="{{ route('admin.store.products.index', $store->id) }}"
                                class="btn btn-outline-secondary">{{ __('store::general.cancel') }}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(function() {
            'use strict';
            var token = $('meta[name="csrf-token"]').attr('content');

            $('.delete-seller-image').on('click', function() {
                let that = this;
                var imageId = $(this).data('id');
                var wrapper = $('#image-wrapper-' + imageId);
                var deleteUrl = "{{ route('admin.store.products.images.destroy', [$store->id, $product->id, ':image_id']) }}".replace(':image_id', imageId);

                Swal.fire({
                    title: '{{ __('store::general.sure_delete') }}',
                    text: '{{ __('store::general.cant_revert') }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '{{ __('store::general.yes_delete') }}',
                    cancelButtonText: '{{ __('store::general.cancel') }}',
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-outline-danger ms-1'
                    },
                    buttonsStyling: false
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            url: deleteUrl,
                            type: 'POST',
                            data: {
                                _method: 'DELETE',
                                _token: token
                            }
                        }).done(function(response) {
                            wrapper.fadeOut(300, function() {
                                $(this).remove();
                            });
                            successAlert(response.message);
                        }).fail(function() {
                            errorAlert();
                        });
                    }
                });
            });
        });
    </script>
@endsection
