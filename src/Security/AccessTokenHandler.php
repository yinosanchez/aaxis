<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class AccessTokenHandler implements AccessTokenHandlerInterface
{
    private UserRepository $repository;
    private JWTEncoderInterface $jwtEncoder;

    public function __construct(
        UserRepository $repository,
        JWTEncoderInterface $jwtEncoder
    ) {
        $this->repository = $repository;
        $this->jwtEncoder = $jwtEncoder;
    }

    public function getUserBadgeFrom(string $token): UserBadge
    {
        try {
            $payload = $this->jwtEncoder->decode($token);
        } catch (JWTDecodeFailureException $e) {
            throw new \InvalidArgumentException('Invalid token');
        }

        $user = $this->repository->findOneBy(['username' => $payload['username']]);
        // e.g. query the "access token" database to search for this token
        if (!$user) {
            throw new \InvalidArgumentException('Invalid token');
        }

        // and return a UserBadge object containing the user identifier from the found token
        return new UserBadge($user->getId());
    }
}