<div class="col">
    <div class="card h-100 border shadow-sm">
        <div class="ratio ratio-1x1">
            <div class="d-flex flex-column p-2 small overflow-auto">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <span class="badge bg-light-secondary">#{{ $product['sort_order'] ?? 1 }}</span>
                    <div class="text-end">
                        <div class="fw-bold text-primary">{{ $product['price'] ?? 0 }}</div>
                        @if (!empty($product['discounted_price']))
                            <div class="text-success">خصم {{ $product['discounted_price'] }}</div>
                        @endif
                    </div>
                </div>

                <h6 class="mb-0 text-truncate">{{ $product['title']['ar'] ?? '' }}</h6>
                <div class="text-muted text-truncate mb-1">{{ $product['title']['en'] ?? '' }}</div>

                <div class="d-flex flex-wrap gap-50 mb-1">
                    @if ($product['is_active'] ?? true)
                        <span class="badge bg-light-success">نشط</span>
                    @else
                        <span class="badge bg-light-danger">غير نشط</span>
                    @endif
                    @if (!empty($product['is_spicy']))
                        <span class="badge bg-light-warning">حار</span>
                    @endif
                    @if (!empty($product['is_vegetarian']))
                        <span class="badge bg-light-info">نباتي</span>
                    @endif
                    @if (!empty($product['calories']))
                        <span class="badge bg-light-secondary">{{ $product['calories'] }}</span>
                    @endif
                </div>

                @if (!empty($product['description']['ar']) || !empty($product['description']['en']))
                    <p class="text-muted mb-1">
                        @if (!empty($product['description']['ar']))
                            <span class="d-block text-truncate">{{ $product['description']['ar'] }}</span>
                        @endif
                        @if (!empty($product['description']['en']))
                            <span class="d-block text-truncate">{{ $product['description']['en'] }}</span>
                        @endif
                    </p>
                @endif

                @if (!empty($product['allergens']['ar']) || !empty($product['allergens']['en']))
                    <p class="text-muted mb-1">
                        <span class="fw-bold">حساسية:</span>
                        {{ $product['allergens']['ar'] ?? '' }}
                        @if (!empty($product['allergens']['en']))
                            / {{ $product['allergens']['en'] }}
                        @endif
                    </p>
                @endif

                @if (!empty($product['sizes']))
                    <ul class="list-unstyled mb-0 border-top pt-1">
                        @foreach ($product['sizes'] as $size)
                            <li class="d-flex justify-content-between gap-50">
                                <span class="text-truncate">{{ $size['name_ar'] ?? '' }} /
                                    {{ $size['name_en'] ?? '' }}</span>
                                <span class="fw-bold text-primary">{{ $size['price'] ?? 0 }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>
