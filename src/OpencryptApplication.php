<?php
/**
 * Filename:OpensslCrypt.php
 * Author:ld
 * Date:2021/8/18
 */

namespace Lfyw\Opencrypt;
use Lfyw\Opencrypt\Exceptions\MissingOpencryptKeyException;

/**
 * 加解密
 */
class OpencryptApplication
{
    protected $publicKey;//公钥
    protected $privateKey;//私钥

    public function __construct()
    {
        $this->setKeys();
    }

    /**
     * Decrypt String.
     * Author:ld
     * Date:2021/8/18
     * @param $string
     * @param bool $base64Encode
     * @return string
     */
    public function decrypt($string, $base64Encode = true)
    {
        //如果未开启解密或者解密密钥文件不存在，则直接返回原数据
        if (!config('opencrypt.opencrypt') || !$this->getPrivateKey()) {
            return $string;
        }
        openssl_private_decrypt($base64Encode ? base64_decode($string) : $string, $decrypted, $this->getPrivateKey());

        return $decrypted;
    }

    /**
     * Encrypt string.
     * Author:ld
     * Date:2021/8/18
     * @param string $string
     * @param bool $base64Encode
     * @return mixed|string
     */
    public function encrypt($string, $base64Encode = true)
    {
        //如果未开启解密或者解密密钥文件不存在，则直接返回原数据
        if (!config('opencrypt.opencrypt') || !$this->getPublicKey()) {
            return $string;
        }

        openssl_public_encrypt($string, $encrypted, $this->getPublicKey());
        $encrypted = $base64Encode ? base64_encode($encrypted) : $encrypted;
        return $encrypted;
    }

    /**
     * Compare a encrypt string is equal to a decrypt string
     * Author::ld <littledragoner@163.com>
     * Date:2021/8/19
     * @param string $encrypted
     * @param string $decrypted
     * @param bool $base64Encode
     * @return bool
     */
    public function equal(string $encrypted, string $decrypted, $base64Encode = true)
    {
        $decryptedFromEncrypted = $this->decrypt($encrypted, $base64Encode);
        return $decrypted === $decryptedFromEncrypted;
    }

    /**
     * Get private key.
     * Author:ld
     * Date:2021/8/18
     * @return mixed
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }

    /**
     * Get public key.
     * Author:ld
     * Date:2021/8/18
     * @return mixed
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }


    /**
     * Set public key and private key
     * Author:ld
     * Date:2021/8/18
     */
    protected function setKeys()
    {
        if (!config('opencrypt.opencrypt')){
            return ;
        }

        if (config('opencrypt.opencrypt_type') == 'file') {
            $privateKeyFile = config('opencrypt.opencrypt_path') . DIRECTORY_SEPARATOR . config('opencrypt.opencrypt_private_key_filename');
            $publicKeyFile = config('opencrypt.opencrypt_path') . DIRECTORY_SEPARATOR . config('opencrypt.opencrypt_public_key_filename');
            throw_unless(file_exists($publicKeyFile) && file_exists($privateKeyFile), new MissingOpencryptKeyException('加密文件不存在'));
            $this->privateKey = file_get_contents($privateKeyFile);
            $this->publicKey = file_get_contents($publicKeyFile);
        } elseif (config('opencrypt.opencrypt_type') == 'env') {
            $this->privateKey = config('opencrypt.opencrypt_private_key');
            $this->publicKey = config('opencrypt.opencrypt_public_key');
        }

        $missKey = !($this->privateKey && $this->publicKey);
        throw_if(config('opencrypt.opencrypt_encrypt') && $missKey, new MissingOpencryptKeyException('密钥缺失'));
    }
}
