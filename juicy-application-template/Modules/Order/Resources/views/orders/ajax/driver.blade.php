      @foreach ($viewModel->drivers() as $key => $driver)
          <option @if ($order->driver_id == $driver->id) selected @endif value="{{ $driver->id }}">{{ $driver->name }}
              (يبعد عنك {{ $order->getdistance[$key] }} كم)
          </option>
      @endforeach
