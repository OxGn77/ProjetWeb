<?php
    class Token
    {
        public static $secret;

        public static function generate($payload)
        {
            self::$secret = Config::getConfig()->secretJWT;
            return JWT::encode($payload,self::$secret,'HS256');
        }

        public static function decode($token)
        {
            
            self::$secret = Config::getConfig()->secretJWT;
            return JWT::decode($token,self::$secret, array('HS256'));
        }
    }