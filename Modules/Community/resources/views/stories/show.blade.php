@extends('common::layouts.master')

@section('title', 'View Story')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Story Details</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-3 fw-bold">Client:</div>
                        <div class="col-md-9">{{ $story->client?->name ?? 'N/A' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 fw-bold">Expires At:</div>
                        <div class="col-md-9">{{ $story->expires_at?->format('Y-m-d H:i') ?? 'N/A' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 fw-bold">Media:</div>
                        <div class="col-md-9">
                            @if ($story->media)
                                @if ($story->is_video)
                                    <video width="320" height="240" controls>
                                        <source src="{{ $story->media }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                @else
                                    <img src="{{ $story->media }}" alt="Story Image" width="200" class="rounded">
                                @endif
                            @else
                                No media
                            @endif
                        </div>
                    </div>
                    
                    <a href="{{ route('admin.stories.index') }}" class="btn btn-secondary mt-2">Back to list</a>
                </div>
            </div>
        </div>
    </div>
@endsection
