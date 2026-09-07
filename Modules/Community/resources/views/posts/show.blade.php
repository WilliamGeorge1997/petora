@extends('common::layouts.master')

@section('title', 'View Post')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Post Details</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-3 fw-bold">Client:</div>
                        <div class="col-md-9">{{ $post->client?->name ?? 'N/A' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 fw-bold">Tagged Pet:</div>
                        <div class="col-md-9">{{ $post->pet?->name ?? 'None' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 fw-bold">Content:</div>
                        <div class="col-md-9">{{ $post->content ?? 'No content' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 fw-bold">Images:</div>
                        <div class="col-md-9">
                            @foreach ($post->images as $image)
                                <img src="{{ $image->image }}" alt="Post Image" width="150" class="me-1 mb-1 rounded">
                            @endforeach
                            @if ($post->images->isEmpty())
                                No images
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 fw-bold">Hashtags:</div>
                        <div class="col-md-9">
                            @foreach ($post->hashtags as $hashtag)
                                <span class="badge bg-primary">#{{ $hashtag->text }}</span>
                            @endforeach
                            @if ($post->hashtags->isEmpty())
                                No hashtags
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary mt-2">Back to list</a>
                </div>
            </div>
        </div>
    </div>
@endsection
