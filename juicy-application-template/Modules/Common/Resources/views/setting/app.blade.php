@extends('common::layouts.master')

@section('css')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('') }}admin/css-rtl/plugins/extensions/ext-component-sweet-alerts.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">تعديل اعدادات التطبيق</h4>
            </div>
            <div class="card-body">
                <form class="form form-horizontal" action="{{ url('admin/setting') }}" method="POST"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        @foreach ($settings as $setting)
                            @if ($setting->type == 'file')
                                {{-- Image Inputs --}}
                                <div class="col-12">
                                    <div class="mb-1 row align-items-center">
                                        <div class="col-sm-3 text-center">
                                            <label class="col-form-label" for="fname-icon"> {{ $setting->display }}</label>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="input-group input-group-merge">
                                                <span class="input-group-text"><i data-feather="user"></i></span>
                                                <input type="{{ $setting->type }}" class="form-control"
                                                    name="{{ $setting->key }}" />
                                            </div>
                                        </div>
                                        @if ($setting->value)
                                            <div class="col-sm-3">
                                                <figure style="width: 100px; height: 100px;" id="image-{{ $setting->key }}"
                                                    class="position-relative">
                                                    <button onclick="removeImage('{{ $setting->key }}')" type="button"
                                                        class="btn-close bg-danger rounded-circle  position-absolute start-0"
                                                        style="top:-10px" aria-label="Close"></button>
                                                    <img width="100" height="100" src="{{ $setting->value }}"
                                                        alt="{{ $setting->display }}" style="cursor: pointer; padding: 5px;"
                                                        class="image-preview border"
                                                        onclick="openImageModal('{{ $setting->value }}', '{{ $setting->display }}')">
                                                </figure>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @else
                                {{-- Color Inputs --}}
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3 text-center">
                                            <label class="col-form-label" for="fname-icon"> {{ $setting->display }}</label>
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="{{ $setting->type }}" class="form-control"
                                                name="{{ $setting->key }}" placeholder="{{ $setting->key }}"
                                                value="{{ $setting->value }}" />
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit" class="btn btn-primary me-1">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">معاينة الصورة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="" class="img-fluid"
                        style="max-width: 100%; max-height: 70vh;">
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('') }}admin/vendors/js/extensions/sweetalert2.all.min.js"></script>
    @if (session('updated'))
        <script>
            Swal.fire({
                title: 'أحسنت!',
                text: 'لقد تم تعديل البيانات بنجاح',
                icon: 'success',
                customClass: {
                    confirmButton: 'btn btn-primary'
                },
                buttonsStyling: false
            });
        </script>
    @endif

    {{-- <script src="//cdn.ckeditor.com/4.16.0/full/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('about_ar');
        CKEDITOR.replace('about_en');
        CKEDITOR.replace('terms_ar');
        CKEDITOR.replace('terms_en');
    </script> --}}

    <script>
        function removeImage(key) {
            if (confirm('هل أنت متأكد من حذف الصورة؟')) {
                $.ajax({
                    url: '{{ url('admin/removeImage') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        key: key
                    },
                    success: function(result) {
                        if (result.status) {
                            $('#image-' + key).remove();
                            Swal.fire({
                                title: 'أحسنت!',
                                text: 'لقد تم حذف الصورة بنجاح',
                                icon: 'success',
                                customClass: {
                                    confirmButton: 'btn btn-primary'
                                },
                                buttonsStyling: false
                            });
                        }
                    }
                });
            }

        }
    </script>

    <script>
        function openImageModal(imageSrc, imageAlt) {
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('modalImage').alt = imageAlt;
            document.getElementById('imageModalLabel').textContent = imageAlt;

            var imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
            imageModal.show();
        }
    </script>
@endsection
