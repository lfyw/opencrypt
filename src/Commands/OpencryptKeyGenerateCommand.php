<?php

namespace Lfyw\Opencrypt\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Support\Str;

class OpencryptKeyGenerateCommand extends Command
{
    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'opencrypt:generate
                    {--show : Display the key instead of modifying files}
                    {--force : Force the operation to run when in production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the openssl key';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * The key will be saved in the environment file.
     *
     * @return
     */
    public function handle()
    {
        $keyPairs = $this->generateRandomKey();

        if ($this->option('show')) {
            $this->line('<comment>'.$keyPairs['privateKey'].'</comment>');
            $this->line('<comment>'.$keyPairs['publicKey'].'</comment>');

            return;
        }
        //环境变量模式：密钥保存到.env环境中；文件模式：密钥保存到文件中
        if ('env' === config('opencrypt.opencrypt_type')) {
            if (!$this->setKeyInEnvironmentFile($keyPairs)) {
                return;
            }
        } elseif ('file' === config('opencrypt.opencrypt_type')) {
            if (!$this->setKey($keyPairs)) {
                return;
            }
        }

        $this->info('Openssl key set successfully.');
    }

    /**
     * Generate a random openssl key for the application.
     */
    protected function generateRandomKey(): array
    {
        $keyPairs = openssl_pkey_new();
        openssl_pkey_export($keyPairs, $privateKey);
        $publicKey = openssl_pkey_get_details($keyPairs);

        return ['privateKey' => $privateKey, 'publicKey' => $publicKey['key']];
    }

    /**
     * Set the openssl key in the environment file.
     *
     * @param array $keyPairs
     *
     * @return bool
     */
    protected function setKeyInEnvironmentFile($keyPairs)
    {
        $currentPrivateKey = $this->laravel['config']['opencrypt.opencrypt_private_key'];
        $currentPublicKey = $this->laravel['config']['opencrypt.opencrypt_public_key'];

        if (0 !== strlen($currentPrivateKey) && 0 !== strlen($currentPublicKey) && (!$this->confirmToProceed())) {
            return false;
        }

        $this->writeNewEnvironmentFileWith($keyPairs);

        return true;
    }

    /**
     * Write a new environment file with the given openssl key.
     *
     * @param array $keyPairs
     *
     * @return void
     */
    protected function writeNewEnvironmentFileWith($keyPairs)
    {
        if (false === Str::contains(file_get_contents($this->laravel->environmentFilePath()), 'OPENCRYPT_PUBLIC_KEY')) {
            file_put_contents($this->laravel->environmentFilePath(), PHP_EOL."OPENCRYPT_PUBLIC_KEY=\"{$keyPairs['publicKey']}\"".PHP_EOL, FILE_APPEND);
        } else {
            file_put_contents($this->laravel->environmentFilePath(), str_replace(
                'OPENCRYPT_PUBLIC_KEY="'.$this->laravel['config']['opencrypt.opencrypt_public_key'].'"',
                'OPENCRYPT_PUBLIC_KEY="'.$keyPairs['publicKey'].'"', file_get_contents($this->laravel->environmentFilePath())
            ));
        }
        if (false === Str::contains(file_get_contents($this->laravel->environmentFilePath()), 'OPENCRYPT_PRIVATE_KEY')) {
            file_put_contents($this->laravel->environmentFilePath(), PHP_EOL."OPENCRYPT_PRIVATE_KEY=\"{$keyPairs['privateKey']}\"".PHP_EOL, FILE_APPEND);
        } else {
            file_put_contents($this->laravel->environmentFilePath(), str_replace(
                'OPENCRYPT_PRIVATE_KEY="'.$this->laravel['config']['opencrypt.opencrypt_private_key'].'"',
                'OPENCRYPT_PRIVATE_KEY="'.$keyPairs['privateKey'].'"', file_get_contents($this->laravel->environmentFilePath())
            ));
        }
    }

    /**
     * Set the public key and private key in the openssl key file.
     * Author:ld
     * Date:2021/8/18.
     *
     * @param $keyPairs
     *
     * @return bool
     */
    protected function setKey($keyPairs)
    {
        $currentPrivateKey = file_get_contents($this->getOpensslPrivateKeyFilePath());
        $currentPublicKey = file_get_contents($this->getOpensslPublicKeyFilePath());
        if (0 !== strlen($currentPrivateKey) && 0 !== strlen($currentPublicKey) && (!$this->confirmToProceed())) {
            return false;
        }
        file_put_contents($this->getOpensslPrivateKeyFilePath(), $keyPairs['privateKey']);
        file_put_contents($this->getOpensslPublicKeyFilePath(), $keyPairs['publicKey']);

        return true;
    }

    /**
     * Get the file which save the private key.
     * Author:ld
     * Date:2021/8/18.
     *
     * @return string
     */
    protected function getOpensslPrivateKeyFilePath()
    {
        $path = $this->laravel['config']['opencrypt.opencrypt_path'];
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        $privateKeyFilename = $this->laravel['config']['opencrypt.opencrypt_private_key_filename'];

        if (!file_exists($path.DIRECTORY_SEPARATOR.$privateKeyFilename)) {
            fopen($path.DIRECTORY_SEPARATOR.$privateKeyFilename, 'w+');
        }

        return $path.DIRECTORY_SEPARATOR.$privateKeyFilename;
    }

    /**
     * Get the file which save the public key.
     * Author:ld
     * Date:2021/8/18.
     *
     * @return string
     */
    protected function getOpensslPublicKeyFilePath()
    {
        $path = $this->laravel['config']['opencrypt.opencrypt_path'];
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        $publicKeyFilename = $this->laravel['config']['opencrypt.opencrypt_public_key_filename'];
        if (!file_exists($path.DIRECTORY_SEPARATOR.$publicKeyFilename)) {
            fopen($path.DIRECTORY_SEPARATOR.$publicKeyFilename, 'w+');
        }

        return $path.DIRECTORY_SEPARATOR.$publicKeyFilename;
    }
}
