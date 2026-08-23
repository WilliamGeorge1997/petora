<?php

namespace Modules\Branch\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Branch\Entities\ShortUrl;

class ShortUrlController extends Controller
{

    public function __invoke($code)
    {
        $url = ShortUrl::where('code', $code)->value('url');
        if (!$url)  abort(404);
        return redirect($url);
    }
}
