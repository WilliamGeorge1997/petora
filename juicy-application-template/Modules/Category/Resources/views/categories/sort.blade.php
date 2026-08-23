@extends('common::layouts.master')

@section('css')
    <style>
        .category-sort-item {
            display: flex;
            align-items: center;
            padding: 10px;
            margin-bottom: 5px;
            background: #f7f7f7;
            border: 1px solid #ecdfdf;
            border-radius: 5px;
            cursor: grab;
            width: 50%;
        }

        .category-sort-item:active {
            cursor: grabbing;
            background: rgb(246, 246, 246);
        }

        .category-order-indicator {
            width: 30px;
            text-align: center;
            margin-right: 10px;
            font-weight: bold;
        }
    </style>
@endsection

@section('content')
    <section>
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">ترتيب الاقسام</h4>
            </div>
            <div class="card-body">
                @if (auth('admin')->user()->hasRole('Super Admin'))
                    <form method="GET" class="mb-2">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-1 row">
                                    <div class="col-sm-3 text-center">
                                        <label class="col-form-label" for="pass-icon">الفرع</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <select name="branch_id" class="form-select select2" onchange="this.form.submit()">
                                            <option value="">اختر الفرع</option>
                                            @foreach (\Modules\Branch\Entities\Branch::available()->active()->get() as $branch)
                                                <option value="{{ $branch->id }}"
                                                    @if ($branch_id == $branch->id) selected @endif>
                                                    {{ $branch->title ?? $branch->getTranslations('title')['ar'] . ' - ' . $branch->getTranslations('title')['en'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('branch_id')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                @endif
                @if ($categories->isEmpty())
                    <p class="text-center alert alert-info">لا توجد فئات.</p>
                @else
                    <form method="POST" action="{{ route('categories.sort.update') }}">
                        @csrf
                        <div id="category-sort-list">
                            @foreach ($categories as $category)
                                <div class="category-sort-item" data-id="{{ $category->id }}">
                                    <span class="drag-handle me-50">☰</span>
                                    <span class="category-order-indicator me-50">{{ $loop->iteration }}</span>
                                    <span
                                        class="category-name">{{ $category->getTranslations('title')['ar'] . ' - ' . $category->getTranslations('title')['en'] }}</span>
                                    <input type="hidden" name="sort_order[]" value="{{ $category->id }}">
                                </div>
                            @endforeach
                            <input type="hidden" name="branch_id" value="{{ $branch_id }}">
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">حفظ الترتيب</button>
                    </form>
                @endif
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script src="{{ asset('admin/vendors/js/extensions/sortable.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const list = document.getElementById('category-sort-list');
            new Sortable(list, {
                animation: 150,
                onEnd: function() {
                    const items = list.querySelectorAll('.category-sort-item');
                    items.forEach((el, index) => {
                        el.querySelector('.category-order-indicator').textContent = index + 1;
                        el.querySelector('input[name="sort_order[]"]').value = el.dataset.id;
                    });
                }
            });
        });
    </script>
@endsection
