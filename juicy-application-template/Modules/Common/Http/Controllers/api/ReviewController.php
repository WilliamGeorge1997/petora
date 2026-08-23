<?php

namespace Modules\Common\Http\Controllers\api;

use Illuminate\Routing\Controller;
use Modules\Common\Http\Requests\ReviewRequest;
use Modules\Common\Service\ReviewService;

class ReviewController extends Controller
{
    private ReviewService $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    public function store(ReviewRequest $request)
    {
        try {
            $review = $this->reviewService->save($request->validated());

            return return_msg(true, 'Review submitted successfully', $review);
        } catch (\Exception $e) {
            return return_msg(false, $e->getMessage());
        }
    }
}
