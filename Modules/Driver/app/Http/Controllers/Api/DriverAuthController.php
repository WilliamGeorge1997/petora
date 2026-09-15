<?php

namespace Modules\Driver\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Modules\Driver\Http\Requests\DriverChangePasswordRequest;
use Modules\Driver\Http\Requests\DriverLoginRequest;
use Modules\Driver\Models\Driver;
use Modules\Driver\Services\DriverService;

#[Middleware('auth:driver', only: ['logout', 'me', 'changeAvailability', 'unActive', 'changePassword'])]
class DriverAuthController extends Controller
{
    public function login(DriverLoginRequest $request, DriverService $driverService)
    {
        $credentials = $request->validated();

        /** @var Driver|null $driver */
        $driver = $driverService->findBy('phone', $credentials['phone'])->first();

        if (! $driver || ! Hash::check($credentials['password'], $driver->password)) {
            return fail(false, __('driver::message.unauthorized'), null, 'unauthorized');
        }

        if (! $driver->is_active) {
            return fail(false, __('driver::message.not_active'), null, 'unauthorized');
        }

        $updateData = [];
        if (! empty($credentials['fcm_token'])) {
            $updateData['fcm_token'] = $credentials['fcm_token'];
        }

        if (! empty($updateData)) {
            $driver->update($updateData);
        }

        $driver->tokens()->delete();
        $token = $driver->createToken('driver_token')->plainTextToken;

        return $this->respondWithToken($driver, $token);
    }

    public function me(Request $request)
    {
        return success(true, __('driver::message.logged_user'), $request->user('driver'));
    }

    public function logout(Request $request)
    {
        /** @var Driver $driver */
        $driver = $request->user('driver');
        $driver->update(['fcm_token' => null]);
        
           /** @var PersonalAccessToken $token */
        $token = $driver->currentAccessToken();
        $token->delete();

        return success(true, __('driver::message.logged_out'));
    }

    public function changeAvailability(Request $request, DriverService $driverService)
    {
        /** @var Driver $driver */
        $driver = $request->user('driver');
        $driver = $driverService->availability($driver);

        return success(true, __('driver::message.availability_changed'), $driver);
    }

    public function unActive(Request $request)
    {
        /** @var Driver $driver */
        $driver = $request->user('driver');
        $driver->update(['is_active' => false]);

        return success(true, __('driver::message.deactivated'), $driver);
    }

    public function changePassword(DriverChangePasswordRequest $request)
    {
        /** @var Driver $driver */
        $driver = $request->user('driver');

        $driver->update(['password' => Hash::make($request->validated('new_password'))]);

        return success(true, __('driver::message.password_changed'), $driver);
    }

    protected function respondWithToken(Driver $driver, string $token)
    {
        $data = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'driver' => $driver,
        ];

        return success(true, __('driver::message.authenticated'), $data);
    }
}
