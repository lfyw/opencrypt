<?php
/**
 * Filename:openssl.php
 * Author:ld
 * Date:2021/8/18.
 */
return [
    /* 是否开启加解密 **/
    'opencrypt' => env('OPENCRYPT', false),
    /* 密钥保存方式 file => 文件保存模式 'env' => 环境变量保存方式 **/
    'opencrypt_type' => env('OPENCRYPT_TYPE', 'file'),

    /* 仅文件保存模式时需要设置以下配置项 **/
    /* 文件保存模式-密钥地址 **/
    'opencrypt_path' => env('OPENCRYPT_PATH', resource_path('opencrypt')),
    /* 文件保存模式-私钥文件名称 **/
    'opencrypt_private_key_filename' => env('OPENCRYPT_PRIVATE_KEY_FILENAME', 'private_key.pem'),
    /* 文件保存模式-公钥文件名称**/
    'opencrypt_public_key_filename' => env('OPENCRYPT_PUBLIC_KEY_FILENAME', 'public_key.pem'),

    /* 以下配置项仅环境变量模式时有效 **/
    /* 公钥 **/
    'opencrypt_public_key' => env('OPENCRYPT_PUBLIC_KEY'),
    /* 私钥 **/
    'opencrypt_private_key' => env('OPENCRYPT_PRIVATE_KEY'),
];
