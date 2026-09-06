@props([
    'id',
    'title' => null,
    'submitLabel' => null,
    'submitTheme' => 'primary',
    'centered' => true,
    'scrollable' => true,
    'size' => null,
    'bodyClass' => null,
])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog {{ $centered ? 'modal-dialog-centered' : '' }} {{ $scrollable ? 'modal-dialog-scrollable' : '' }} {{ $size }}">
        <div class="modal-content">
            <div class="modal-header">
                @if ($title)
                    <h5 class="modal-title" id="{{ $id }}Label">{{ $title }}</h5>
                @endif
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body {{ $bodyClass }}">
                {{ $slot }}
            </div>
            @if ($submitLabel || isset($footer))
                <div class="modal-footer">
                    @if (isset($footer))
                        {{ $footer }}
                    @else
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-{{ $submitTheme }}" onclick="$('#{{ $id }} form').submit()">{{ $submitLabel }}</button>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
