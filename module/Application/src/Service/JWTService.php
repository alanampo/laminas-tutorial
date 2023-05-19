<?php

namespace Application\Service;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTService
{

    private const key = 'AMPO';

    /**     
     * create Token
     *
     * @param array $user
     * @return string
     */
    public static function createToken(array $user): string
    {

        $payload = [
            "user" => $user,
            "exp" => time() + 3600
        ];

        return JWT::encode($payload, self::key, 'HS256');
    }

    /**
     * check Token
     *
     * @param string $token
     * @return \stdClass                    The JWT's payload as a PHP object
     * @throws \InvalidArgumentException     Provided key/key-array was empty or malformed
     * @throws \DomainException              Provided JWT is malformed
     * @throws \UnexpectedValueException     Provided JWT was invalid
     * @throws \Firebase\JWT\SignatureInvalidException    Provided JWT was invalid because the signature verification failed
     * @throws \Firebase\JWT\BeforeValidException         Provided JWT is trying to be used before it's eligible as defined by 'nbf'
     * @throws \Firebase\JWT\BeforeValidException         Provided JWT is trying to be used before it's been created as defined by 'iat'
     * @throws \Firebase\JWT\ExpiredException             Provided JWT has since expired, as defined by the 'exp' claim 
     * 
     */
    public static function checkToken(string $token)
    {
        return JWT::decode($token, new Key(self::key, 'HS256'));
    }

}
