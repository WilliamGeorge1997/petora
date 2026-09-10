<?php

namespace Modules\Client\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Modules\Client\DTOs\ClientDto;
use Modules\Client\Http\Requests\ClientForgetPasswordRequest;
use Modules\Client\Http\Requests\ClientLoginRequest;
use Modules\Client\Http\Requests\ClientNewPasswordRequest;
use Modules\Client\Http\Requests\ClientRegisterRequest;
use Modules\Client\Http\Requests\ClientVerifyOtpRequest;
use Modules\Client\Models\Client;
use Modules\Client\Services\ClientService;

#[Middleware('auth:client', only: ['logout', 'me'])]
class ClientAuthController extends Controller
{
    public function register(ClientRegisterRequest $request, ClientService $clientService)
    {
        $dto = ClientDto::fromRegisterRequest($request);
        $client = $clientService->save($dto);

        return success(true, __('client::message.registered'), $client);
    }

    public function login(ClientLoginRequest $request, ClientService $clientService)
    {
        $credentials = $request->validated();

        /** @var Client $client */
        $client = $clientService->findBy('phone', $credentials['phone'])->first();

        if (! $client || ! Hash::check($credentials['password'], $client->password)) {
            return fail(false, __('client::message.unauthorized'), null, 'unauthorized');
        }

        if (! $client->is_active) {
            return fail(false, __('client::message.not_active'), null, 'unauthorized');
        }

        if ($request->has('fcm_token')) {
            $client->update(['fcm_token' => $credentials['fcm_token']]);
        }

        $client->tokens()->delete();
        $token = $client->createToken('client_token')->plainTextToken;

        return $this->respondWithToken($client, $token);
    }

    public function verify(ClientVerifyOtpRequest $request, ClientService $clientService)
    {
        $data = $request->validated();

        /** @var Client $client */
        $client = $clientService->findBy('phone', $data['phone'])->first();

        if ($client && $client->is_active) {
            return fail(false, __('client::message.already_active'), null, 'unauthorized');
        }

        if ($client && $client->verify_code == $data['otp']) {
            $updateData = ['is_active' => true];

            if ($request->has('fcm_token')) {
                $updateData['fcm_token'] = $data['fcm_token'];
            }

            $client->update($updateData);

            $token = $client->createToken('client_token')->plainTextToken;

            return $this->respondWithToken($client, $token);
        }

        return fail(false, __('client::message.wrong_otp'), null, 'unauthorized');
    }

    public function forgetPassword(ClientForgetPasswordRequest $request, ClientService $clientService)
    {
        $data = $request->validated();

        $client = $clientService->findBy('phone', $data['phone'])->first();
        $verify_code = app()->environment('local') ? 999999 : rand(100000, 999999);

        $client->update(['verify_code' => $verify_code]);

        // $smsService = new SMSService();
        // $smsService->sendSMS($client->phone, $verify_code);

        return success(true, __('client::message.otp_sent'));
    }

    public function verifyForgetPassword(ClientVerifyOtpRequest $request, ClientService $clientService)
    {
        $data = $request->validated();

        $client = $clientService->findBy('phone', $data['phone'])->first();

        if ($client && $client->verify_code == $data['otp']) {
            return success(true, __('client::message.valid_otp'));
        }

        return fail(false, __('client::message.wrong_otp'), null, 'unauthorized');
    }

    public function newPassword(ClientNewPasswordRequest $request, ClientService $clientService)
    {
        $data = $request->validated();

        $client = $clientService->findBy('phone', $data['phone'])->first();
        $client->update(['password' => Hash::make($data['password'])]);

        return success(true, __('client::message.password_changed'));
    }

    public function me(Request $request)
    {
        return success(true, __('client::message.logged_user'), $request->user('client'));
    }

    public function logout(Request $request)
    {
        /** @var Client $client */
        $client = $request->user('client');

        /** @var PersonalAccessToken $token */
        $token = $client->currentAccessToken();
        $token->delete();

        return success(true, __('client::message.logged_out'));
    }

    protected function respondWithToken(Client $client, string $token)
    {
        $data = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'client' => $client,
        ];

        return success(true, __('client::message.authenticated'), $data);
    }
}
