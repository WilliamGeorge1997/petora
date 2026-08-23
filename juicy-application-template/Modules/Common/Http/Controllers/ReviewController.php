<?php

namespace Modules\Common\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Common\Service\ReviewService;

class ReviewController extends Controller
{
    private ReviewService $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->middleware(['auth:admin', 'prevent-back-history']);
        $this->reviewService = $reviewService;
    }

    public function index(Request $request)
    {
        $data['paginated'] = 50;
        $reviews = $this->reviewService->findAll($data);

        if ($request->ajax()) {
            return response()->json(['data' => $reviews->items()]);
        }

        $averageRatings = null;
        if (Auth::guard('admin')->user()->hasRole('Branch Manager')) {
            $averageRatings = $this->reviewService->getAverageRatings();
        }

        return view('common::reviews.index', [
            'reviews' => $reviews,
            'averageRatings' => $averageRatings,
        ]);
    }

    public function destroy($id)
    {
        $this->reviewService->delete($id);

        return response()->json(['data' => 'success'], 200);
    }
}
