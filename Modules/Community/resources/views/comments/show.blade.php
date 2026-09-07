@extends('common::layouts.master')

@section('title', 'View Comment')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Comment Details</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-3 fw-bold">Client:</div>
                        <div class="col-md-9">{{ $comment->client?->name ?? 'N/A' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 fw-bold">Post ID:</div>
                        <div class="col-md-9">
                            <a href="{{ route('admin.posts.show', $comment->post_id) }}">#{{ $comment->post_id }}</a>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 fw-bold">Content:</div>
                        <div class="col-md-9">{{ $comment->content ?? 'No content' }}</div>
                    </div>
                    @if ($comment->parent_id)
                        <div class="row mb-2">
                            <div class="col-md-3 fw-bold">Parent Comment ID:</div>
                            <div class="col-md-9">
                                <a href="{{ route('admin.comments.show', $comment->parent_id) }}">#{{ $comment->parent_id }}</a>
                            </div>
                        </div>
                    @endif
                    <div class="row mb-2">
                        <div class="col-md-3 fw-bold">Replies:</div>
                        <div class="col-md-9">
                            <ul>
                                @forelse ($comment->replies as $reply)
                                    <li><a href="{{ route('admin.comments.show', $reply->id) }}">Reply #{{ $reply->id }}</a> - {{ Str::limit($reply->content, 50) }}</li>
                                @empty
                                    <li>No replies</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                    
                    <a href="{{ route('admin.comments.index') }}" class="btn btn-secondary mt-2">Back to list</a>
                </div>
            </div>
        </div>
    </div>
@endsection
