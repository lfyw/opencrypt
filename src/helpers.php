<?php

if (!function_exists('openencrypt')) {
    /**
     * openencrypt
     * 加密.
     *
     * @param mixed $string
     * @param mixed $base64Encode
     *
     * @return void
     */
    function openencrypt($string, $base64Encode = true)
    {
        return app('opencrypt')->encrypt($string, $base64Encode = true);
    }
}

if (!function_exists('opendecrypt')) {
    /**
     * opendecrypt
     * 加密.
     *
     * @param mixed $string
     * @param mixed $base64Encode
     *
     * @return void
     */
    function opendecrypt($string, $base64Encode = true)
    {
        return app('opencrypt')->decrypt($string, $base64Encode = true);
    }
}

if (!function_exists('openequal')) {
    /** openequal
     * 比较.
     *
     * @param mixed $encrypted    加密后的内容
     * @param mixed $decrypted    原始字符串
     * @param mixed $base64Encode
     *
     * @return void
     */
    function openequal(string $encrypted, string $decrypted, $base64Encode = true)
    {
        return app('opencrypt')->equal($encrypted, $decrypted, $base64Encode = true);
    }
}
