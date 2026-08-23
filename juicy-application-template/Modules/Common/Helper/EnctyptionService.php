<?php

namespace Modules\Common\Helper;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class EnctyptionService
{

    public static function encrypt($data)
    {
        $encrypted = Crypt::encryptString($data);
        return base64_encode($encrypted);
    }

    public static function decrypt($encryptedData)
    {
        try {
            $decoded = base64_decode($encryptedData);
            return Crypt::decryptString($decoded);
        } catch (DecryptException $e) {
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function generateEncryptedToken($data)
    {
        return self::encrypt($data);
    }
}