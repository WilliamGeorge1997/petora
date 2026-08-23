@foreach ($viewModel->orderStatuses() as $status)
    @if ($status->id == 1)
        @continue
    @endif
    <option @if ($order->order_status_id == $status->id) selected @endif value="{{ $status->id }}">{{ $status->title }}
    </option>
@endforeach
