<?php

namespace Modules\Admin\Http\Controllers\api;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\File;
use Modules\Admin\Service\AdminService;
use Modules\Common\Helper\UploaderHelper;

class AuthController extends Controller
{
    use ValidatesRequests,UploaderHelper;
     /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin_api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth('admin_api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth('admin_api')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('admin_api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('admin_api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('admin_api')->factory()->getTTL() * 60
        ]);
    }

   

    public function updateProfile(Request $request){
        $data = $request->all();
        $admin = (new AdminService())->findById(\Illuminate\Support\Facades\Auth::id());
        if (request()->hasFile('image')){
            File::delete(public_path('uploads/admin/'.$admin->image));
            $image = request()->file('image');
            $imageName = $this->upload($image, 'admin');
            $data['image'] = $imageName;
        }
        if ($data['password'] ?? null){
            $data['password'] =  bcrypt($request->get('password'));
        }
        $data = array_filter($data);
        $admin->update($data);
        return return_msg(true,'Profile Updated Successfully',$admin);
    }
}
