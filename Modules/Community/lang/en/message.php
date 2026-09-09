<?php

return [
    'hashtag' => [
        'index' => 'Hashtags',
        'fetched' => 'Hashtags fetched successfully',
        'created' => 'Hashtag created successfully',
        'updated' => 'Hashtag updated successfully',
        'deleted' => 'Hashtag deleted successfully',
        'activated' => 'Hashtag activated successfully',
        'deactivated' => 'Hashtag deactivated successfully',
    ],
    'post' => [
        'index' => 'Posts',
        'fetched' => 'Posts fetched successfully',
        'created' => 'Post created successfully',
        'updated' => 'Post updated successfully',
        'deleted' => 'Post deleted successfully',
        'activated' => 'Post activated successfully',
        'deactivated' => 'Post deactivated successfully',
    ],
    'comment' => [
        'index' => 'Comments',
        'fetched' => 'Comments fetched successfully',
        'created' => 'Comment created successfully',
        'updated' => 'Comment updated successfully',
        'deleted' => 'Comment deleted successfully',
        'activated' => 'Comment activated successfully',
        'deactivated' => 'Comment deactivated successfully',
    ],
    'like' => [
        'index' => 'Likes',
        'fetched' => 'Likes fetched successfully',
        'created' => 'Like created successfully',
        'deleted' => 'Like deleted successfully',
    ],
    'story' => [
        'index' => 'Stories',
        'fetched' => 'Stories fetched successfully',
        'created' => 'Story created successfully',
        'updated' => 'Story updated successfully',
        'deleted' => 'Story deleted successfully',
        'activated' => 'Story activated successfully',
        'deactivated' => 'Story deactivated successfully',
    ],
    'follow' => [
        'followers_fetched' => 'Followers fetched successfully.',
        'following_fetched' => 'Following fetched successfully.',
        'created' => 'Followed successfully.',
        'deleted' => 'Unfollowed successfully.',
    ],
    'follow_self' => 'You cannot follow yourself.',
    'block' => [
        'index' => 'Blocks',
        'fetched' => 'Blocks fetched successfully',
        'created' => 'Block created successfully',
        'deleted' => 'Block deleted successfully',
    ],

    // Validation Messages
    'client_id_required' => 'Client selection is required.',
    'client_id_integer' => 'Invalid client selection.',
    'client_id_exists' => 'Selected client does not exist.',

    'pet_id_required' => 'Pet selection is required.',
    'pet_id_integer' => 'Invalid pet selection.',
    'pet_id_exists' => 'Selected pet does not exist.',

    'post_id_required' => 'Post selection is required.',
    'post_id_integer' => 'Invalid post selection.',
    'post_id_exists' => 'Selected post does not exist.',

    'parent_id_required' => 'Parent comment selection is required.',
    'parent_id_integer' => 'Invalid parent comment selection.',
    'parent_id_exists'     => 'Selected parent comment does not exist.',
    'parent_id_max_depth'  => 'You can only reply to a comment, not to a reply.',

    'content_required' => 'Content is required.',
    'content_or_media_required' => 'Either content or media is required.',
    'content_string' => 'Content must be a string.',

    'is_active_required' => 'Status is required.',
    'is_active_boolean' => 'Invalid status format.',

    'media_required' => 'Media is required.',
    'media_invalid' => 'The uploaded file is invalid.',
    'media_mimes' => 'Media must be a file of type: jpeg, png, jpg, webp, svg, gif, mp4, mov, avi.',
    'media_max' => 'Media must not be greater than 20480 kilobytes.',

    'is_video_required' => 'Video indicator is required.',
    'is_video_boolean' => 'Invalid video indicator format.',

    'likeable_id_required' => 'Liked item is required.',
    'likeable_id_integer' => 'Invalid liked item selection.',
    'likeable_id_exists' => 'Selected liked item does not exist.',
    'likeable_type_required' => 'Liked type is required.',
    'likeable_type_string' => 'Invalid liked type format.',

    'follower_id_required' => 'Follower is required.',
    'follower_id_integer' => 'Invalid follower selection.',
    'follower_id_exists' => 'Selected follower does not exist.',

    'following_id_required' => 'Following user is required.',
    'following_id_integer' => 'Invalid following user selection.',
    'following_id_exists' => 'Selected following user does not exist.',

    'blocker_id_required' => 'Blocker is required.',
    'blocker_id_integer' => 'Invalid blocker selection.',
    'blocker_id_exists' => 'Selected blocker does not exist.',

    'blocked_id_required' => 'Blocked user is required.',
    'blocked_id_integer' => 'Invalid blocked user selection.',
    'blocked_id_exists' => 'Selected blocked user does not exist.',

    'expires_at_required' => 'Expiration date is required.',
    'expires_at_date' => 'Expiration date must be a valid date.',

    'text_required' => 'Hashtag text is required.',
    'text_string' => 'Hashtag text must be a string.',
    'text_unique' => 'This hashtag already exists.',

    'hashtags_array' => 'Hashtags must be an array.',
    'hashtags_string' => 'Each hashtag must be a string.',
    'hashtags_max' => 'Each hashtag must not exceed 100 characters.',
];
