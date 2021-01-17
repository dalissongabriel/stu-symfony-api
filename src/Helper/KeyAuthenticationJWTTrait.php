<?php


namespace App\Helper;


trait KeyAuthenticationJWTTrait
{
    public static function getKey(): string
    {
        return '$argon2i$v=19$m=65536,t=4,p=1$dzFUTVpyMk5ENk5RcDRkMA$oDnxVbg/A6heA6rOxncfp/zktWinhB/1LmSWCkJYXOg';;
    }
}