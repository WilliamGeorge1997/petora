@if ($showHeader ?? true)
    <div
        class="d-flex flex-wrap align-items-center gap-1 mb-2 {{ !empty($isSubcategory) ? 'ps-2 border-start border-3 border-secondary' : '' }}">
        @if (!empty($isSubcategory))
            <span class="badge bg-light-secondary">قسم فرعي</span>
        @endif
        <div class="flex-grow-1">
            <strong>{{ $category['title']['ar'] ?? '' }}</strong>
            <span class="text-muted">/ {{ $category['title']['en'] ?? '' }}</span>
        </div>
        <span class="badge bg-light-primary">ترتيب {{ $category['sort_order'] ?? 1 }}</span>
        <span class="badge bg-light-dark">{{ $category['products_count'] ?? 0 }} منتج</span>
        @if ($category['is_active'] ?? true)
            <span class="badge bg-light-success">نشط</span>
        @else
            <span class="badge bg-light-danger">غير نشط</span>
        @endif
    </div>
@endif

@if (count($category['products'] ?? []) > 0)
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-2 mb-2">
        @foreach ($category['products'] as $product)
            @include('import::partials.product-card', ['product' => $product])
        @endforeach
    </div>
@elseif (empty($category['subcategories']))
    <p class="text-muted small mb-2">لا توجد منتجات في هذا القسم.</p>
@endif

@if (!empty($category['subcategories']))
    @foreach ($category['subcategories'] as $subcategory)
        <div class="bg-light rounded p-2 mb-2">
            @include('import::partials.category-products', [
                'category' => $subcategory,
                'isSubcategory' => true,
                'showHeader' => true,
            ])
        </div>
    @endforeach
@endif
