@php
    $locale = app()->getLocale();
@endphp
@extends('common::layouts.master')

@section('title', __('notification::general.list'))

@section('content')
    <section id="default-breadcrumb">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('notification::general.title') }}</h4>
                        <a href="{{ route('admin.notifications.create') }}" class="btn btn-primary">
                            {{ __('notification::general.create') }}
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('notification::attribute.title') }}</th>
                                        <th>{{ __('notification::attribute.description') }}</th>
                                        <th>{{ __('notification::attribute.notifiable_type') }}</th>
                                        <th>{{ __('notification::attribute.created_at') }}</th>
                                        <th>{{ __('notification::general.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($notifications as $notification)
                                        <tr>
                                            <td>{{ $notification->id }}</td>
                                            <td>{{ $notification->title }}</td>
                                            <td>{{ \Illuminate\Support\Str::limit($notification->description, 50) }}</td>
                                            <td>{{ class_basename($notification->notifiable_type) ?: '-' }}</td>
                                            <td>{{ $notification->created_at?->format('Y-m-d H:i') }}</td>
                                            <td>
                                                <form action="{{ route('admin.notifications.destroy', $notification->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('notification::general.delete_confirm') }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">{{ __('notification::message.fetched') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if($notifications instanceof \Illuminate\Contracts\Pagination\Paginator)
                            <div class="mt-3">
                                {{ $notifications->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
