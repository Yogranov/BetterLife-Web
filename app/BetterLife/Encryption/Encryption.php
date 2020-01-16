<?php
/**
 * Created by PhpStorm.
 * User: Yogev
 * Date: 14-Apr-18
 * Time: 20:43
 */

namespace BetterLife\System;


class Encryption {
    const method = "aes-256-cbc";

    /**
     * @param string $data
     * @return string
     */
    public static function Encrypt(string $data) {
        $key = \Credential::GetCredential(Constant::MYSQL_DATABASE . "_encrypt_key")->GetPassword();
        $iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);
        $encrypt = base64_encode(openssl_encrypt($data, self::method, $key, OPENSSL_RAW_DATA, $iv));

        return $encrypt;
    }

    /**
     * @param string $encryptedData
     * @return string
     */
    public static function Decrypt(string $encryptedData) {
        $key = \Credential::GetCredential(Constant::MYSQL_DATABASE . "_encrypt_key")->GetPassword();
        $iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);
        $decrypt = openssl_decrypt(base64_decode($encryptedData), self::method, $key, OPENSSL_RAW_DATA, $iv);

        return $decrypt;
    }
}