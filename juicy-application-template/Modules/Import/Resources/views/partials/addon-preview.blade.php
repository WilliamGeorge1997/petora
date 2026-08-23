<div class="accordion-item">
    <h2 class="accordion-header" id="importAddonHeading{{ $index }}">
        <button class="accordion-button {{ $loopFirst ?? false ? '' : 'collapsed' }}" type="button"
            data-bs-toggle="collapse" data-bs-target="#importAddonCollapse{{ $index }}"
            aria-expanded="{{ $loopFirst ?? false ? 'true' : 'false' }}"
            aria-controls="importAddonCollapse{{ $index }}">
            <span class="me-50">{{ $addon['title']['ar'] ?? '' }}</span>
            <span class="text-muted small">({{ $addon['title']['en'] ?? '' }})</span>
            <span class="badge bg-warning ms-50">{{ count($addon['values'] ?? []) }} قيمة</span>
            @if ($addon['multi_select'] ?? true)
                <span class="badge bg-light-info ms-50">اختيار متعدد</span>
            @endif
        </button>
    </h2>
    <div id="importAddonCollapse{{ $index }}"
        class="accordion-collapse collapse {{ $loopFirst ?? false ? 'show' : '' }}"
        aria-labelledby="importAddonHeading{{ $index }}" data-bs-parent="#importAddonsAccordion">
        <div class="accordion-body pt-1">
            <ul class="list-group list-group-flush">
                @foreach ($addon['values'] ?? [] as $value)
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div>
                            <strong>{{ $value['title']['ar'] ?? '' }}</strong>
                            <span class="text-muted small">/ {{ $value['title']['en'] ?? '' }}</span>
                        </div>
                        <span class="badge bg-primary">{{ $value['price'] ?? 0 }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
