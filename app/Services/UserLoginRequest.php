<?php

namespace App\Services;

use App\Models\User;

class UserLoginRequest
{
    private string $password;
    private string $email;

    public function __construct(string $email, string $password)
    {
        $this->password = $password;
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function checkRequest(User $user): bool
    {
        return password_verify($this->password, $user->getPassword()) && $this->email === $user->getEMail();
    }
}