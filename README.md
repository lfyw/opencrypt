<h1 align="center"> opencrypt </h1>

<p align="center"> A crypt sdk..</p>


## Installing

```shell
$ composer require lfyw/opencrypt -vvv
```

## Usage

方案参考[PHP 和 Web 端对称加密传输|JSEncrypt|CryptoJS](https://learnku.com/articles/8584/php-and-web-end-symmetric-encryption-transmission-jsencryptcryptojs),方便前后端简单加密解密的一个工具.

### 配置

#### 发布配置文件

```shell
$ php artisan vendor:publish --provider="Lfyw\Opencrypt\ServiceProvider"
```
#### 配置文件说明

加密状态决定是否对加密内容进行处理，建议本地开发时可以关闭以提高本地开发和测试效率。

密钥保存方式有两种，分别是文件保存方式和环境变量保存方式。顾名思义，文件保存方式会将密钥保存在文件里，密钥保存方式会将密钥写入`.env`文件。除此之外，其他没有什么区别。

```php
<?php
/**
 * Filename:openssl.php
 * Author:ld
 * Date:2021/8/18
 */
return [
    /** 是否开启加解密 **/
    'opencrypt' => env('OPENCRYPT', false),
    /** 密钥保存方式 file => 文件保存模式 'env' => 环境变量保存方式 **/
    'opencrypt_type' => env('OPENCRYPT_TYPE', 'file'),

    /** 仅文件保存模式时需要设置以下配置项 **/
    /** 文件保存模式-密钥地址 **/
    'opencrypt_path' => env('OPENCRYPT_PATH', resource_path('opencrypt')),
    /** 文件保存模式-私钥文件名称 **/
    'opencrypt_private_key_filename' => env('OPENCRYPT_PRIVATE_KEY_FILENAME', 'private_key.pem'),
    /** 文件保存模式-公钥文件名称**/
    'opencrypt_public_key_filename' => env('OPENCRYPT_PUBLIC_KEY_FILENAME', 'public_key.pem'),

    /** 以下配置项仅环境变量模式时有效 **/
    /** 公钥 **/
    'opencrypt_public_key' => env('OPENCRYPT_PUBLIC_KEY'),
    /** 私钥 **/
    'opencrypt_private_key' => env('OPENCRYPT_PRIVATE_KEY'),
];
```
设置`opencrypt' => env('OPENCRYPT', false)`为`true`,即可使用

### 使用说明

#### 生成密钥

```shell
$ php artisan opencrypt:generate
```
如果采用文件模式，会根据配置文件将密钥保存在对应路径；如果采用环境变量保存模式，会将变量直接写入`.env`文件。

#### 容器方法使用

```php
/** 加密 **/
app('opencrypt')->encrypt('123');//返回加密字符串
/** 解密 **/
app('opencrypt')->decrypt('123');//返回解密字符串,无法解密会返回null
/** 判断字符串加密后是否等于密文参数 **/
app('opencrypt')->equal('密文','123');//返回布尔值
/** 获取公钥 **/
app('opencrypt')->getPrivateKey();
/** 获取私钥 **/
app('opencrypt')->getPublicKey();
```
#### Facade

``` php
** 加密 **/
Opencrypt::encrypt('123');//返回加密字符串
/** 解密 **/
Opencrypt::decrypt('123');//返回解密字符串,无法解密会返回null
/** 判断字符串加密后是否等于密文参数 **/
Opencrypt::equal('密文','123');//返回布尔值
/** 获取公钥 **/
Opencrypt::getPrivateKey();
/** 获取私钥 **/
Opencrypt::getPublicKey();
```
#### 助手函数

```php
/** 加密 **/
openencrypt('123');//返回加密字符串
/** 解密 **/
opendecrypt('123');//返回解密字符串,无法解密会返回null
```

#### 其他

如果需要前端配合做一些加密操作.将公钥复制一份给前端，前端参考[PHP 和 Web 端对称加密传输|JSEncrypt|CryptoJS](https://learnku.com/articles/8584/php-and-web-end-symmetric-encryption-transmission-jsencryptcryptojs)进行编码。 

#### 参考

[PHP 和 Web 端对称加密传输|JSEncrypt|CryptoJS](https://learnku.com/articles/8584/php-and-web-end-symmetric-encryption-transmission-jsencryptcryptojs)

## Contributing

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/lfyw/opencrypt/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/lfyw/opencrypt/issues).
3. Contribute new features or update the wiki.

_The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and PSR-2 coding guidelines. Any new code contributions must be accompanied by unit tests where applicable._

## License

MIT