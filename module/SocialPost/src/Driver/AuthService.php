<?php

namespace SocialPost\Driver;

class AuthService
{
    public function getUserData(): array
    {
        return [
            'email' => 'your@email.address',
            'name'  => 'YourName',
        ];
    }
}
