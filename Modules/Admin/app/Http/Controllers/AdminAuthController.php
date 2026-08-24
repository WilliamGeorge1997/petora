<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Http\Requests\AdminLoginRequest;
use Modules\Admin\Models\Admin;
use Modules\Admin\Services\AdminService;

class AdminAuthController implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('guest:admin', except: ['logout', 'editProfile', 'updateProfile']),
        ];
    }

    public function showLoginForm()
    {
        return view('admin::login');
    }

    public function login(AdminLoginRequest $request)
    {
        $credentials = [
            'email' => $request->string('email'),
            'password' => $request->string('password'),
        ];

        $admin = Admin::where('email', $credentials['email'])->first();
        if ($admin && $admin->is_active == 0) {
            return redirect()->back()->withInput($request->only('email', 'remember'))->withErrors(['email' => __('admin::admin.account_inactive')]);
        }
        if (Auth::guard('admin')->attempt(array_merge($credentials, ['is_active' => 1]), $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }
        return redirect()->back()->withInput($request->only('email', 'remember'))->withErrors(['email' => __('admin::admin.invalid_credentials')]);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }

    public function EditProfile()
    {
        $admin = (new AdminService())->findById(Auth::id());
        return view('admin::editProfile', compact('admin'));
    }

    // public function updateProfile(Request $request)
    // {
    //     $data = $request->except('_token');
    //     $admin = (new AdminService())->findById(Auth::id());
    //     if (request()->hasFile('image')) {
    //         File::delete(public_path('uploads/admin/' . $admin->image));
    //         $image = request()->file('image');
    //         $imageName = $this->upload($image, 'admin');
    //         $data['image'] = $imageName;
    //     }
    //     if ($data['password'] ?? null) {
    //         $data['password'] =  bcrypt($request->input('password'));
    //     }
    //     $data = array_filter($data);
    //     $admin->update($data);
    //     return redirect()->intended(route('admin.dashboard'));
    // }
}
