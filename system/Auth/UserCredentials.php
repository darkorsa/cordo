<?php

namespace System\Auth;

use OAuth2\Storage\UserCredentialsInterface;

class UserCredentials implements UserCredentialsInterface
{
    const LOGIN = 'demo';

    const PASSWORD = '$argon2i$v=19$m=1024,t=2,p=2$S2VFL3hZbDc5MzZjMmtwRQ$DPburl9sM/ZJlQEfYw984yGblG/e6ISOCkz3OoYCT4w'; // microframeworkdemopassword
    
    public function checkUserCredentials($username, $password)
    {
        if ($username != self::LOGIN || !password_verify($password, self::PASSWORD)) {
            return false;
        }

        return true;
    }

    public function getUserDetails($username)
    {
        return ['user_id' => 'demo_user_id'];
    }
}
