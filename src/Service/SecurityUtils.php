<?php

namespace App\Service;

use App\Entity;
use App\Service;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class SecurityUtils
{
    /**
     * @var TokenStorageInterface
     */
    private TokenStorageInterface $tokenStorage;

    /**
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        TokenStorageInterface $tokenStorage
    ) {
        $this->tokenStorage = $tokenStorage;
    }


    /**
     * If a user is logged in, return the User entity.
     *
     * @return Entity\User|null
     */
    public function getUser(): ?Entity\User
    {
        //if there is no token, we cannot call getUser() func
        if ($this->tokenStorage->getToken()) {
            $user = $this->tokenStorage->getToken()->getUser();
        } else {
            $user = null;
        }

        if (!$user instanceof Entity\User) {
            return null;
        }

        return $user;
    }


    /**
     * @return bool
     */
    public function isUserLoggedIn(): bool
    {
        return (bool)$this->getUser();
    }

    /**
     * @return Entity\User
     */
    public function getLoggedInUser(): Entity\User
    {
        if (
            !($token = $this->tokenStorage->getToken())
            || !($user = $token->getUser()) instanceof Entity\User
        ) {
            throw new AuthenticationException();
        }

        return $user;
    }
}
