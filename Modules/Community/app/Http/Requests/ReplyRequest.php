<?php

namespace Modules\Community\Http\Requests;

use Illuminate\Validation\Validator;

class ReplyRequest extends CommentRequest
{
    /**
     * Get the "after" validation callables for the request.
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                $comment = $this->route('comment');

                if ($comment && $comment->parent_id !== null) {
                    $validator->errors()->add('parent_id', __('community::message.parent_id_max_depth'));
                }
            },
        ];
    }
}
