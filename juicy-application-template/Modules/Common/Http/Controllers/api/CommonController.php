<?php

namespace Modules\Common\Http\Controllers\api;

use Illuminate\Routing\Controller;
use Modules\Common\Service\CommonService;
use Illuminate\Support\Facades\Mail;
use Modules\Common\Mail\contactUs;
use Modules\Common\Http\Requests\ContactUsRequest;

class CommonController extends Controller
{

    private $CommonService;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(CommonService $CommonService)
    {
        $this->CommonService = $CommonService;
    }

    public function terms($lang)
    {
        return return_msg(true, 'Terms Data  ', getSetting('terms_' . $lang));
    }

    public function about($lang)
    {
        return return_msg(true, 'About Data  ', getSetting('about_' . $lang));
    }

    public function contactUs(ContactUsRequest $request)
    {

        Mail::to(getSetting('email'))->send(new contactUs($request->phone, @$request->email, $request->message));

        return return_msg(true, 'Message Sent successfully');
    }

    public function tax()
    {
        return return_msg(true, 'Tax fetched successfully', getSetting('tax'));
    }

    public function currency()
    {
        $currencies = $this->CommonService->findWhereIn(['currency_ar', 'currency_en']);
        return return_msg(true, 'Currency fetched successfully', $currencies);
    }


    public function socialLinks()
    {
        $socialLinks = $this->CommonService->findWhereIn(['facebook', 'youtube', 'instagram', 'x', 'snapchat', 'tiktok', 'whatsapp', 'telegram']);
        return return_msg(true, 'Social media links fetched successfully', $socialLinks);
    }
}
