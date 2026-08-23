<?php

namespace Modules\Gallery\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Gallery\Service\GalleryService;

class GalleryController extends Controller
{
    public function __construct(private GalleryService $gallerService)
    {
    }

    public function index($id, Request $request)
    {
        $data = $request->all();
        $galleries = $this->gallerService->findBy('branch_id', $id, $data, ['id', 'image']);
        return return_msg(true, 'Gallery Fetched Successfully', $galleries);
    }
}
