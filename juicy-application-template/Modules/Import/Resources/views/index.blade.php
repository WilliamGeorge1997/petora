@extends('common::layouts.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}admin/vendors/css/forms/select/select2.min.css">
@endsection

@section('content')
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">استيراد القائمة</h4>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success" role="alert">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-2">
                    <button type="button" class="btn btn-sm btn-primary" id="copy-import-template">
                        نسخ القالب
                    </button>
                    <button type="button" class="btn btn-sm btn-secondary" id="copy-import-instructions">
                        نسخ التعليمات
                    </button>
                    <span id="copy-import-template-feedback" class="text-success ms-1 d-none">تم النسخ</span>
                    <span id="copy-import-instructions-feedback" class="text-success ms-1 d-none">تم النسخ</span>
                </div>

                <textarea id="import-template" class="d-none" readonly dir="ltr">{{ $importTemplateJson }}</textarea>
                <textarea id="import-instructions" class="d-none" readonly dir="ltr">{{ $importInstructions }}</textarea>

                <form class="form form-horizontal" method="POST" action="{{ route('import.show') }}">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="branch-select">الفرع</label>
                                </div>
                                <div class="col-sm-9">
                                    <select class="select2 form-select" id="branch-select" name="branch_id" required>
                                        <option value="">اختر الفرع</option>
                                        @foreach (\Modules\Branch\Entities\Branch::active()->get() as $branch)
                                            <option value="{{ $branch->id }}"
                                                {{ (string) old('branch_id') === (string) $branch->id ? 'selected' : '' }}>
                                                {{ $branch->getTranslations('title')['ar'] ?? '' }}
                                                -
                                                {{ $branch->getTranslations('title')['en'] ?? '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('branch_id')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="import-json">JSON القائمة</label>
                                </div>
                                <div class="col-sm-9">
                                    <textarea id="import-json" name="json" class="form-control font-monospace" rows="8" dir="ltr"
                                        placeholder='{"categories": [...], "addons": [...]}' required>{{ old('json') }}</textarea>
                                    <small class="text-muted">الصق JSON من الذكاء الاصطناعي (يدعم لصق داخل كود json
                                        أيضاً).</small>
                                    @error('json')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-9 offset-sm-3">
                                    <button type="submit" class="btn btn-primary me-1"
                                        formaction="{{ route('import.show') }}">
                                        معاينة
                                    </button>
                                    <button type="submit" class="btn btn-success" formaction="{{ route('import.store') }}"
                                        onclick="return confirm('تأكيد استيراد القائمة لهذا الفرع؟');">
                                        استيراد
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                @if (session('import_preview'))
                    @php($preview = session('import_preview'))
                    <div class="alert alert-success mb-2 mt-2" role="alert">
                        <strong>معاينة:</strong>
                        {{ $preview['categories_count'] ?? 0 }} فئة،
                        {{ $preview['products_count'] ?? 0 }} منتج
                        @if (($preview['addons_count'] ?? 0) > 0)
                            ، {{ $preview['addons_count'] }} إضافة
                            ({{ $preview['addon_values_count'] ?? 0 }} قيمة)
                        @endif
                        .
                    </div>

                    @if (!empty($preview['categories']))
                        <div class="mb-2">
                            <div class="overflow-auto" style="max-height: 36rem;">
                                <div class="accordion accordion-margin" id="importPreviewAccordion">
                                    @foreach ($preview['categories'] as $category)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="importHeading{{ $loop->index }}">
                                                <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}"
                                                    type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#importCollapse{{ $loop->index }}"
                                                    aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                                    aria-controls="importCollapse{{ $loop->index }}">
                                                    <span class="me-50">{{ $category['title']['ar'] ?? '' }}</span>
                                                    <span
                                                        class="text-muted small">({{ $category['title']['en'] ?? '' }})</span>
                                                    <span
                                                        class="badge bg-primary ms-50">{{ $category['products_count'] ?? 0 }}
                                                        منتج</span>
                                                </button>
                                            </h2>
                                            <div id="importCollapse{{ $loop->index }}"
                                                class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                                                aria-labelledby="importHeading{{ $loop->index }}"
                                                data-bs-parent="#importPreviewAccordion">
                                                <div class="accordion-body pt-1">
                                                    @include('import::partials.category-products', [
                                                        'category' => $category,
                                                        'showHeader' => false,
                                                    ])
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (!empty($preview['addons']))
                        <h5 class="mt-2 mb-1">الإضافات / الصلصات</h5>
                        <div class="mb-2">
                            <div class="overflow-auto" style="max-height: 24rem;">
                                <div class="accordion accordion-margin" id="importAddonsAccordion">
                                    @foreach ($preview['addons'] as $addon)
                                        @include('import::partials.addon-preview', [
                                            'addon' => $addon,
                                            'index' => $loop->index,
                                            'loopFirst' => $loop->first,
                                        ])
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('') }}admin/vendors/js/forms/select/select2.full.min.js"></script>
    <script>
        $(function() {
            $('#branch-select').select2({
                dropdownAutoWidth: true,
                width: '100%',
                dropdownParent: $('#branch-select').parent()
            });

            function copyHidden(textareaId, feedbackId) {
                var el = document.getElementById(textareaId);
                navigator.clipboard.writeText(el.value).then(function() {
                    $('#' + feedbackId).removeClass('d-none');
                    setTimeout(function() {
                        $('#' + feedbackId).addClass('d-none');
                    }, 2000);
                });
            }

            $('#copy-import-template').on('click', function() {
                copyHidden('import-template', 'copy-import-template-feedback');
            });

            $('#copy-import-instructions').on('click', function() {
                copyHidden('import-instructions', 'copy-import-instructions-feedback');
            });
        });
    </script>
@endsection
