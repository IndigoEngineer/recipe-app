<?php

namespace App\EntityListener ;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserListener{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher){
        $this->passwordHasher = $passwordHasher;
    }

    public function prePersist(User $user){
        $this->encodePassword($user);
    }

//    public function preUpdate(User $user){
//        $this->encodePassword($user);
//    }

    public function encodePassword (User $user){
        if ($user->getPlainPassword() === null ){
            return;
        }
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $user->getPlainPassword()
        );
        $user->setPassword($hashedPassword);
        $user->setPlainPassword(null);
    }
}
