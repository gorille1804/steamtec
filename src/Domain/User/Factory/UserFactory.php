<?php

namespace Domain\User\Factory;

use Domain\User\Data\Contract\CreateUserRequest;
use Domain\User\Data\Contract\UpdateUserRequest;
use Domain\User\Data\Model\User;
use Domain\User\Data\ObjectValue\UserId;

class UserFactory
{
    public  static function make(
        CreateUserRequest $request, 
    ): User	
    {
        return new User(
            UserId::make(),
            $request->email,
            $request->roles,
            $request->firstname,
            $request->lastname,
            $request->phone,
            $request->socity,
            null,
            new \DateTimeImmutable(),
        );
    }

    public static function update(User $user, UpdateUserRequest $request): User
    {
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->phone = $request->phone;
        $user->socity = $request->socity;

        return $user;
    }

    public static function makeFromUser(User $user): UpdateUserRequest
    {
      
        $formRequest = new UpdateUserRequest();
        $formRequest->firstname = $user->firstname;
        $formRequest->lastname = $user->lastname;
        $formRequest->phone = $user->phone;
        $formRequest->socity = $user->socity;

        return $formRequest;	

    }	

}