<?php

namespace Lfyw\Opencrypt;

use Illuminate\Support\Facades\Facade;

/**
 * openssl crypt加解密facade.
 *
 * @method static string decrypt(string $string, bool $base64Encode = true)
 * @method static string encrypt(string $string, bool $base64Encode = true)
 * @method static string getPrivateKey()
 * @method static string getPublicKey()
 * @method static bool equal(string $encrypted, string $decrypted, bool $base64Encode = true)
 */
class Opencrypt extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'opencrypt';
    }
}
