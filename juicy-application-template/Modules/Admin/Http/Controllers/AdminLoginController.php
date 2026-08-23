<?php

namespace Modules\Admin\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Modules\Admin\Entities\Admin;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Modules\Admin\Service\AdminService;
use Modules\Common\Helper\UploaderHelper;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Foundation\Validation\ValidatesRequests;

class AdminLoginController extends Controller
{
    use ValidatesRequests, UploaderHelper;

    public function __construct()
    {
        $this->middleware('guest:admin', ['except' => ['logout', 'EditProfile', 'updateProfile']]);
    }
    public function showLoginForm()
    {
        return view('admin::login');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];
        $admin = Admin::where('email', $credentials['email'])->first();
        if ($admin && $admin->is_active == 0) {
            return redirect()->back()->withInput($request->only('email', 'remember'))->withErrors(['email' => 'الحساب غير مفعل من قبل الإدارة.']);
        }
        if (Auth::guard('admin')->attempt(array_merge($credentials, ['is_active' => 1]), $request->remember)) {
            if (Auth::guard('admin')->user()->hasRole('Branch Manager'))
                return redirect()->intended(route('admin.statistics'));

            return redirect()->intended(route('admin.dashboard'));
        }
        return redirect()->back()->withInput($request->only('email', 'remember'))->withErrors(['email' => 'بيانات الدخول غير صحيحة.']);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }

    public function EditProfile()
    {
        $admin = (new AdminService())->findById(\Illuminate\Support\Facades\Auth::id());
        return view('admin::editProfile', compact('admin'));
    }

    public function updateProfile(Request $request)
    {
        $data = $request->except('_token');
        $admin = (new AdminService())->findById(\Illuminate\Support\Facades\Auth::id());
        if (request()->hasFile('image')) {
            File::delete(public_path('uploads/admin/' . $admin->image));
            $image = request()->file('image');
            $imageName = $this->upload($image, 'admin');
            $data['image'] = $imageName;
        }
        if ($data['password'] ?? null) {
            $data['password'] =  bcrypt($request->get('password'));
        }
        $data = array_filter($data);
        $admin->update($data);
        return redirect()->intended(route('admin.dashboard'));
    }
}
